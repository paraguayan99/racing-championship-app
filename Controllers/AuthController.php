<?php 
namespace App\Controllers;

use App\Models\UsersModel;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $this->render('auth/login');
    }

    public function login()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = UsersModel::findByEmail($email);
        if ($user && password_verify($password, $user->password_hash)) {
            session_start();
            $_SESSION['user_id'] = $user->id;
            // $_SESSION['role'] = $user->role;
            $_SESSION['role_id'] = $user->role_id;
            $_SESSION['role'] = UsersModel::getRoleName($user->role_id); // "Administrateur", "Moderateur", "Utilisateur"
            header('Location: index.php?controller=dashboard&action=index');
            exit();
        } else {
            $error = "Identifiants invalides";
            $this->render('auth/login', ['error' => $error]);
        }
    } else {
        // GET → juste afficher le formulaire
        $this->render('auth/login');
    }
}

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit();
    }
}
?>
