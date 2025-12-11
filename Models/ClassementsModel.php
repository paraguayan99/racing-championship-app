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
                s.season_number,
                s.status,
                c.name AS category,

                d.nickname,
                country.flag AS driver_flag,

                t.name AS team_name,
                t.logo AS team_logo,
                t.color AS team_color

            FROM drivers_standings ds
            JOIN seasons s ON ds.season_id = s.id
            JOIN categories c ON c.id = s.category_id

            JOIN drivers d ON d.id = ds.driver_id
            LEFT JOIN countries country ON country.id = d.country_id

            LEFT JOIN teams_drivers td 
                ON td.driver_id = ds.driver_id 
                AND td.season_id = s.id

            LEFT JOIN teams t ON t.id = td.team_id

            WHERE s.status = 'active'
            ORDER BY ds.total_points DESC
        ";
        return $db->getConnection()->query($sql)->fetchAll();
    }

    public static function getDriversStandingsBySeason($seasonId)
    {
        $db = new DbConnect();
        $sql = "
            SELECT 
                ds.*,
                s.season_number,
                s.status,
                c.name AS category,

                d.nickname,
                country.flag AS driver_flag,

                t.name AS team_name,
                t.logo AS team_logo,
                t.color AS team_color

            FROM drivers_standings ds
            JOIN seasons s ON ds.season_id = s.id
            JOIN categories c ON c.id = s.category_id

            JOIN drivers d ON d.id = ds.driver_id
            LEFT JOIN countries country ON country.id = d.country_id

            LEFT JOIN teams_drivers td 
                ON td.driver_id = ds.driver_id 
                AND td.season_id = s.id

            LEFT JOIN teams t ON t.id = td.team_id

            WHERE s.id = :season_id 
                AND s.status = 'desactive'
            ORDER BY ds.total_points DESC
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

