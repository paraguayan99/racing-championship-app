<?php
namespace App\Core;

use App\Models\UsersModel;

class Auth
{
    // Démarre une session sécurisée
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name("team_eracing_sid");

            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_start();
 
            // Force l'envoi du cookie
            if (session_id()) {
                setcookie(
                    session_name(),
                    session_id(),
                    [
                        'expires' => 0,
                        'path' => '/',
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

        // Timeout activité → seulement si user connecté (1800s == 30min)
        $lifetime = 1800;
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

    // Génére le Captcha
    public static function generateCaptcha(): string
    {
        self::start();

        $a = random_int(1, 9);
        $b = random_int(1, 5);

        $_SESSION['captcha_answer'] = $a + $b;

        return "Captcha : Combien font $a plus $b ?";
    }

    // Vérifie le Captcha
    public static function validateCaptcha($answer): bool
    {
        self::start();

        if (!isset($_SESSION['captcha_answer'])) {
            return false;
        }

        $isValid = ((int)$answer === (int)$_SESSION['captcha_answer']);

        // Supprime le captcha après usage
        unset($_SESSION['captcha_answer']);

        return $isValid;
    }
}
?>
