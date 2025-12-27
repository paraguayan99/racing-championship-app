<?php
namespace App\Models;

use App\Core\DbConnect;

class GpStatsModel extends DbConnect {

    public $gp_id;
    public $pole_position_driver;
    public $pole_position_time;
    public $fastest_lap_driver;
    public $fastest_lap_time;

    // Récupérer toutes les stats avec infos Saison, GP, Country, Drivers
    public static function allWithSeasonActive()
    {
        $db = new DbConnect();
        $pdo = $db->getConnection();

        $sql = "
            SELECT 
                gp_stats.gp_id,
                gp_stats.pole_position_driver,
                gp_stats.pole_position_time,
                gp_stats.fastest_lap_driver,
                gp_stats.fastest_lap_time,

                gp.gp_ordre,
                gp.season_id,

                s.season_number,
                s.status AS season_status,

                c.name AS category_name,

                d1.nickname AS pole_driver_name,
                d2.nickname AS fl_driver_name,

                ci.name AS circuit_name,
                co.name AS country_name,
                co.code AS country_code

            FROM gp_stats

            INNER JOIN gp 
                ON gp_stats.gp_id = gp.id

            INNER JOIN seasons s 
                ON gp.season_id = s.id

            INNER JOIN categories c 
                ON s.category_id = c.id

            INNER JOIN circuits ci
                ON gp.circuit_id = ci.id

            INNER JOIN countries co
                ON ci.country_id = co.id

            LEFT JOIN drivers d1
                ON gp_stats.pole_position_driver = d1.id

            LEFT JOIN drivers d2
                ON gp_stats.fastest_lap_driver = d2.id

            WHERE s.status = 'active'

            ORDER BY 
                c.name ASC,
                s.season_number ASC,
                gp.gp_ordre DESC,
                gp_stats.gp_id ASC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Récupérer une stat par GP ID (clé primaire)
    public static function findByGpId($gp_id)
    {
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT * FROM gp_stats WHERE gp_id = ?");
        $stmt->execute([$gp_id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
?>
