<?php
namespace App\Models;

use App\Core\DbConnect;

class TeamsModel extends DbConnect {

    public $id;
    public $name;
    public $logo;
    public $color;
    public $country_id;
    public $status;

    // Récupérer une équipe par son nom
    public static function findByName($name){
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT * FROM teams WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    // Récupérer le nom du pays pour affichage
    public static function getCountryName($country_id){
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT name FROM countries WHERE id=?");
        $stmt->execute([$country_id]);
        $row = $stmt->fetch();
        return $row ? $row->name : null;
    }

    // Récupérer toutes les équipes
    public static function all(){
        $db = new DbConnect();
        return $db->getConnection()->query("
            SELECT teams.id, teams.name, teams.logo, teams.color, teams.status,
                   countries.name AS country
            FROM teams
            JOIN countries ON teams.country_id = countries.id
        ")->fetchAll();
    }

    // Pour afficher uniquement les ACTIFS dans les formulaires
    public static function getActive()
    {
        $db = new DbConnect();
        $sql = "
            SELECT * 
            FROM teams 
            WHERE status = 'active'
            ORDER BY name ASC
        ";
        return $db->getConnection()->query($sql)->fetchAll();
    }

}
?>
