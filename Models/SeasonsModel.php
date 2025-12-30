<?php
namespace App\Models;

use App\Core\DbConnect;

class SeasonsModel extends DbConnect {

    public $id;
    public $season_number;
    public $category_id;
    public $videogame;
    public $platform;
    public $status;

    // Récupérer toutes les saisons avec le nom de la catégorie
    public static function all(){
        $db = new DbConnect();
        return $db->getConnection()->query("
            SELECT seasons.id, season_number, categories.name as category, videogame, platform, seasons.status
            FROM seasons
            JOIN categories ON seasons.category_id = categories.id
        ")->fetchAll();
    }

    // Récupérer toutes les catégories (pour les select dans le formulaire)
    public static function allCategories(){
        $db = new DbConnect();
        return $db->getConnection()->query("SELECT * FROM categories")->fetchAll();
    }

    // Récupérer une saison par ID
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
