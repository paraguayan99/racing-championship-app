<?php
namespace App\Controllers;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Protection : utilisateur connecté
        $this->authMiddleware();
    }

    public function index()
    {
        // Redirection selon le rôle
        // $role = $_SESSION['role'] ?? '';
        $role = \App\Core\Auth::role();

        switch ($role) {
            case "Administrateur":
                $this->render('dashboard/admin');
                break;
            case "Moderateur":
                $this->render('dashboard/moderator');
                break;
            default:
                $this->render('dashboard/user');
                break;
        }
    }
}
?>
