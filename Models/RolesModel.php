<?php
namespace App\Models;

use App\Core\DbConnect;

class RolesModel extends DbConnect {

    // Permet de récupérer le nom des roles des Users
    public static function allRoles()
    {
        $db = new DbConnect();
        return $db->getConnection()->query("SELECT id, name FROM roles")->fetchAll();
    }
}