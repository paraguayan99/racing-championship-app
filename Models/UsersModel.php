<?php
namespace App\Models;

use App\Core\DbConnect;

class UsersModel extends DbConnect {

    public $id;
    public $email;
    public $password_hash;
    public $role_id;

    public static function findByEmail($email){
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function findById($id) {
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("
            SELECT users.id, users.email, roles.name AS role
            FROM users
            JOIN roles ON users.role_id = roles.id
            WHERE users.id = ?
        ");
        $stmt->execute([$id]);
        // FETCH_ASSOC pour retourner un tableau associatif
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function getRoleName($role_id){
        $db = new DbConnect();
        $stmt = $db->getConnection()->prepare("SELECT name FROM roles WHERE id=?");
        $stmt->execute([$role_id]);
        $row = $stmt->fetch();
        return $row ? $row->name : null;
    }

    public static function all(){
        $db = new DbConnect();
        return $db->getConnection()->query("
            SELECT users.id, email, roles.name as role
            FROM users
            JOIN roles ON users.role_id = roles.id
        ")->fetchAll();
    }
}
?>
