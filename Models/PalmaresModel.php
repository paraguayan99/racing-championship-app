<?php
namespace App\Models;

use App\Core\DbConnect;

class PalmaresModel extends DbConnect
{
    // // Récupère le Palmarès Pilotes de toutes les saisons
    // public static function getDriversStats()
    // {
    //     $db = new DbConnect();
    //     return $db->getConnection()
    //         ->query("
    //             SELECT p.*, c.color AS category_color
    //             FROM drivers_palmares p
    //             JOIN categories c ON c.name = p.category
    //             ORDER BY p.category, p.titles DESC, p.total_points DESC
    //         ")
    //         ->fetchAll(\PDO::FETCH_OBJ);
    // }

    // // Récupère le Palmarès Equipes de toutes les saisons
    // public static function getTeamsStats()
    // {
    //     $db = new DbConnect();
    //     return $db->getConnection()
    //         ->query("
    //             SELECT p.*, c.color AS category_color
    //             FROM teams_palmares p
    //             JOIN categories c ON c.name = p.category
    //             ORDER BY p.category, p.titles DESC, p.total_points DESC
    //         ")
    //         ->fetchAll(\PDO::FETCH_OBJ);
    // }





    // Récupère toutes les catégories pour le select
    public static function getAllCategoriesForSelect()
    {
        $db = new DbConnect();
        return $db->getConnection()
            ->query("
                SELECT id, name, color
                FROM categories
                ORDER BY name ASC
            ")
            ->fetchAll(\PDO::FETCH_OBJ);
    }

    // Récupère le Palmarès Pilotes — toutes catégories ou filtrée
    public static function getDriversStats($categoryName = null)
    {
        $db = new DbConnect();

        $where = $categoryName ? "WHERE p.category = :category" : "";

        $stmt = $db->getConnection()->prepare("
            SELECT p.*, c.color AS category_color
            FROM drivers_palmares p
            JOIN categories c ON c.name = p.category
            $where
            ORDER BY p.category, p.titles DESC, p.total_points DESC
        ");

        $stmt->execute($categoryName ? ['category' => $categoryName] : []);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Récupère le Palmarès Equipes — toutes catégories ou filtrée
    public static function getTeamsStats($categoryName = null)
    {
        $db = new DbConnect();

        $where = $categoryName ? "WHERE p.category = :category" : "";

        $stmt = $db->getConnection()->prepare("
            SELECT p.*, c.color AS category_color
            FROM teams_palmares p
            JOIN categories c ON c.name = p.category
            $where
            ORDER BY p.category, p.titles DESC, p.total_points DESC
        ");

        $stmt->execute($categoryName ? ['category' => $categoryName] : []);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
