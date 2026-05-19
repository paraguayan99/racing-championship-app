<?php
namespace App\Core;

class Router 
{
    //  Table de correspondance pour les controllers composés
    //  Clé = nom du controller en minuscules dans l'URL
    //  Valeur = nom exact de la classe Controller
    private $controllerMap = [
        'statscircuits' => 'StatsCircuits',
        'statsdrivers'   => 'StatsDrivers',
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

    // Test pour les redirections facebook qui étaient mal gérés
    // fbclid, utm_source et tout autre paramètre inattendu sont silencieusement ignorés avant d'arriver à call_user_func_array, 
    // quelle que soit la source (Facebook, Google, etc.).
    public function routes()
    {
        $rawController = $_GET['controller'] ?? 'Home';

        $controllerName = $this->controllerMap[strtolower($rawController)] ?? $this->studlyCase($rawController);
        $controllerClass = '\\App\\Controllers\\' . $controllerName . 'Controller';

        $action = $_GET['action'] ?? 'index';

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();

            if (method_exists($controller, $action)) {
                $params = $_GET ?? [];
                unset($params['controller'], $params['action']);

                // Ne garder que les paramètres attendus par la méthode
                $reflection = new \ReflectionMethod($controller, $action);
                $expectedParams = array_map(
                    fn($p) => $p->getName(),
                    $reflection->getParameters()
                );
                $params = array_intersect_key($params, array_flip($expectedParams));

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