<?php
namespace App\Models;

use App\Core\DbConnect;

class ManualAdjustmentsModel extends DbConnect {

    public $id;
    public $season_id;
    public $driver_id;
    public $team_id;
    public $points;
    public $comment;

    public static function all() {
    $db = new DbConnect();
    $stmt = $db->getConnection()->prepare("
        SELECT 
            ma.id,
            ma.season_id,
            ma.driver_id,
            ma.team_id,
            ma.points,
            ma.comment,
            d.nickname AS driver_nickname,
            t.name AS team_name,
            s.season_number,
            c.name AS category_name
        FROM manual_adjustments ma
        JOIN seasons s ON ma.season_id = s.id
        JOIN categories c ON s.category_id = c.id
        LEFT JOIN drivers d ON ma.driver_id = d.id
        LEFT JOIN teams t ON ma.team_id = t.id
        ORDER BY ma.id DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
}

    public static function allWithSeasonActive()
    {
        $db = new DbConnect();
        $pdo = $db->getConnection();

        $sql = "
            SELECT 
                ma.*,

                -- Saison
                s.season_number,
                s.status AS season_status,
                c.name AS category_name,

                -- Pilote
                d.nickname AS driver_nickname,

                -- Équipe
                t.name AS team_name

            FROM manual_adjustments ma
            JOIN seasons s ON ma.season_id = s.id
            JOIN categories c ON s.category_id = c.id
            LEFT JOIN drivers d ON ma.driver_id = d.id
            LEFT JOIN teams t ON ma.team_id = t.id
            
            WHERE s.status = 'active'
            ORDER BY c.name ASC, s.season_number ASC, ma.id ASC
        ";

        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
}

}
?>
