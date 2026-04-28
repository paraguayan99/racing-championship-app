<?php
namespace App\Models;

use App\Core\DbConnect;

class DriversModel extends DbConnect {

    public $id;
    public $nickname;
    public $country_id;
    public $status;

    // Récupère un pilote par son ID
    public static function find($id){
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT * FROM drivers WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Récupère un pilote par son pseudo
    public static function findByNickname($nickname){
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT * FROM drivers WHERE nickname = ?");
        $stmt->execute([$nickname]);
        return $stmt->fetch();
    }

    // Récupère nom pays par son id
    public static function getCountryName($country_id){
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT name FROM countries WHERE id=?");
        $stmt->execute([$country_id]);
        $row = $stmt->fetch();
        return $row ? $row->name : null;
    }

    // Récupère tous les pilotes
    public static function all(){
        $db = new DbConnect();
        return $db->getConnection()->query("
            SELECT drivers.id, drivers.nickname, drivers.status, countries.name AS country
            FROM drivers
            JOIN countries ON drivers.country_id = countries.id
            ORDER BY drivers.status ASC, drivers.nickname ASC
        ")->fetchAll();
    }

    // Pour afficher uniquement les pilotes actifs dans les formulaires
    public static function getActive()
    {
        $db = new DbConnect();
        $sql = "SELECT * FROM drivers WHERE status = 'active' ORDER BY nickname ASC";
        return $db->getConnection()->query($sql)->fetchAll();
    }

}
?>
