<?php
namespace App\Models;

use App\Core\DbConnect;

class GpPointsModel extends DbConnect {

    public $id;
    public $gp_id;
    public $driver_id;
    public $team_id;
    public $position;
    public $points_numeric;
    public $points_text;

    // Récupérer tous les GP Points avec infos Saison, GP, Country, Driver, Team
    public static function allWithSeasonActive()
    {
        $db = new DbConnect();
        $pdo = $db->getConnection();

        $sql = "
            SELECT 
                gp_points.id,
                gp_points.gp_id,
                gp_points.driver_id,
                gp_points.team_id,
                gp_points.position,
                gp_points.points_numeric,
                gp_points.points_text,

                gp.gp_ordre,
                gp.season_id,

                s.season_number,
                s.status AS season_status,

                c.name AS category_name,

                d.nickname AS driver_nickname,
                t.name AS team_name,

                ci.name AS circuit_name,
                co.name AS country_name

            FROM gp_points

            INNER JOIN gp 
                ON gp_points.gp_id = gp.id

            INNER JOIN seasons s 
                ON gp.season_id = s.id

            INNER JOIN categories c 
                ON s.category_id = c.id

            INNER JOIN circuits ci
                ON gp.circuit_id = ci.id

            INNER JOIN countries co
                ON ci.country_id = co.id

            LEFT JOIN drivers d
                ON gp_points.driver_id = d.id

            LEFT JOIN teams t
                ON gp_points.team_id = t.id

            WHERE s.status = 'active'

            ORDER BY 
                c.name ASC,
                s.season_number ASC,

                /* Pour que le dernier GP POINTS ajoutés soient en haut de la VUE INDEX */
                gp.gp_ordre DESC,
                
                /* DNF, DNS, DSQ = NULL en dernier au classement */
                (gp_points.position IS NULL) ASC,
                gp_points.position ASC,

                gp_points.id ASC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Récupérer tous les GP Points pour un GP spécifique
    public static function allByGp($gp_id)
    {
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT * FROM gp_points WHERE gp_id = ?");
        $stmt->execute([$gp_id]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Récupérer un point par ID
    public static function findById($id)
    {
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT * FROM gp_points WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
?>
