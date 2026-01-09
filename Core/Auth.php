<?php
namespace App\Core;

use App\Models\UsersModel;

class Auth
{
    // // Démarre une session sécurisée
    // public static function start()
    // {
    //     if (session_status() === PHP_SESSION_NONE) {
    //         // Sécuriser la session : strict mode et cookies sûrs
    //         ini_set('session.use_strict_mode', 1);
    //         // Nom de session personnalisé si plusieurs applis sur le même nom de domaine 
    //         session_name('team_eracing_sid');

    //         // Paramètres cookies : à définir AVANT session_start()
    //         $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
    //                 (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

    //         session_set_cookie_params([
    //             'lifetime' => 0,
    //             'path' => '/',
    //             'domain' => $_SERVER['HTTP_HOST'],
    //             'secure' => $secure,
    //             'httponly' => true,
    //             'samesite' => 'Strict'
    //         ]);

    //         session_start();

    //         // Deconnexion de l'utilisateur et destruction de la session après 5 minutes
    //         $lifetime = 300 ; // durée en secondes 300 = 5 minutes

    //         if (isset($_SESSION['time_activity']) && (time() - $_SESSION['time_activity'] > $lifetime)) {
    //             session_unset();     // supprime les variables
    //             session_destroy();   // détruit la session
    //             header("Location: index.php?controller=auth&action=login");
    //             exit();
    //         }

    //         $_SESSION['time_activity'] = time(); // mise à jour
    //     }
    // }


    // Fonctionne pour OVH
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name("team_eracing_sid");

            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/cedric1493/racing-championship-app/public/',
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_start();

            // Utilisé pendant le debug ... A NE PAS CONSERVER ? 
            // Force l'envoi du cookie
            if (session_id()) {
                setcookie(
                    session_name(),
                    session_id(),
                    [
                        'expires' => 0,
                        'path' => '/cedric1493/racing-championship-app/public/',
                        'domain' => '',
                        'secure' => true,
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]
                );
            }
        }

        // Crée token CSRF seulement si inexistant
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Timeout activité → seulement si user connecté
        $lifetime = 300;
        if (isset($_SESSION['user_id']) && isset($_SESSION['time_activity']) 
            && (time() - $_SESSION['time_activity'] > $lifetime)) {
            
            // Sauvegarde le token avant de détruire
            $oldToken = $_SESSION['csrf_token'] ?? null;
            
            session_unset();
            session_destroy();
            session_start();

            // Réinjecte le même token après régénération
            if ($oldToken) {
                $_SESSION['csrf_token'] = $oldToken;
            } else {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
        }

        // Mise à jour timestamp activité
        $_SESSION['time_activity'] = time();
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
    // public static function csrfToken(): string
    // {
    //     self::start();
    //     if (empty($_SESSION['csrf_token'])) {
    //         $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    //     }
    //     return $_SESSION['csrf_token'];
    // }

    // Génère un token CSRF
    // Fonctionne pour OVH
    public static function csrfToken(): string
    {
        self::start();
        return $_SESSION['csrf_token'];
    }

    // Vérifie un token CSRF
    public static function validateCSRF(string $token): bool
    {
        self::start();
        return isset($_SESSION['csrf_token']) 
            && hash_equals($_SESSION['csrf_token'], $token);
    }
}
?>
