<?php
namespace App\Core;

use PDO;

class PalmaresCache extends DbConnect
{
    private PDO $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = $this->getConnection();
    }

    // Point d'entrée principal.
    // À appeler après toute modification sur gp_points, penalties, manual_adjustments.
    public function rebuild(int $season_id): void
    {
        $this->rebuildDriversStandings($season_id);
        $this->rebuildTeamsStandings($season_id);
        $this->rebuildDriversPalmares();
        $this->rebuildTeamsPalmares();
    }

    // Initialisation complète — à appeler une seule fois pour peupler le cache
    // sur toutes les saisons existantes.
    public function rebuildAll(): void
    {
        $seasons = $this->db->query("SELECT id FROM seasons")->fetchAll();
        foreach ($seasons as $season) {
            $this->rebuildDriversStandings($season->id);
            $this->rebuildTeamsStandings($season->id);
        }
        $this->rebuildDriversPalmares();
        $this->rebuildTeamsPalmares();
    }

    // NIVEAU 1 : Standings par saison

    private function rebuildDriversStandings(int $season_id): void
    {
        $this->db->prepare("DELETE FROM cache_drivers_standings WHERE season_id = ?")
                 ->execute([$season_id]);

        $sql = "
            INSERT INTO cache_drivers_standings
                (season_id, driver_id, season_number, season_status, category, nickname, team_name, total_points, wins, podiums)
            SELECT
                s.id,
                d.id,
                s.season_number,
                s.status,
                c.name,
                d.nickname,
                t.name,
                COALESCE(SUM(gp_pts.points_numeric), 0)
                    + COALESCE(ma.total_points, 0)
                    - COALESCE(SUM(p.points_removed), 0),
                SUM(CASE WHEN gp_pts.position = 1 THEN 1 ELSE 0 END),
                SUM(CASE WHEN gp_pts.position IN (1,2,3) THEN 1 ELSE 0 END)
            FROM seasons s
            JOIN categories c ON c.id = s.category_id
            JOIN gp g ON g.season_id = s.id
            JOIN gp_points gp_pts ON gp_pts.gp_id = g.id
            JOIN drivers d ON d.id = gp_pts.driver_id
            LEFT JOIN teams_drivers td ON td.driver_id = d.id AND td.season_id = s.id
            LEFT JOIN teams t ON t.id = td.team_id
            LEFT JOIN (
                SELECT season_id, driver_id, SUM(points) AS total_points
                FROM manual_adjustments
                GROUP BY season_id, driver_id
            ) ma ON ma.season_id = s.id AND ma.driver_id = d.id
            LEFT JOIN penalties p ON p.driver_id = d.id AND p.gp_id = g.id
            WHERE s.id = ?
            GROUP BY s.id, s.season_number, s.status, c.name, d.id, d.nickname, t.name, ma.total_points
        ";
        $this->db->prepare($sql)->execute([$season_id]);
    }

    private function rebuildTeamsStandings(int $season_id): void
    {
        $this->db->prepare("DELETE FROM cache_teams_standings WHERE season_id = ?")
                 ->execute([$season_id]);

        $sql = "
            INSERT INTO cache_teams_standings
                (season_id, team_id, season_number, category, team_name, total_points)
            SELECT
                s.id,
                t.id,
                s.season_number,
                c.name,
                t.name,
                COALESCE(SUM(gp_pts.points_numeric), 0)
                    + COALESCE(ma.total_points, 0)
                    - COALESCE(SUM(p.points_removed), 0)
            FROM seasons s
            JOIN categories c ON c.id = s.category_id
            JOIN teams t
            LEFT JOIN gp g ON g.season_id = s.id
            JOIN gp_points gp_pts ON gp_pts.gp_id = g.id AND gp_pts.team_id = t.id
            LEFT JOIN (
                SELECT season_id, team_id, SUM(points) AS total_points
                FROM manual_adjustments
                GROUP BY season_id, team_id
            ) ma ON ma.season_id = s.id AND ma.team_id = t.id
            LEFT JOIN penalties p ON p.gp_id = g.id AND p.team_id = t.id AND p.driver_id = gp_pts.driver_id
            WHERE s.id = ?
            GROUP BY s.id, s.season_number, c.name, t.id, t.name, ma.total_points
        ";
        $this->db->prepare($sql)->execute([$season_id]);
    }

    // NIVEAU 2 : Palmarès toutes saisons

    private function rebuildDriversPalmares(): void
    {
        $this->db->exec("TRUNCATE TABLE cache_drivers_palmares");

        $sql = "
            INSERT INTO cache_drivers_palmares
                (driver_id, category, nickname, titles, vice_titles, third_places, total_points, wins, podiums, total_gp)
            SELECT
                d.id,
                ds.category,
                d.nickname,
                COALESCE(SUM(CASE WHEN ds.season_status = 'desactive' AND ds.total_points = max_pts.max_pts THEN 1 ELSE 0 END), 0),
                COALESCE(SUM(CASE WHEN ds.season_status = 'desactive' AND ds.total_points = vice_pts.vice_pts THEN 1 ELSE 0 END), 0),
                COALESCE(SUM(CASE WHEN ds.season_status = 'desactive' AND ds.total_points = third_pts.third_pts THEN 1 ELSE 0 END), 0),
                COALESCE(SUM(ds.total_points), 0),
                COALESCE(SUM(ds.wins), 0),
                COALESCE(SUM(ds.podiums), 0),
                COALESCE(gp_count.total_gp, 0)
            FROM cache_drivers_standings ds
            JOIN drivers d ON d.id = ds.driver_id
            LEFT JOIN (
                SELECT season_id, category, MAX(total_points) AS max_pts
                FROM cache_drivers_standings
                GROUP BY season_id, category
            ) max_pts ON max_pts.season_id = ds.season_id AND max_pts.category = ds.category
            LEFT JOIN (
                SELECT a.season_id, a.category, MAX(a.total_points) AS vice_pts
                FROM cache_drivers_standings a
                WHERE a.total_points < (
                    SELECT MAX(b.total_points) FROM cache_drivers_standings b
                    WHERE b.season_id = a.season_id AND b.category = a.category
                )
                GROUP BY a.season_id, a.category
            ) vice_pts ON vice_pts.season_id = ds.season_id AND vice_pts.category = ds.category
            LEFT JOIN (
                SELECT season_id, category, total_points AS third_pts
                FROM (
                    SELECT season_id, category, total_points,
                           ROW_NUMBER() OVER (PARTITION BY season_id, category ORDER BY total_points DESC) AS rn
                    FROM (
                        SELECT DISTINCT season_id, category, total_points
                        FROM cache_drivers_standings
                    ) unique_pts
                ) ranked
                WHERE rn = 3
            ) third_pts ON third_pts.season_id = ds.season_id AND third_pts.category = ds.category
            LEFT JOIN (
                SELECT gp_pts.driver_id, ds_inner.category, COUNT(DISTINCT gp_pts.gp_id) AS total_gp
                FROM gp_points gp_pts
                JOIN gp g ON g.id = gp_pts.gp_id
                JOIN cache_drivers_standings ds_inner
                    ON ds_inner.driver_id = gp_pts.driver_id AND ds_inner.season_id = g.season_id
                GROUP BY gp_pts.driver_id, ds_inner.category
            ) gp_count ON gp_count.driver_id = d.id AND gp_count.category = ds.category
            GROUP BY d.id, ds.category, d.nickname, gp_count.total_gp
        ";
        $this->db->exec($sql);
    }

    private function rebuildTeamsPalmares(): void
    {
        $this->db->exec("TRUNCATE TABLE cache_teams_palmares");

        $sql = "
            INSERT INTO cache_teams_palmares
                (team_id, category, team_name, titles, total_points)
            SELECT
                t.id,
                ts.category,
                t.name,
                COALESCE(SUM(CASE WHEN s.status = 'desactive' AND ts.total_points = max_pts.max_pts THEN 1 ELSE 0 END), 0),
                COALESCE(SUM(ts.total_points), 0)
            FROM cache_teams_standings ts
            JOIN teams t ON t.id = ts.team_id
            JOIN seasons s ON s.id = ts.season_id
            LEFT JOIN (
                SELECT season_id, MAX(total_points) AS max_pts
                FROM cache_teams_standings
                GROUP BY season_id
            ) max_pts ON max_pts.season_id = ts.season_id
            GROUP BY t.id, ts.category, t.name
        ";
        $this->db->exec($sql);
    }
}