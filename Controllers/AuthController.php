<?php
namespace App\Controllers;

use App\Controllers\Controller;
use App\Core\Auth;
use App\Models\UsersModel;

class AuthController extends Controller
{
    // Traitement de la connexion
    public function login()
    {
        $error = '';
        Auth::start();

        // Initialise le suivi des tentatives si inexistant
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = [
                'count' => 0,
                'level' => 0,
                'blocked_until' => null
            ];
        }

        // Si blocage actif, affiche le message et stoppe
        if (
            $_SESSION['login_attempts']['blocked_until'] !== null &&
            time() < $_SESSION['login_attempts']['blocked_until']
        ) {
            $remainingMinutes = ceil(
                ($_SESSION['login_attempts']['blocked_until'] - time()) / 60
            );

            $unit = ($remainingMinutes > 1) ? 'minutes' : 'minute';

            $this->render('auth/login', [
                'error' => "Trop de tentatives. Réessayez dans $remainingMinutes $unit."
            ]);
            return;
        }

        // Traite le formulaire uniquement en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email      = $_POST['email'] ?? '';
            $password   = $_POST['password'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';
            $captcha    = $_POST['captcha'] ?? '';

            // Vérifie CAPTCHA + CSRF
            if (!Auth::validateCaptcha($captcha) || !Auth::validateCSRF($csrf_token)) {
                $error = $this->handleFailedAttempt("Captcha ou Token invalide.");
            }
            else {
                $user = UsersModel::findByEmail($email);

                // Authentification réussie
                if ($user && password_verify($password, $user->password_hash)) {

                    // Réinitialise la sécurité
                    unset($_SESSION['login_attempts']);
                    // Prévient la fixation de session
                    session_regenerate_id(true);

                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['role_id'] = $user->role_id;
                    $_SESSION['role']    = UsersModel::getRoleName($user->role_id);

                    header('Location: index.php?controller=dashboard&action=index');
                    exit();
                }
                else {
                    // Échec identifiants
                    $error = $this->handleFailedAttempt("Identifiants invalides.");
                }
            }
        }

        // Affiche la page de connexion avec le message d’erreur éventuel
        $this->render('auth/login', [
            'error' => $error
        ]);
    }

    // Traite les tentatives échouées et renvoie le message d'erreur
    private function handleFailedAttempt(string $baseMessage): string
    {
        // Compte une tentative échouée
        $_SESSION['login_attempts']['count']++;
        // Ralentit les bots (anti brute-force)
        usleep(300000);

        // Si 5 erreurs => blocage
        if ($_SESSION['login_attempts']['count'] >= 5) {

            $_SESSION['login_attempts']['count'] = 0;
            $_SESSION['login_attempts']['level']++;

            switch ($_SESSION['login_attempts']['level']) {
                case 1: $delay = 300; break;   // 5 minutes
                case 2: $delay = 1800; break;  // 30 minutes
                case 3: $delay = 3600; break;  // 1 heure
                default: $delay = 86400;       // 24 heures
            }

            $_SESSION['login_attempts']['blocked_until'] = time() + $delay;

            $minutes = ceil($delay / 60);
            $unit = ($minutes > 1) ? 'minutes' : 'minute';

            return "Trop de tentatives. Réessayez dans $minutes $unit.";
        }

        // Sinon affiche les tentatives restantes
        $remainingAttempts = max(0, 5 - $_SESSION['login_attempts']['count']);
        return "$baseMessage $remainingAttempts tentative(s) restante(s).";
    }

    // Traitement de la deconnexion
    public function logout()
    {
        // Utilise gestionnaire centralisé
        Auth::start();

        // Vide toutes les variables
        $_SESSION = [];

        // Supprime le cookie de session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Détruit la session
        session_destroy();

        // Regénère un nouvel id vide
        // session_regenerate_id(true);

        header('Location: index.php?controller=auth&action=login');
        exit();
    }
}

