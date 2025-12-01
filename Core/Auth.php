<?php
namespace App\Core;

use App\Models\UsersModel;

class Auth
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Sécuriser la session : strict mode et cookies sûrs
            ini_set('session.use_strict_mode', 1);
            // Nom de session custom (optionnel)
            session_name('team_eracing_sid');

            // Paramètres cookies : à définir AVANT session_start()
            $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
                    (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => $_SERVER['HTTP_HOST'],
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            session_start();

            // Deconnexion de l'utilisateur et destruction de la session après 5 minutes
            $lifetime = 300 ; // durée en secondes 300 = 5 minutes

            if (isset($_SESSION['time_activity']) && (time() - $_SESSION['time_activity'] > $lifetime)) {
                session_unset();     // supprime les variables
                session_destroy();   // détruit la session
                header("Location: index.php?controller=auth&action=login");
                exit();
            }

            $_SESSION['time_activity'] = time(); // mise à jour
        }
    }
    
    // Vérifie si un user est connecté
    public static function check(): bool
    {
        self::start();
        return isset($_SESSION['user_id']);
    }

    // Récupère le rôle (string)
    public static function role(): ?string
    {
        self::start();
        return $_SESSION['role'] ?? null;
    }

    // Vérifie si l'utilisateur a un rôle spécifique
    public static function hasRole(string $requiredRole): bool
    {
        self::start();
        return isset($_SESSION['role']) && $_SESSION['role'] === $requiredRole;
    }

    // Génère un token CSRF
    public static function csrfToken(): string
    {
        self::start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Vérifie un token CSRF
    public static function validateCSRF(string $token): bool
    {
        self::start();
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
