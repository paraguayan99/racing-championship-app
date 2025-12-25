<?php
namespace App\Models;

use App\Core\DbConnect;

class StatsCircuitsModel extends DbConnect
{
    /* ---------------------------------------------------------
       SELECT – LISTE DES CIRCUITS
    --------------------------------------------------------- */

    public static function getAllCircuitsForSelect()
    {
        $db = new DbConnect();
        $sql = "
            SELECT c.id, c.name, co.name AS country, co.flag AS country_flag
            FROM circuits c
            LEFT JOIN countries co ON co.id = c.country_id
            ORDER BY c.name ASC
        ";
        return $db->getConnection()->query($sql)->fetchAll(\PDO::FETCH_OBJ);
    }

    /* ---------------------------------------------------------
       TOP 10 CHRONOS (POLE POSITION + FASTEST LAP)
    --------------------------------------------------------- */
    public static function getCircuitTopChronos($circuitId)
    {
        $db = new DbConnect();
        $sql = "
        SELECT 
            g.id AS gp_id,
            s.season_number,
            cat.name AS category,
            s.videogame,
            s.platform,
            d.nickname,
            gs.pole_position_time AS chrono,
            'Pole Position' AS chrono_type
        FROM gp_stats gs
        JOIN gp g ON g.id = gs.gp_id
        JOIN seasons s ON s.id = g.season_id
        JOIN categories cat ON cat.id = s.category_id
        JOIN drivers d ON d.id = gs.pole_position_driver
        WHERE g.circuit_id = :circuit_id1
        AND gs.pole_position_time IS NOT NULL

        UNION ALL

        SELECT 
            g.id,
            s.season_number,
            cat.name,
            s.videogame,
            s.platform,
            d.nickname,
            gs.fastest_lap_time,
            'Fastest Lap'
        FROM gp_stats gs
        JOIN gp g ON g.id = gs.gp_id
        JOIN seasons s ON s.id = g.season_id
        JOIN categories cat ON cat.id = s.category_id
        JOIN drivers d ON d.id = gs.fastest_lap_driver
        WHERE g.circuit_id = :circuit_id2
        AND gs.fastest_lap_time IS NOT NULL

        ORDER BY chrono ASC
        LIMIT 10
    ";

    $stmt = $db->getConnection()->prepare($sql);
    $stmt->execute([
        'circuit_id1' => $circuitId,
        'circuit_id2' => $circuitId
    ]);
    $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
    return $result ?: [];
    }

    /* ---------------------------------------------------------
       CLASSEMENT DRIVERS SUR LE CIRCUIT
    --------------------------------------------------------- */
    public static function getDriversStatsByCircuit($circuitId)
    {
        $db = new DbConnect();
        $sql = "
            SELECT 
                d.id,
                d.nickname,

                COUNT(DISTINCT gp.id) AS gp_count,

                COUNT(CASE WHEN gp_pts.position = 1 THEN 1 END) AS wins,
                COUNT(CASE WHEN gp_pts.position IN (1,2,3) THEN 1 END) AS podiums,
                COUNT(CASE WHEN gs.pole_position_driver = d.id THEN 1 END) AS poles,
                COUNT(CASE WHEN gs.fastest_lap_driver = d.id THEN 1 END) AS fastest_laps

            FROM drivers d

            JOIN gp_points gp_pts ON gp_pts.driver_id = d.id
            JOIN gp ON gp.id = gp_pts.gp_id
            LEFT JOIN gp_stats gs ON gs.gp_id = gp.id

            WHERE gp.circuit_id = :circuit_id

            GROUP BY d.id
            ORDER BY wins DESC, gp_count DESC
        ";

        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute(['circuit_id' => $circuitId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /* ---------------------------------------------------------
       NOMBRE DE GP PAR CATÉGORIE
    --------------------------------------------------------- */
    public static function getGPCountByCategory($circuitId)
    {
        $db = new DbConnect();
        $sql = "
            SELECT 
                cat.name AS category,
                COUNT(g.id) AS gp_count
            FROM gp g
            JOIN seasons s ON s.id = g.season_id
            JOIN categories cat ON cat.id = s.category_id
            WHERE g.circuit_id = :circuit_id
            GROUP BY cat.id
            ORDER BY cat.name ASC
        ";

        $stmt = $db->getConnection()->prepare($sql);
        $stmt->execute(['circuit_id' => $circuitId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
