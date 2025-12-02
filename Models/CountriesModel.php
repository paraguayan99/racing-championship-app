<?php
namespace App\Models;

use App\Core\DbConnect;

class CountriesModel extends DbConnect {

    public $id;
    public $name;
    public $code;
    public $flag;

    // Récupérer un pays par son nom
    public static function findByName($name){
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT * FROM countries WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    // Récupérer tous les pays
    public static function all(){
        $db = new DbConnect();
        return $db->getConnection()->query("
            SELECT id, name, code, flag
            FROM countries
            ORDER BY name ASC
        ")->fetchAll();
    }
}
?>
