<?php
namespace App\Controllers;

abstract class Controller 
{
    protected function authMiddleware($requiredRole = null)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        if ($requiredRole && $_SESSION['role'] !== $requiredRole) {
            echo "Accès refusé";
            exit();
        }
    }
    
    public function render(string $path, array $data = [])
    {
        // Permet d'extraire les données récupérées sous forme de variables
        extract($data);

        // On crée le buffer de sortie
        ob_start();

        // Crée le chemin et inclut le fichier de la vue souhaitée
        include dirname(__DIR__) . '/Views/' . $path . '.php';

        // On vide le buffer dans les variables $title et $content
        $content = ob_get_clean();

        // On fabrique le "template"
        include dirname(__DIR__) . '/Views/base.php';
    }
}
?>