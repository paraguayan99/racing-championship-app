<?php
namespace App\Models;

use App\Core\DbConnect;

class StatsDriversModel
{
    // Retourne les infos de base d'un pilote (nickname, pays, flag)
    public static function getDriverById($driverId)
    {
        $db = new DbConnect();
        $sql = "
            SELECT d.*, c.name AS country_name, c.flag AS country_flag
            FROM drivers d
            LEFT JOIN countries c ON c.id = d.country_id
            WHERE d.id = :driver_id
            LIMIT 1
        ";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute(['driver_id' => $driverId]);
        return $stmt->fetch();
    }

    // Retourne l'historique complet d'un pilote, saison par saison,
    // avec toutes les statistiques nécessaires à la vue statsdrivers
    public static function getDriverHistory($driverId)
    {
        $db = new DbConnect();
        $sql = "
            SELECT
                ds.season_id,
                ds.season_number,
                ds.season_status,
                ds.category,
                ds.driver_id,
                ds.nickname,
                ds.total_points,
                ds.wins,
                ds.podiums,

                -- Infos saison
                s.videogame,
                s.videogame_short,
                s.platform,
                s.season_name,
                s.type AS season_type,

                -- Catégorie
                cat.color AS category_color,
                cat.type AS category_type,

                -- Équipe
                t.name  AS team_name,
                t.logo  AS team_logo,
                t.color AS team_color,

                -- Statistiques GP calculées ici
                COUNT(DISTINCT gpp.gp_id)                                           AS gp_count,
                COUNT(CASE WHEN gs.pole_position_driver = ds.driver_id THEN 1 END)  AS pole_count,
                COUNT(CASE WHEN gs.fastest_lap_driver   = ds.driver_id THEN 1 END)  AS fastestlap_count

            FROM drivers_standings ds

            JOIN seasons    s   ON s.id   = ds.season_id
            JOIN categories cat ON cat.id = s.category_id

            LEFT JOIN teams_drivers td
                ON  td.driver_id = ds.driver_id
                AND td.season_id = ds.season_id
            LEFT JOIN teams t ON t.id = td.team_id

            JOIN gp g ON g.season_id = ds.season_id

            LEFT JOIN gp_points gpp
                ON  gpp.gp_id     = g.id
                AND gpp.driver_id = ds.driver_id

            LEFT JOIN gp_stats gs ON gs.gp_id = g.id

            WHERE ds.driver_id = :driver_id

            GROUP BY
                ds.season_id,
                ds.driver_id,
                ds.season_number,
                ds.season_status,
                ds.category,
                ds.nickname,
                ds.total_points,
                ds.wins,
                ds.podiums,
                s.videogame,
                s.videogame_short,
                s.platform,
                s.season_name,
                s.type,
                cat.type,
                cat.color,
                t.name,
                t.logo,
                t.color

            ORDER BY cat.name ASC, s.season_number ASC
        ";

        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute(['driver_id' => $driverId]);
        return $stmt->fetchAll();
    }

    // Retourne le rang du pilote dans chaque saison demandée,
    // avec le même départage des égalités que ClassementsModel
    public static function getDriverRanksBySeason($driverId, array $seasonIds)
    {
        if (empty($seasonIds)) return [];

        $db = new DbConnect();

        // Génère les placeholders PDO : :sid0, :sid1, …
        $placeholders = [];
        $params = [];
        foreach ($seasonIds as $i => $sid) {
            $placeholders[] = ":sid{$i}";
            $params["sid{$i}"] = $sid;
        }
        $inClause = implode(', ', $placeholders);

        $sql = "
            SELECT
                sub.season_id,
                sub.driver_id,
                RANK() OVER (
                    PARTITION BY sub.season_id
                    ORDER BY
                        sub.total_points DESC,
                        sub.pos_1  DESC,
                        sub.pos_2  DESC,
                        sub.pos_3  DESC,
                        sub.pos_4  DESC,
                        sub.pos_5  DESC,
                        sub.pos_6  DESC,
                        sub.pos_7  DESC,
                        sub.pos_8  DESC,
                        sub.pos_9  DESC,
                        sub.pos_10 DESC,
                        sub.pos_11 DESC,
                        sub.pos_12 DESC,
                        sub.pos_13 DESC,
                        sub.pos_14 DESC,
                        sub.pos_15 DESC,
                        sub.pos_16 DESC,
                        sub.pos_17 DESC,
                        sub.pos_18 DESC,
                        sub.pos_19 DESC,
                        sub.pos_20 DESC,
                        sub.pos_21 DESC,
                        sub.pos_22 DESC,
                        sub.gp_count DESC,
                        sub.nickname ASC
                ) AS driver_rank
            FROM (
                SELECT
                    ds.season_id,
                    ds.driver_id,
                    ds.nickname,
                    ds.total_points,
                    COUNT(DISTINCT gpp.gp_id) AS gp_count,
                    COUNT(CASE WHEN gpp.position = 1  THEN 1 END) AS pos_1,
                    COUNT(CASE WHEN gpp.position = 2  THEN 1 END) AS pos_2,
                    COUNT(CASE WHEN gpp.position = 3  THEN 1 END) AS pos_3,
                    COUNT(CASE WHEN gpp.position = 4  THEN 1 END) AS pos_4,
                    COUNT(CASE WHEN gpp.position = 5  THEN 1 END) AS pos_5,
                    COUNT(CASE WHEN gpp.position = 6  THEN 1 END) AS pos_6,
                    COUNT(CASE WHEN gpp.position = 7  THEN 1 END) AS pos_7,
                    COUNT(CASE WHEN gpp.position = 8  THEN 1 END) AS pos_8,
                    COUNT(CASE WHEN gpp.position = 9  THEN 1 END) AS pos_9,
                    COUNT(CASE WHEN gpp.position = 10 THEN 1 END) AS pos_10,
                    COUNT(CASE WHEN gpp.position = 11 THEN 1 END) AS pos_11,
                    COUNT(CASE WHEN gpp.position = 12 THEN 1 END) AS pos_12,
                    COUNT(CASE WHEN gpp.position = 13 THEN 1 END) AS pos_13,
                    COUNT(CASE WHEN gpp.position = 14 THEN 1 END) AS pos_14,
                    COUNT(CASE WHEN gpp.position = 15 THEN 1 END) AS pos_15,
                    COUNT(CASE WHEN gpp.position = 16 THEN 1 END) AS pos_16,
                    COUNT(CASE WHEN gpp.position = 17 THEN 1 END) AS pos_17,
                    COUNT(CASE WHEN gpp.position = 18 THEN 1 END) AS pos_18,
                    COUNT(CASE WHEN gpp.position = 19 THEN 1 END) AS pos_19,
                    COUNT(CASE WHEN gpp.position = 20 THEN 1 END) AS pos_20,
                    COUNT(CASE WHEN gpp.position = 21 THEN 1 END) AS pos_21,
                    COUNT(CASE WHEN gpp.position = 22 THEN 1 END) AS pos_22
                FROM drivers_standings ds
                JOIN gp g ON g.season_id = ds.season_id
                LEFT JOIN gp_points gpp
                    ON  gpp.gp_id     = g.id
                    AND gpp.driver_id = ds.driver_id
                WHERE ds.season_id IN ($inClause)
                GROUP BY ds.season_id, ds.driver_id, ds.nickname, ds.total_points
            ) sub
        ";

        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        $ranks = [];
        foreach ($rows as $row) {
            if ((int)$row->driver_id === (int)$driverId) {
                $ranks[(int)$row->season_id] = (int)$row->driver_rank;
            }
        }
        return $ranks;
    }
}
