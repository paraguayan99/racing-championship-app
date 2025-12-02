<?php
namespace App\Models;

use App\Core\DbConnect;

class CategoriesModel extends DbConnect {

    public $id;
    public $name;
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
            SELECT id, name, status
            FROM categories
            ORDER BY id ASC
        ")->fetchAll();
    }
}
?>

