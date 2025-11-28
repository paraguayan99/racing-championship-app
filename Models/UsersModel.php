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


// class User
// {
//     public $id;
//     public $email;
//     public $password_hash;
//     public $role;

//     private static function getDB()
//     {
//         $db = new \App\Core\DbConnect();
//         return $db->getConnection(); // <-- utilisation de la méthode publique
//     }

//     /**
//      * Récupérer un utilisateur par email
//      */
//     public static function findByEmail($email)
//     {
//         $pdo = self::getDB();
//         $stmt = $pdo->prepare(
//             "SELECT u.id, u.email, u.password_hash, r.name AS role
//              FROM users u
//              INNER JOIN roles r ON u.role_id = r.id
//              WHERE u.email = :email"
//         );
//         $stmt->execute(['email' => $email]);
//         $data = $stmt->fetch();
        
//         if ($data) {
//             $user = new self();
//             $user->id = $data->id;
//             $user->email = $data->email;
//             $user->password_hash = $data->password_hash;
//             $user->role = $data->role;
//             return $user;
//         }

//         return null;
//     }

//     /**
//      * Récupérer tous les utilisateurs
//      */
//     public static function all()
//     {
//         $pdo = self::getDB();
//         $stmt = $pdo->query(
//             "SELECT u.id, u.email, r.name AS role
//              FROM users u
//              INNER JOIN roles r ON u.role_id = r.id"
//         );

//         $users = [];
//         while ($data = $stmt->fetch()) {
//             $user = new self();
//             $user->id = $data->id;
//             $user->email = $data->email;
//             $user->role = $data->role;
//             $users[] = $user;
//         }
//         return $users;
//     }

//     // Récupérer le nombre d'utilisateurs
//     public static function count()
//     {
//         $pdo = self::getDB();
//         $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
//         $data = $stmt->fetch();
//         return $data->total ?? 0;
//     }
// }
