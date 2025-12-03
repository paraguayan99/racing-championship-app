<?php
namespace App\Models;

use App\Core\DbConnect;

class TeamsDriversModel extends DbConnect {

    public $id;
    public $season_id;
    public $driver_id;
    public $team_id;

    public static function all()
    {
        $db = new DbConnect();
        return $db->getConnection()->query("
            SELECT 
                td.id,
                td.season_id,
                td.driver_id,
                td.team_id,

                -- Pilote
                d.nickname AS driver,

                -- Team
                t.name AS team,

                -- Saison
                s.season_number,
                s.videogame,
                s.platform,
                s.status AS season_status,

                -- Catégorie (nom)
                c.name AS category_name

            FROM teams_drivers td
            JOIN drivers d ON td.driver_id = d.id
            JOIN teams t ON td.team_id = t.id
            JOIN seasons s ON td.season_id = s.id
            JOIN categories c ON s.category_id = c.id

            ORDER BY s.season_number DESC, d.nickname ASC
        ")->fetchAll();
    }

    // Obtenir une ligne précise
    public static function find($id) {
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("
            SELECT * FROM teams_drivers WHERE id=?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>
