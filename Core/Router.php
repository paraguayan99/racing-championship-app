<?php
namespace App\Core;

class Router 
{
    // Fonctionne avec WAMP SERVER
    // Permet de diriger l'URL récupéré en GET vers le contrôleur
    // public function routes()
    // {
    //     $controllerName = isset($_GET['controller']) ? ucfirst(array_shift($_GET)) : 'Home';
    //     $controllerClass = '\\App\\Controllers\\' . $controllerName . 'Controller';

    //     $action = isset($_GET['action']) ? array_shift($_GET) : 'index';

    //     // Vérification CSRF pour les requêtes POST
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         \App\Core\Auth::start();
    //         $token = $_POST['csrf_token'] ?? '';
    //         if (!\App\Core\Auth::validateCSRF($token)) {
    //             $this->render403();
    //         }
    //     }

    //     // Vérifier si la classe du contrôleur existe
    //     if (class_exists($controllerClass)) {
    //         $controller = new $controllerClass();

    //         if (method_exists($controller, $action)) {
    //             // Appeler la méthode avec les paramètres $_GET
    //             call_user_func_array([$controller, $action], $_GET ?? []);
    //         } else {
    //             $this->render404();
    //         }
    //     } else {
    //         $this->render404();
    //     }
    // }



    // Fonctionne sur OVH

    //  Table de correspondance pour les controllers composés
    //  Clé = nom du controller en minuscules dans l'URL
    //  Valeur = nom exact de la classe Controller
    private $controllerMap = [
        'statscircuits' => 'StatsCircuits',
        'teamsdrivers' => 'TeamsDrivers',
        'gppoints' => 'GpPoints',
        'gpstats' => 'GpStats',
        'manualadjustments' => 'ManualAdjustments',
    ];


    // Convertit une chaîne en "StudlyCase" comme home => Home
    private function studlyCase(string $value): string {
        $words = preg_split('/[_-]/', $value); // découpe par _ ou -
        $words = array_map(fn($w) => ucfirst(strtolower($w)), $words); // majuscule sur chaque mot
        return implode('', $words);
    }


    // Route l'URL vers le bon controller / action
    public function routes()
    {
        $rawController = $_GET['controller'] ?? 'Home';

        // Vérifie la table de correspondance sinon utilise studlyCase
        $controllerName = $this->controllerMap[strtolower($rawController)] ?? $this->studlyCase($rawController);

        $controllerClass = '\\App\\Controllers\\' . $controllerName . 'Controller';

        $action = $_GET['action'] ?? 'index';


        // Contrôle TOKEN CSRF retiré car c'est le Controller qui s'en charge avec sa méthode authMiddleware

        // // Vérification CSRF pour les requêtes POST
        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     \App\Core\Auth::start();
        //     $token = $_POST['csrf_token'] ?? '';
        //     if (!\App\Core\Auth::validateCSRF($token)) {
        //         $this->render403();
        //     }
        // }

        // Vérifie si la classe du contrôleur existe
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();

            if (method_exists($controller, $action)) {
                // call_user_func_array([$controller, $action], $_GET ?? []);
                $params = $_GET ?? [];
                unset($params['controller'], $params['action']);
                call_user_func_array([$controller, $action], $params);
            } else {
                $this->render404();
            }
        } else {
            $this->render404();
        }
    }








    // Erreur si contrôleur n'existe pas
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
                <a class='nav-btn-dashboard' href='/'>Retour à l'accueil</a>
            </div>
        ";

        include dirname(__DIR__) . '/Views/base.php';
        exit;
    }

    // Erreur si Token Invalide
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
                <a class='nav-btn-dashboard' href='/'>Retour à l'accueil</a>
            </div>
        ";

        include dirname(__DIR__) . '/Views/base.php';
        exit;
    }
}
?>