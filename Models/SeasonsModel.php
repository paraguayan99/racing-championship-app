<?php
namespace App\Models;

use App\Core\DbConnect;

class SeasonsModel extends DbConnect {

    public $id;
    public $season_number;
    public $season_name;
    public $category_id;
    public $videogame;
    public $platform;
    public $status;

    // Récupère toutes les saisons avec le nom de la catégorie
    public static function all(){
        $db = new DbConnect();
        return $db->getConnection()->query("
            SELECT seasons.id, season_number, season_name, categories.name as category, videogame, platform, seasons.status
            FROM seasons
            JOIN categories ON seasons.category_id = categories.id
            ORDER BY seasons.status ASC, categories.name ASC, seasons.season_number DESC
        ")->fetchAll();
    }

    // Récupère toutes les catégories (pour les select dans le formulaire)
    public static function allCategories(){
        $db = new DbConnect();
        return $db->getConnection()->query("SELECT * FROM categories")->fetchAll();
    }

    // Récupère une saison par un ID
    public static function findById($id){
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT * FROM seasons WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Pour afficher uniquement les saisons ACTIVES
    public static function getActive()
    {
        $db = new DbConnect();
        return $db->getConnection()->query("
            SELECT 
                seasons.id,
                seasons.season_number,
                seasons.season_name,
                categories.name AS category,
                seasons.videogame,
                seasons.platform,
                seasons.status
            FROM seasons
            JOIN categories ON seasons.category_id = categories.id
            WHERE seasons.status = 'active'
            ORDER BY seasons.season_number DESC
        ")->fetchAll(\PDO::FETCH_OBJ);
    }
}
?>
