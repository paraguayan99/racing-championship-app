<?php
namespace App\Models;

use App\Core\DbConnect;

class User
{
    public $id;
    public $email;
    public $password_hash;
    public $role;

    private static function getDB()
    {
        $db = new \App\Core\DbConnect();
        return $db->getConnection(); // <-- utilisation de la méthode publique
    }

    /**
     * Récupérer un utilisateur par email
     */
    public static function findByEmail($email)
    {
        $pdo = self::getDB();
        $stmt = $pdo->prepare(
            "SELECT u.id, u.email, u.password_hash, r.name AS role
             FROM users u
             INNER JOIN roles r ON u.role_id = r.id
             WHERE u.email = :email"
        );
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();
        
        if ($data) {
            $user = new self();
            $user->id = $data->id;
            $user->email = $data->email;
            $user->password_hash = $data->password_hash;
            $user->role = $data->role;
            return $user;
        }

        return null;
    }

    /**
     * Récupérer tous les utilisateurs
     */
    public static function all()
    {
        $pdo = self::getDB();
        $stmt = $pdo->query(
            "SELECT u.id, u.email, r.name AS role
             FROM users u
             INNER JOIN roles r ON u.role_id = r.id"
        );

        $users = [];
        while ($data = $stmt->fetch()) {
            $user = new self();
            $user->id = $data->id;
            $user->email = $data->email;
            $user->role = $data->role;
            $users[] = $user;
        }
        return $users;
    }

    // Récupérer le nombre d'utilisateurs
    public static function count()
    {
        $pdo = self::getDB();
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
        $data = $stmt->fetch();
        return $data->total ?? 0;
    }
}
?>