<?php
namespace App\Models;

use App\Core\DbConnect;

class ClassementsModel extends DbConnect
{
    // ----- PILOTES -----

    // Récupère toutes les saisons actives et inactives pour le select de drivers_standings.php
    public static function getAllSeasonsForSelect()
    {
        $db = new DbConnect();
        $sql = "
            SELECT s.id AS season_id, s.season_number, c.name AS category, s.videogame, s.platform, s.status
            FROM seasons s
            JOIN categories c ON c.id = s.category_id
            ORDER BY s.status DESC, c.name ASC, s.season_number DESC
        ";
        return $db->getConnection()->query($sql)->fetchAll();
    }

    public static function getDriversStandingsActive()
    {
        $db = new DbConnect();
        $sql = "
            SELECT 
                ds.*,

                -- Flag du pilote
                country.flag AS driver_flag,

                -- Infos manquantes sur l'équipe
                t.logo AS team_logo,
                t.color AS team_color,

                -- Statistiques ajoutées
                COUNT(DISTINCT gpp.gp_id) AS gp_count,
                COUNT(CASE WHEN gs.pole_position_driver = ds.driver_id THEN 1 END) AS pole_count,
                COUNT(CASE WHEN gs.fastest_lap_driver = ds.driver_id THEN 1 END) AS fastestlap_count

            FROM drivers_standings ds

            -- Récupérer le flag du pilote
            LEFT JOIN drivers d ON d.id = ds.driver_id
            LEFT JOIN countries country ON country.id = d.country_id

            -- Équipe de la saison (pour récupérer logo + couleur)
            LEFT JOIN teams_drivers td 
                ON td.driver_id = ds.driver_id 
                AND td.season_id = ds.season_id
            LEFT JOIN teams t 
                ON t.id = td.team_id

            -- GP & stats
            JOIN gp g ON g.season_id = ds.season_id

            LEFT JOIN gp_points gpp
                ON gpp.gp_id = g.id
                AND gpp.driver_id = ds.driver_id

            LEFT JOIN gp_stats gs
                ON gs.gp_id = g.id

            WHERE ds.season_status = 'active'

            GROUP BY ds.season_id, ds.driver_id
            ORDER BY ds.category ASC, ds.total_points DESC
        ";
        return $db->getConnection()->query($sql)->fetchAll();
    }

    public static function getDriversStandingsBySeason($seasonId)
    {
        $db = new DbConnect();
        $sql = "
            SELECT 
                ds.*,

                country.flag AS driver_flag,
                t.logo AS team_logo,
                t.color AS team_color,

                COUNT(DISTINCT gpp.gp_id) AS gp_count,
                COUNT(CASE WHEN gs.pole_position_driver = ds.driver_id THEN 1 END) AS pole_count,
                COUNT(CASE WHEN gs.fastest_lap_driver = ds.driver_id THEN 1 END) AS fastestlap_count

            FROM drivers_standings ds

            LEFT JOIN drivers d ON d.id = ds.driver_id
            LEFT JOIN countries country ON country.id = d.country_id

            LEFT JOIN teams_drivers td 
                ON td.driver_id = ds.driver_id 
                AND td.season_id = ds.season_id

            LEFT JOIN teams t 
                ON t.id = td.team_id

            JOIN gp g ON g.season_id = ds.season_id

            LEFT JOIN gp_points gpp
                ON gpp.gp_id = g.id
                AND gpp.driver_id = ds.driver_id

            LEFT JOIN gp_stats gs
                ON gs.gp_id = g.id

            WHERE ds.season_id = :season_id
            AND ds.season_status = 'desactive'

            GROUP BY ds.season_id, ds.driver_id
            ORDER BY ds.category ASC, ds.total_points DESC
        ";

        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute(['season_id' => $seasonId]);
        return $stmt->fetchAll();
    }

    // POUR LES CLASSEMENTS TEAMS

    // ----- TEAMS -----

    public static function getTeamsStandingsActive()
    {
        $db = new DbConnect();
        $sql = "
            SELECT 
                ts.*,
                t.logo AS team_logo,
                t.color AS team_color,

                COUNT(DISTINCT g.id) AS gp_count
            FROM teams_standings ts

            LEFT JOIN teams t ON t.id = ts.team_id
            JOIN gp g ON g.season_id = ts.season_id

            WHERE ts.season_id IN (
                SELECT id FROM seasons WHERE status = 'active'
            )

            GROUP BY ts.season_id, ts.team_id
            ORDER BY ts.category ASC, ts.total_points DESC
        ";

        return $db->getConnection()->query($sql)->fetchAll();
    }


    public static function getTeamsStandingsBySeason($seasonId)
    {
        $db = new DbConnect();
        $sql = "
            SELECT 
                ts.*,
                t.logo AS team_logo,
                t.color AS team_color,

                COUNT(DISTINCT g.id) AS gp_count
            FROM teams_standings ts

            LEFT JOIN teams t ON t.id = ts.team_id
            JOIN gp g ON g.season_id = ts.season_id

            WHERE ts.season_id = :season_id

            GROUP BY ts.season_id, ts.team_id
            ORDER BY ts.category ASC, ts.total_points DESC
        ";

        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute(['season_id' => $seasonId]);
        return $stmt->fetchAll();
    }

    // ----- TITRES PILOTES -----
    public static function getDriverAwards()
    {
        $db = new DbConnect();
        $sql = "
            SELECT *
            FROM driver_awards
            ORDER BY titles DESC, vice_titles DESC, third_place DESC
        ";
        return $db->getConnection()->query($sql)->fetchAll();
    }

    // ----- STATS PILOTES TOUTES SAISONS -----
    public static function getDriverStatsAllSeasons()
    {
        $db = new DbConnect();
        $sql = "
            SELECT *
            FROM driver_stats_all_seasons
            ORDER BY total_points DESC
        ";
        return $db->getConnection()->query($sql)->fetchAll();
    }

    // ----- GP STATS -----
    public static function getGpStatsSummary()
    {
        $db = new DbConnect();
        $sql = "
            SELECT *
            FROM gp_stats_summary
            ORDER BY season_number DESC, gp_id ASC
        ";
        return $db->getConnection()->query($sql)->fetchAll();
    }

    // ----- TEAMS -----
    public static function getTeamsStandings()
    {
        $db = new DbConnect();
        $sql = "
            SELECT *
            FROM teams_standings
            ORDER BY season_number DESC, total_points DESC
        ";
        return $db->getConnection()->query($sql)->fetchAll();
    }

    public static function getTeamAwards()
    {
        $db = new DbConnect();
        $sql = "
            SELECT *
            FROM team_awards
            ORDER BY titles DESC, vice_titles DESC, third_place DESC
        ";
        return $db->getConnection()->query($sql)->fetchAll();
    }

    public static function getTeamPointsAllSeasons()
    {
        $db = new DbConnect();
        $sql = "
            SELECT *
            FROM team_points_all_seasons
            ORDER BY total_points DESC
        ";
        return $db->getConnection()->query($sql)->fetchAll();
    }
}
?>

