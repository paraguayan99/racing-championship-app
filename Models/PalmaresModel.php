<?php
namespace App\Models;

use App\Core\DbConnect;

class PalmaresModel extends DbConnect
{
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
