<?php
//On importe les namespaces de l'autoloader et du router
use App\Autoloader;
use App\Core\Router;

// Forcer HTTPS (si possible) et headers sécurité
// if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
//     // Si on est derrière un reverse proxy, vérifier HTTP_X_FORWARDED_PROTO si nécessaire
//     if (empty($_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https') {
//         $httpsUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//         header('Location: ' . $httpsUrl);
//         exit;
//     }
// }

// // Headers de sécurité (CSP minimal + X-Frame + XSS protection)
// header("X-Frame-Options: SAMEORIGIN");
// header("X-Content-Type-Options: nosniff");
// header("Referrer-Policy: no-referrer-when-downgrade");
// header("X-XSS-Protection: 1; mode=block");
// // CSP très simple — adapter selon assets/images/external scripts
// header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");


// On inclut l'autoloader
include '../Autoloader.php';
Autoloader::register();

// On instancie le routeur
$route = new Router();

// On lance l'appli
$route->routes();

?>