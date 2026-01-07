<?php
namespace App\Models;

use App\Core\DbConnect;

class PalmaresModel extends DbConnect
{
    // Récupère le Palmarès Pilotes de toutes les saisons
    public static function getDriversStats()
    {
        $db = new DbConnect();
        return $db->getConnection()
            ->query("
                SELECT p.*, c.color AS category_color
                FROM drivers_palmares p
                JOIN categories c ON c.name = p.category
                ORDER BY p.category, p.titles DESC, p.total_points DESC
            ")
            ->fetchAll(\PDO::FETCH_OBJ);
    }

    // Récupère le Palmarès Equipes de toutes les saisons
    public static function getTeamsStats()
    {
        $db = new DbConnect();
        return $db->getConnection()
            ->query("
                SELECT p.*, c.color AS category_color
                FROM teams_palmares p
                JOIN categories c ON c.name = p.category
                ORDER BY p.category, p.titles DESC, p.total_points DESC
            ")
            ->fetchAll(\PDO::FETCH_OBJ);
    }
}
