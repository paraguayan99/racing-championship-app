<?php
namespace App\Models;

use App\Core\DbConnect;

class CircuitsModel extends DbConnect {

    public $id;
    public $name;
    public $country_id;
    public $status;

    // Récupérer tous les circuits
    public static function all(){
        $db = new DbConnect();
        return $db->getConnection()->query("
            SELECT circuits.id, circuits.name, circuits.status, countries.name as country
            FROM circuits
            JOIN countries ON circuits.country_id = countries.id
        ")->fetchAll();
    }

    // Récupérer tous les pays
    public static function allCountries(){
        $db = new DbConnect();
        return $db->getConnection()->query("
            SELECT id, name
            FROM countries
            ORDER BY name ASC
        ")->fetchAll();
    }
}
?>
