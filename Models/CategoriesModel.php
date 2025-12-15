<?php
namespace App\Models;

use App\Core\DbConnect;

class CategoriesModel extends DbConnect {

    public $id;
    public $name;
    public $color;
    public $status;

    public static function findByName($name){
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT * FROM categories WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    public static function all(){
        $db = new DbConnect();
        return $db->getConnection()->query("
            SELECT id, name, color, status
            FROM categories
            ORDER BY id ASC
        ")->fetchAll();
    }

    // Pour afficher uniquement les ACTIFS dans les formulaires
    public static function getActive()
    {
        $db = new DbConnect();
        $sql = "SELECT * FROM categories WHERE status = 'active' ORDER BY name ASC";
        return $db->getConnection()->query($sql)->fetchAll();
    }

}
?>

