<?php
namespace App\Controllers;

use App\Controllers\Controller;
use App\Core\Auth;
use App\Models\UsersModel;

class AuthController extends Controller
{
    // Traitement du login
    public function login()
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $csrf_token = $_POST['csrf_token'] ?? '';

            // Vérification CSRF
            if (!Auth::validateCSRF($csrf_token)) {
                $error = "Token CSRF invalide !";
            } else {
                $user = UsersModel::findByEmail($email);

                if ($user && password_verify($password, $user->password_hash)) {
                    // Connexion sécurisée
                    session_start();

                    // Anti session fixation
                    session_regenerate_id(true);
                    // Regeneration du token CSRF après connexion
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['role_id'] = $user->role_id;
                    $_SESSION['role'] = UsersModel::getRoleName($user->role_id);

                    header('Location: index.php?controller=dashboard&action=index');
                    exit();
                } else {
                    $error = "Identifiants invalides";
                }

                // if ($user && password_verify($password, $user->password_hash)) {
                //     // Connexion sécurisée
                //     session_start();
                //     $_SESSION['user_id'] = $user->id;
                //     $_SESSION['role_id'] = $user->role_id;
                //     $_SESSION['role'] = UsersModel::getRoleName($user->role_id);

                //     // Redirection simple vers le dashboard
                //     header('Location: index.php?controller=dashboard&action=index');
                //     exit();
                // } else {
                //     $error = "Identifiants invalides";
                // }
            }
        }

        // Affiche le formulaire avec l’erreur éventuelle
        $this->render('auth/login', ['error' => $error]);
    }

    // Déconnexion
    public function logout()
    {
        session_start();
        $_SESSION = [];
        unset($_SESSION['csrf_token']);
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit();
    }
}

