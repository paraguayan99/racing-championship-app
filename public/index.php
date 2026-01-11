<?php
// Afficher les erreurs avant tout affichage de HTML
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Chargement des variables d'environnement comme pour la connexion à la BDD
// Fichier .env à la racine du site (.gitignore)
$envPath = __DIR__ . '/../.env';

if (file_exists($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue; // ligne vide ou commentaire

        [$key, $value] = explode('=', $line, 2);
        $_ENV[$key] = $value;
    }
}

// Solution trouvée pour les TOKENS avec OVH
// Démarrer la session AVANT tout le contenu HTML
// Cela permet aux cookies de session d'être envoyés correctement

// On inclut l'autoloader en premier
include '../Autoloader.php';

// On importe les namespaces nécessaires
use App\Autoloader;
use App\Core\Router;
use App\Core\Auth;

// On enregistre l'autoloader
use App\Core\Auth;

// On enregistre l'autoloader
Autoloader::register();

// On démarre la session IMMÉDIATEMENT après l'autoloader
// AVANT toute sortie HTML ou echo
Auth::start();

// On démarre la session IMMÉDIATEMENT après l'autoloader
// AVANT toute sortie HTML ou echo
Auth::start();

// On instancie le routeur
$route = new Router();

// On lance l'appli
$route->routes();
?>