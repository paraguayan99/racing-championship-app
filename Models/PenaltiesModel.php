<?php
namespace App\Models;

use App\Core\DbConnect;

class PenaltiesModel extends DbConnect {

    public $id;
    public $gp_id;
    public $driver_id;
    public $team_id;
    public $points_removed;
    public $comment;

    // Récupérer toutes les pénalités avec infos Saison, GP, Country, Driver, Team
    public static function allWithSeasonActive()
    {
        $db = new DbConnect();
        $pdo = $db->getConnection();

        $sql = "
            SELECT 
                penalties.id,
                penalties.gp_id,
                penalties.driver_id,
                penalties.team_id,
                penalties.points_removed,
                penalties.comment,

                gp.gp_ordre,
                gp.season_id,

                s.season_number,
                s.status AS season_status,

                c.name AS category_name,

                d.nickname AS driver_nickname,
                t.name AS team_name,

                ci.name AS circuit_name,
                co.name AS country_name

            FROM penalties

            INNER JOIN gp 
                ON penalties.gp_id = gp.id

            INNER JOIN seasons s 
                ON gp.season_id = s.id

            INNER JOIN categories c 
                ON s.category_id = c.id

            INNER JOIN circuits ci
                ON gp.circuit_id = ci.id

            INNER JOIN countries co
                ON ci.country_id = co.id

            LEFT JOIN drivers d
                ON penalties.driver_id = d.id

            LEFT JOIN teams t
                ON penalties.team_id = t.id

            WHERE s.status = 'active'

            ORDER BY 
                c.name ASC,
                s.season_number ASC,
                gp.gp_ordre DESC,
                penalties.id ASC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Récupérer une pénalité par ID
    public static function findById($id)
    {
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT * FROM penalties WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
?>
