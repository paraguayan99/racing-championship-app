<?php 
namespace App\Controllers;

use App\Models\User;

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

        $user = User::findByEmail($email);
        if ($user && password_verify($password, $user->password_hash)) {
            session_start();
            $_SESSION['user_id'] = $user->id;
            $_SESSION['role'] = $user->role;
            header('Location: index.php?controller=Dashboard&action=index');
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
        header('Location: index.php?controller=Auth&action=login');
        exit();
    }
}
?>
