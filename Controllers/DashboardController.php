<?php
namespace App\Controllers;

use App\Models\UsersModel;

class DashboardController extends Controller
{
    public function index()
    {
        session_start();
        if(!isset($_SESSION['user_id'])){ header("Location:index.php?controller=auth&action=login"); exit(); }

        // Admin → dashboard complet
        if($_SESSION['role'] == "Administrateur"){
            $this->render("dashboard/admin");
        }
        // Modérateur → dashboard réduit
        elseif($_SESSION['role'] == "Moderateur"){
            $this->render("dashboard/moderator");
        }
        // Utilisateur normal → accès refusé
        else{
            $this->render("dashboard/user");
            exit();
        }
    }

    // Middleware pour vérifier la session et le rôle
    protected function authMiddleware($requiredRole = null)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        if ($requiredRole && $_SESSION['role'] !== $requiredRole) {
            echo "Accès refusé";
            exit();
        }
    }

    // Render adapté à base.php
    public function render($view, $data = [])
    {
        extract($data);
        ob_start();
        require_once "../Views/$view.php";
        $content = ob_get_clean();
        require_once "../Views/base.php";
    }

    // Dashboard principal
    // public function index()
    // {
    //     $this->authMiddleware(); // Protection : utilisateur connecté
    //     $this->render('dashboard/index');
    // }

    // Gestion des utilisateurs (admin uniquement)
    public function manageUsers()
    {
        $this->authMiddleware('admin');
        $users = UsersModel::all(); // Exemple : récupère tous les utilisateurs
        $this->render('dashboard/manageUsers');
    }

    // Paramètres du dashboard (admin uniquement)
    public function settings()
    {
        $this->authMiddleware('admin');
        $this->render('dashboard/settings');
    }
}

?>