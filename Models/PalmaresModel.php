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

    // // Récupère le Palmarès Pilotes — toutes catégories ou filtrée
    // public static function getDriversStats($categoryName = null)
    // {
    //     $db = new DbConnect();

    //     $where = $categoryName ? "WHERE p.category = :category" : "";

    //     $stmt = $db->getConnection()->prepare("
    //         SELECT p.*, c.color AS category_color
    //         FROM drivers_palmares p
    //         JOIN categories c ON c.name = p.category
    //         $where
    //         ORDER BY p.category, p.titles DESC, p.total_points DESC
    //     ");

    //     $stmt->execute($categoryName ? ['category' => $categoryName] : []);
    //     return $stmt->fetchAll(\PDO::FETCH_OBJ);
    // }

    // // Récupère le Palmarès Equipes — toutes catégories ou filtrée
    // public static function getTeamsStats($categoryName = null)
    // {
    //     $db = new DbConnect();

    //     $where = $categoryName ? "WHERE p.category = :category" : "";

    //     $stmt = $db->getConnection()->prepare("
    //         SELECT p.*, c.color AS category_color
    //         FROM teams_palmares p
    //         JOIN categories c ON c.name = p.category
    //         $where
    //         ORDER BY p.category, p.titles DESC, p.total_points DESC
    //     ");

    //     $stmt->execute($categoryName ? ['category' => $categoryName] : []);
    //     return $stmt->fetchAll(\PDO::FETCH_OBJ);
    // }

    // // Récupère le Palmarès Pilotes — toutes catégories ou filtrée
    // public static function getDriversStats($categoryName = null)
    // {
    //     $db = new DbConnect();

    //     $where = $categoryName ? "WHERE p.category = :category" : "";

    //     $stmt = $db->getConnection()->prepare("
    //         SELECT p.*, c.color AS category_color
    //         FROM cache_drivers_palmares p
    //         JOIN categories c ON c.name = p.category
    //         $where
    //         ORDER BY p.category, p.titles DESC, p.vice_titles DESC, p.third_places DESC, p.wins DESC, p.podiums DESC, p.total_points DESC, p.total_gp DESC
    //     ");

    //     $stmt->execute($categoryName ? ['category' => $categoryName] : []);
    //     return $stmt->fetchAll(\PDO::FETCH_OBJ);
    // }

    // // Récupère le Palmarès Equipes — toutes catégories ou filtrée
    // public static function getTeamsStats($categoryName = null)
    // {
    //     $db = new DbConnect();

    //     $where = $categoryName ? "WHERE p.category = :category" : "";

    //     $stmt = $db->getConnection()->prepare("
    //         SELECT p.*, c.color AS category_color
    //         FROM cache_teams_palmares p
    //         JOIN categories c ON c.name = p.category
    //         $where
    //         ORDER BY p.category, p.titles DESC, p.total_points DESC
    //     ");

    //     $stmt->execute($categoryName ? ['category' => $categoryName] : []);
    //     return $stmt->fetchAll(\PDO::FETCH_OBJ);
    // }

    // Récupère le Palmarès Pilotes — toutes catégories ou filtrée
    // Le Driver id=1 (Pilote inconnu) est trié tout en bas du classement
    public static function getDriversStats($categoryName = null)
    {
        $db = new DbConnect();

        $where = $categoryName ? "WHERE p.category = :category" : "";

        $stmt = $db->getConnection()->prepare("
            SELECT p.*, c.color AS category_color
            FROM cache_drivers_palmares p
            JOIN categories c ON c.name = p.category
            $where
            ORDER BY 
                CASE WHEN p.driver_id = 1 THEN 1 ELSE 0 END ASC,
                p.titles DESC, p.vice_titles DESC, p.third_places DESC,
                p.wins DESC, p.podiums DESC, p.total_points DESC, p.total_gp DESC,
                p.nickname ASC
        ");

        $stmt->execute($categoryName ? ['category' => $categoryName] : []);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Récupère le Palmarès Equipes — toutes catégories ou filtrée
    // Le Team id=1 (Equipe inconnue) n'est pas comptabilisé dans le Palmarès
    public static function getTeamsStats($categoryName = null)
    {
        $db = new DbConnect();

        $where = $categoryName ? "WHERE p.category = :category AND p.team_id != 1" : "WHERE p.team_id != 1";

        $stmt = $db->getConnection()->prepare("
            SELECT p.*, c.color AS category_color
            FROM cache_teams_palmares p
            JOIN categories c ON c.name = p.category
            $where
            ORDER BY p.category, p.titles DESC, p.total_points DESC, p.team_name ASC
        ");

        $stmt->execute($categoryName ? ['category' => $categoryName] : []);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
