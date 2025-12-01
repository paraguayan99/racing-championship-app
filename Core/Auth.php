<?php
namespace App\Core;

use App\Models\UsersModel;

class Auth
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // OPTION POUR SECURISER LA SESSION UN PEU PLUS
        // session_set_cookie_params([
        //     'httponly' => true,
        //     'secure' => isset($_SERVER['HTTPS']),
        //     'samesite' => 'Strict'
        // ]);
        // session_start();
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
