<?php
namespace App\Controllers;

abstract class Controller 
{
    protected function authMiddleware(string|array $requiredRoles = null)
    {
        \App\Core\Auth::start();

        if (!\App\Core\Auth::check()) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Vérification des rôles
        if ($requiredRoles) {
            $userHasRole = false;

            if (is_array($requiredRoles)) {
                foreach ($requiredRoles as $role) {
                    if (\App\Core\Auth::hasRole($role)) {
                        $userHasRole = true;
                        break;
                    }
                }
            } else {
                $userHasRole = \App\Core\Auth::hasRole($requiredRoles);
            }

            if (!$userHasRole) {
                http_response_code(403);
                // Titre de la page
                $title = "Team-eRacing";

                // Contenu affichant le message d'erreur
                $content = "<div class='section-dashboard'>
                                <h1>Accès refusé</h1>
                                <p>Vous n'avez pas les autorisations nécessaires.</p>
                                <a class='nav-btn-dashboard' href='index.php'>Retour à l'accueil</a>
                            </div>";

                include dirname(__DIR__) . '/Views/base.php';
                exit;
            }
        }

        // Vérification CSRF pour POST
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (!isset($_POST['csrf_token']) 
                || !\App\Core\Auth::validateCSRF($_POST['csrf_token'])) {
                http_response_code(403);
                // Titre de la page
                $title = "Team-eRacing";

                // Contenu affichant le message d'erreur
                $content = "<div class='section-dashboard'>
                                <h1>Token invalide !</h1>
                                <p>Veuillez réessayer ou recharger la page.</p>
                                <a class='nav-btn-dashboard' href='index.php'>Retour à l'accueil</a>
                            </div>";

                include dirname(__DIR__) . '/Views/base.php';
                exit;
            }
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