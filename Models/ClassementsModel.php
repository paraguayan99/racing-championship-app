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

    public static function getActiveSeasonId()
    {
        $db = new DbConnect();
        $sql = "SELECT id FROM seasons WHERE status = 'active' LIMIT 1";
        return $db->getConnection()->query($sql)->fetchColumn();
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

    // public static function getTeamsStandingsActive()
    // {
    //     $db = new DbConnect();
    //     $sql = "
    //         SELECT 
    //             ts.*,
    //             t.logo AS team_logo,
    //             t.color AS team_color,

    //             COUNT(DISTINCT g.id) AS gp_count
    //         FROM teams_standings ts

    //         LEFT JOIN teams t ON t.id = ts.team_id
    //         JOIN gp g ON g.season_id = ts.season_id

    //         WHERE ts.season_id IN (
    //             SELECT id FROM seasons WHERE status = 'active'
    //         )

    //         GROUP BY ts.season_id, ts.team_id
    //         ORDER BY ts.category ASC, ts.total_points DESC
    //     ";

    //     return $db->getConnection()->query($sql)->fetchAll();
    // }


    // public static function getTeamsStandingsBySeason($seasonId)
    // {
    //     $db = new DbConnect();
    //     $sql = "
    //         SELECT 
    //             ts.*,
    //             t.logo AS team_logo,
    //             t.color AS team_color,

    //             COUNT(DISTINCT g.id) AS gp_count
    //         FROM teams_standings ts

    //         LEFT JOIN teams t ON t.id = ts.team_id
    //         JOIN gp g ON g.season_id = ts.season_id

    //         WHERE ts.season_id = :season_id

    //         GROUP BY ts.season_id, ts.team_id
    //         ORDER BY ts.category ASC, ts.total_points DESC
    //     ";

    //     $stmt = $db->getConnection()->prepare($sql);
    //     $stmt->execute(['season_id' => $seasonId]);
    //     return $stmt->fetchAll();
    // }

    // // RESUME DES GP DES SAISON AVEC TOP 3 + PP + HL
    //     // --- GP pour une saison active (toutes catégories actives) ---
    // public static function getSeasonGPResultsActive()
    // {
    //     $db = new DbConnect();
    //     $sql = "
    //         SELECT g.*, s.season_number, c.name AS category,
    //                cir.name AS circuit_name,
    //                co.name AS country_name,
    //                (SELECT COUNT(*) FROM gp WHERE season_id = g.season_id) AS total_gp,
    //                (
    //                    SELECT JSON_ARRAYAGG(
    //                        JSON_OBJECT(
    //                            'position', sub.position,
    //                            'driver_id', sub.driver_id,
    //                            'nickname', sub.nickname,
    //                            'team_name', sub.team_name,
    //                            'team_logo', sub.team_logo,
    //                            'team_color', sub.team_color
    //                        )
    //                    )
    //                    FROM (
    //                        SELECT gp_points.position, gp_points.driver_id, d.nickname,
    //                               t.name AS team_name, t.logo AS team_logo, t.color AS team_color
    //                        FROM gp_points
    //                        JOIN drivers d ON d.id = gp_points.driver_id
    //                        LEFT JOIN teams t ON t.id = gp_points.team_id
    //                        WHERE gp_points.gp_id = g.id
    //                          AND gp_points.position IN (1,2,3)
    //                        ORDER BY gp_points.position ASC
    //                    ) AS sub
    //                ) AS top3,
    //                (SELECT d.nickname FROM gp_stats gs LEFT JOIN drivers d ON d.id = gs.pole_position_driver WHERE gs.gp_id = g.id) AS pole_driver,
    //                (SELECT d.nickname FROM gp_stats gs LEFT JOIN drivers d ON d.id = gs.fastest_lap_driver WHERE gs.gp_id = g.id) AS fastest_lap_driver
    //         FROM gp g
    //         JOIN seasons s ON s.id = g.season_id
    //         JOIN categories c ON c.id = s.category_id
    //         LEFT JOIN circuits cir ON cir.id = g.circuit_id
    //         LEFT JOIN countries co ON co.id = cir.country_id
    //         WHERE s.status = 'active'
    //         ORDER BY c.name ASC, g.gp_ordre ASC
    //     ";
    //     return $db->getConnection()->query($sql)->fetchAll(\PDO::FETCH_OBJ);
    // }

    // // --- GP pour une saison précise ---
    // public static function getSeasonGPResultsBySeason($seasonId)
    // {
    //     $db = new DbConnect();
    //     $sql = "
    //         SELECT g.*, s.season_number, c.name AS category,
    //                cir.name AS circuit_name,
    //                co.name AS country_name,
    //                (SELECT COUNT(*) FROM gp WHERE season_id = g.season_id) AS total_gp,
    //                (
    //                    SELECT JSON_ARRAYAGG(
    //                        JSON_OBJECT(
    //                            'position', sub.position,
    //                            'driver_id', sub.driver_id,
    //                            'nickname', sub.nickname,
    //                            'team_name', sub.team_name,
    //                            'team_logo', sub.team_logo,
    //                            'team_color', sub.team_color
    //                        )
    //                    )
    //                    FROM (
    //                        SELECT gp_points.position, gp_points.driver_id, d.nickname,
    //                               t.name AS team_name, t.logo AS team_logo, t.color AS team_color
    //                        FROM gp_points
    //                        JOIN drivers d ON d.id = gp_points.driver_id
    //                        LEFT JOIN teams t ON t.id = gp_points.team_id
    //                        WHERE gp_points.gp_id = g.id
    //                          AND gp_points.position IN (1,2,3)
    //                        ORDER BY gp_points.position ASC
    //                    ) AS sub
    //                ) AS top3,
    //                (SELECT d.nickname FROM gp_stats gs LEFT JOIN drivers d ON d.id = gs.pole_position_driver WHERE gs.gp_id = g.id) AS pole_driver,
    //                (SELECT d.nickname FROM gp_stats gs LEFT JOIN drivers d ON d.id = gs.fastest_lap_driver WHERE gs.gp_id = g.id) AS fastest_lap_driver
    //         FROM gp g
    //         JOIN seasons s ON s.id = g.season_id
    //         JOIN categories c ON c.id = s.category_id
    //         LEFT JOIN circuits cir ON cir.id = g.circuit_id
    //         LEFT JOIN countries co ON co.id = cir.country_id
    //         WHERE g.season_id = :season_id
    //         ORDER BY g.gp_ordre ASC
    //     ";
    //     $stmt = $db->getConnection()->prepare($sql);
    //     $stmt->execute(['season_id' => $seasonId]);
    //     return $stmt->fetchAll(\PDO::FETCH_OBJ);
    // }

    // // --- Détails complets d'un GP ---
    // public static function getGPDetails($gpId)
    // {
    //     $db = new DbConnect();

    //     // Infos GP + stats + circuit + pays
    //     $sql = "
    //         SELECT g.*, s.season_number, c.name AS category,
    //                cir.name AS circuit_name,
    //                co.name AS country_name,
    //                (SELECT d.nickname FROM gp_stats gs LEFT JOIN drivers d ON d.id = gs.pole_position_driver WHERE gs.gp_id = g.id) AS pole_driver,
    //                (SELECT gs.pole_position_time FROM gp_stats gs WHERE gs.gp_id = g.id) AS pole_time,
    //                (SELECT d.nickname FROM gp_stats gs LEFT JOIN drivers d ON d.id = gs.fastest_lap_driver WHERE gs.gp_id = g.id) AS fastest_lap_driver,
    //                (SELECT gs.fastest_lap_time FROM gp_stats gs WHERE gs.gp_id = g.id) AS fastest_lap_time
    //         FROM gp g
    //         JOIN seasons s ON s.id = g.season_id
    //         JOIN categories c ON c.id = s.category_id
    //         LEFT JOIN circuits cir ON cir.id = g.circuit_id
    //         LEFT JOIN countries co ON co.id = cir.country_id
    //         WHERE g.id = :gp_id
    //     ";
    //     $stmt = $db->getConnection()->prepare($sql);
    //     $stmt->execute(['gp_id' => $gpId]);
    //     $gp = $stmt->fetch(\PDO::FETCH_OBJ);

    //     if (!$gp) return null;

    //     // Classement complet GP avec logo + couleur
    //     $sqlPoints = "
    //         SELECT gp_points.position,
    //                gp_points.points_numeric,
    //                gp_points.points_text,
    //                d.nickname,
    //                t.name AS team_name,
    //                t.logo AS team_logo,
    //                t.color AS team_color
    //         FROM gp_points
    //         JOIN drivers d ON d.id = gp_points.driver_id
    //         LEFT JOIN teams t ON t.id = gp_points.team_id
    //         WHERE gp_points.gp_id = :gp_id
    //         ORDER BY gp_points.position ASC
    //     ";
    //     $stmt2 = $db->getConnection()->prepare($sqlPoints);
    //     $stmt2->execute(['gp_id' => $gpId]);
    //     $gp->points = $stmt2->fetchAll(\PDO::FETCH_OBJ);

    //     return $gp;
    // }

    // ----------------------------
// TEAMS STANDINGS
// ----------------------------

    public static function getTeamsStandingsActive()
    {
        $db = new DbConnect();
        $sql = "
            SELECT 
                ts.*,
                t.logo AS team_logo,
                t.color AS team_color,
                co.flag AS team_flag,
                COUNT(DISTINCT g.id) AS gp_count
            FROM teams_standings ts
            LEFT JOIN teams t ON t.id = ts.team_id
            LEFT JOIN countries co ON co.id = t.country_id
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
                co.flag AS team_flag,
                COUNT(DISTINCT g.id) AS gp_count
            FROM teams_standings ts
            LEFT JOIN teams t ON t.id = ts.team_id
            LEFT JOIN countries co ON co.id = t.country_id
            JOIN gp g ON g.season_id = ts.season_id
            WHERE ts.season_id = :season_id
            GROUP BY ts.season_id, ts.team_id
            ORDER BY ts.category ASC, ts.total_points DESC
        ";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute(['season_id' => $seasonId]);
        return $stmt->fetchAll();
    }

    // ----------------------------
    // GP RESULTS
    // ----------------------------

    public static function getSeasonGPResultsActive()
    {
        $db = new DbConnect();
        $sql = "
            SELECT g.*, s.season_number, c.name AS category,
                cir.name AS circuit_name,
                co.name AS country_name,
                co.flag AS country_flag,
                (SELECT COUNT(*) FROM gp WHERE season_id = g.season_id) AS total_gp,
                (
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'position', sub.position,
                            'driver_id', sub.driver_id,
                            'nickname', sub.nickname,
                            'driver_flag', sub.driver_flag,
                            'team_name', sub.team_name,
                            'team_logo', sub.team_logo,
                            'team_color', sub.team_color,
                            'team_flag', sub.team_flag
                        )
                    )
                    FROM (
                        SELECT gp_points.position, gp_points.driver_id, d.nickname, dr_country.flag AS driver_flag,
                                t.name AS team_name, t.logo AS team_logo, t.color AS team_color, t_country.flag AS team_flag
                        FROM gp_points
                        JOIN drivers d ON d.id = gp_points.driver_id
                        LEFT JOIN countries dr_country ON dr_country.id = d.country_id
                        LEFT JOIN teams t ON t.id = gp_points.team_id
                        LEFT JOIN countries t_country ON t_country.id = t.country_id
                        WHERE gp_points.gp_id = g.id
                            AND gp_points.position IN (1,2,3)
                        ORDER BY gp_points.position ASC
                    ) AS sub
                ) AS top3,
                (SELECT d.nickname FROM gp_stats gs LEFT JOIN drivers d ON d.id = gs.pole_position_driver WHERE gs.gp_id = g.id) AS pole_driver,
                (SELECT d.nickname FROM gp_stats gs LEFT JOIN drivers d ON d.id = gs.fastest_lap_driver WHERE gs.gp_id = g.id) AS fastest_lap_driver
            FROM gp g
            JOIN seasons s ON s.id = g.season_id
            JOIN categories c ON c.id = s.category_id
            LEFT JOIN circuits cir ON cir.id = g.circuit_id
            LEFT JOIN countries co ON co.id = cir.country_id
            WHERE s.status = 'active'
            ORDER BY c.name ASC, g.gp_ordre ASC
        ";
        return $db->getConnection()->query($sql)->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function getSeasonGPResultsBySeason($seasonId)
    {
        $db = new DbConnect();
        $sql = "
            SELECT g.*, s.season_number, c.name AS category,
                cir.name AS circuit_name,
                co.name AS country_name,
                co.flag AS country_flag,
                (SELECT COUNT(*) FROM gp WHERE season_id = g.season_id) AS total_gp,
                (
                    SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'position', sub.position,
                            'driver_id', sub.driver_id,
                            'nickname', sub.nickname,
                            'driver_flag', sub.driver_flag,
                            'team_name', sub.team_name,
                            'team_logo', sub.team_logo,
                            'team_color', sub.team_color,
                            'team_flag', sub.team_flag
                        )
                    )
                    FROM (
                        SELECT gp_points.position, gp_points.driver_id, d.nickname, dr_country.flag AS driver_flag,
                                t.name AS team_name, t.logo AS team_logo, t.color AS team_color, t_country.flag AS team_flag
                        FROM gp_points
                        JOIN drivers d ON d.id = gp_points.driver_id
                        LEFT JOIN countries dr_country ON dr_country.id = d.country_id
                        LEFT JOIN teams t ON t.id = gp_points.team_id
                        LEFT JOIN countries t_country ON t_country.id = t.country_id
                        WHERE gp_points.gp_id = g.id
                            AND gp_points.position IN (1,2,3)
                        ORDER BY gp_points.position ASC
                    ) AS sub
                ) AS top3,
                (SELECT d.nickname FROM gp_stats gs LEFT JOIN drivers d ON d.id = gs.pole_position_driver WHERE gs.gp_id = g.id) AS pole_driver,
                (SELECT d.nickname FROM gp_stats gs LEFT JOIN drivers d ON d.id = gs.fastest_lap_driver WHERE gs.gp_id = g.id) AS fastest_lap_driver
            FROM gp g
            JOIN seasons s ON s.id = g.season_id
            JOIN categories c ON c.id = s.category_id
            LEFT JOIN circuits cir ON cir.id = g.circuit_id
            LEFT JOIN countries co ON co.id = cir.country_id
            WHERE g.season_id = :season_id
            ORDER BY g.gp_ordre ASC
        ";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute(['season_id' => $seasonId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // ----------------------------
    // GP DETAILS
    // ----------------------------

    public static function getGPDetails($gpId)
    {
        $db = new DbConnect();

        // Infos GP + stats + circuit + pays
        $sql = "
            SELECT g.*, s.season_number, c.name AS category,
                cir.name AS circuit_name,
                co.name AS country_name,
                co.flag AS country_flag,
                (SELECT d.nickname FROM gp_stats gs LEFT JOIN drivers d ON d.id = gs.pole_position_driver WHERE gs.gp_id = g.id) AS pole_driver,
                (SELECT gs.pole_position_time FROM gp_stats gs WHERE gs.gp_id = g.id) AS pole_time,
                (SELECT d.nickname FROM gp_stats gs LEFT JOIN drivers d ON d.id = gs.fastest_lap_driver WHERE gs.gp_id = g.id) AS fastest_lap_driver,
                (SELECT gs.fastest_lap_time FROM gp_stats gs WHERE gs.gp_id = g.id) AS fastest_lap_time
            FROM gp g
            JOIN seasons s ON s.id = g.season_id
            JOIN categories c ON c.id = s.category_id
            LEFT JOIN circuits cir ON cir.id = g.circuit_id
            LEFT JOIN countries co ON co.id = cir.country_id
            WHERE g.id = :gp_id
        ";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute(['gp_id' => $gpId]);
        $gp = $stmt->fetch(\PDO::FETCH_OBJ);

        if (!$gp) return null;

        // Classement complet GP avec logo + couleur + flags
        $sqlPoints = "
            SELECT gp_points.position,
                gp_points.points_numeric,
                gp_points.points_text,
                d.nickname,
                dr_country.flag AS driver_flag,
                t.name AS team_name,
                t.logo AS team_logo,
                t.color AS team_color,
                t_country.flag AS team_flag
            FROM gp_points
            JOIN drivers d ON d.id = gp_points.driver_id
            LEFT JOIN countries dr_country ON dr_country.id = d.country_id
            LEFT JOIN teams t ON t.id = gp_points.team_id
            LEFT JOIN countries t_country ON t_country.id = t.country_id
            WHERE gp_points.gp_id = :gp_id
            ORDER BY gp_points.position ASC
        ";
        $stmt2 = $db->getConnection()->prepare($sqlPoints);
        $stmt2->execute(['gp_id' => $gpId]);
        $gp->points = $stmt2->fetchAll(\PDO::FETCH_OBJ);

        return $gp;
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

