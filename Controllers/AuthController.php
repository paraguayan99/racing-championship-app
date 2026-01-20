<?php
namespace App\Controllers;

use App\Controllers\Controller;
use App\Core\Auth;
use App\Models\UsersModel;

class AuthController extends Controller
{
    // Fonctionne pour OVH
    // Traitement de la connexion
    public function login()
    {
        $error = '';
        Auth::start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';

            // Test de validation
            $csrfValid = Auth::validateCSRF($csrf_token);

            if (!$csrfValid) {
                $error = "Token CSRF invalide !";
            } else {
                $user = UsersModel::findByEmail($email);

                if ($user && password_verify($password, $user->password_hash)) {
                    // session_regenerate_id(true);

                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['role_id'] = $user->role_id;
                    $_SESSION['role'] = UsersModel::getRoleName($user->role_id);

                    header('Location: index.php?controller=dashboard&action=index');
                    exit();
                } else {
                    $error = "Identifiants invalides";
                }
            }
        }

        $this->render('auth/login', [
            'error' => $error,
        ]);
    }

    // Traitement de la deconnexion
    public function logout()
    {
        // Utilise gestionnaire centralisé
        \App\Core\Auth::start();

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

