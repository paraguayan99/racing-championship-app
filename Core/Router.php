<?php
namespace App\Core;

class Router 
{
    public function routes()
    {
        $controllerName = isset($_GET['controller']) ? ucfirst(array_shift($_GET)) : 'Home';
        $controllerClass = '\\App\\Controllers\\' . $controllerName . 'Controller';

        $action = isset($_GET['action']) ? array_shift($_GET) : 'index';

        // Vérification CSRF pour les requêtes POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            \App\Core\Auth::start();
            $token = $_POST['csrf_token'] ?? '';
            if (!\App\Core\Auth::validateCSRF($token)) {
                $this->render403();
            }
        }

        // Vérifier si la classe du contrôleur existe
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();

            if (method_exists($controller, $action)) {
                // Appeler la méthode avec les paramètres $_GET
                call_user_func_array([$controller, $action], $_GET ?? []);
            } else {
                $this->render404();
            }
        } else {
            $this->render404();
        }
    }

    private function render404()
    {
        http_response_code(404);

        // Titre de la page
        $title = "Team-eRacing";

        // Contenu affichant le message d'erreur
        $content = "
            <div class='section-dashboard'>
                <h1>404 - Page introuvable</h1>
                <p>La page que vous cherchez n'existe pas.</p>
                <a class='nav-btn-dashboard' href='index.php'>Retour à l'accueil</a>
            </div>
        ";

        include dirname(__DIR__) . '/Views/base.php';
        exit;
    }

    private function render403()
    {
        http_response_code(403);

        // Titre de la page
        $title = "Team-eRacing";

        // Contenu affichant le message d'erreur
        $content = "
            <div class='section-dashboard'>
                <h1>Token invalide !</h1>
                <p>Veuillez réessayer ou recharger la page.</p>
                <a class='nav-btn-dashboard' href='index.php'>Retour à l'accueil</a>
            </div>
        ";

        include dirname(__DIR__) . '/Views/base.php';
        exit;
    }
}

// namespace App\Core;

// class Router 
// {

//     public function routes()
//     {
//         // On teste si la superglobale $_GET['controller'] est déclarée et non vide, puis on ajoute le premier index de $_GET dans la variable
//         //$controller, ou par défaut 'Home', ainsi que son namespace, plus le mot Controller
//         //pour compléter le nom de la classe controller à instancier.

//         $controller = (isset($_GET['controller']) ? ucfirst(array_shift($_GET)) : 'Home');
//         $controller = '\\App\\Controllers\\' . $controller . 'Controller';

//         //On teste si la superglobale $_GET['action'] est déclarée et non vide, puis on ajoute le premier index de $_GET dans la variable
//         //$action, ou par défaut 'index'.
//         $action = (isset($_GET['action']) ? array_shift($_GET) : 'index');

//         // On instancie le contrôleur
//         $controller = new $controller();


//         // Vérification CSRF systématique pour toutes les requêtes POST
//         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//             \App\Core\Auth::start();
//             $token = $_POST['csrf_token'] ?? '';
//             if (!\App\Core\Auth::validateCSRF($token)) {
//                 http_response_code(400);
//                 die("Token CSRF invalide !");
//             }
//         }

//         // print_r($controller);
//         // var_dump($controller);
//         // print_r($action);
//         // var_dump($action);

//         if (method_exists($controller, $action)) {
//             // Si $_GET contient des index, on exécute la méthode en passant comme argument les paramètres de $_GET ou alors
//             // On exécute la méthode sans argument.
//             (isset($_GET)) ? call_user_func_array([$controller, $action], $_GET) : $controller->$action();
//         } else {
//             // Route inexistante → code 404 + affichage via template
//             http_response_code(404);
            
//             // Contenu à afficher dans la vue
//             $content = "
//                 <div class='section-dashboard'>
//                     <h1>404 - Page introuvable</h1>
//                     <p>La page que vous cherchez n'existe pas.</p>
//                     <a class='nav-btn-dashboard' href='index.php'>Retour à l'accueil</a>
//                 </div>
//             ";

//             // Inclure le template principal
//             include dirname(__DIR__) . '/Views/base.php';
//             exit;
//         }
//     }
// }

?>