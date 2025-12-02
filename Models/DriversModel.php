<?php
namespace App\Models;

use App\Core\DbConnect;

class DriversModel extends DbConnect {

    public $id;
    public $nickname;
    public $country_id;
    public $status;

    public static function findByNickname($nickname){
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT * FROM drivers WHERE nickname = ?");
        $stmt->execute([$nickname]);
        return $stmt->fetch();
    }

    public static function getCountryName($country_id){
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT name FROM countries WHERE id=?");
        $stmt->execute([$country_id]);
        $row = $stmt->fetch();
        return $row ? $row->name : null;
    }

    public static function all(){
        $db = new DbConnect();
        return $db->getConnection()->query("
            SELECT drivers.id, drivers.nickname, drivers.status, countries.name as country
            FROM drivers
            JOIN countries ON drivers.country_id = countries.id
        ")->fetchAll();
    }
}
?>
