<?php

//On importe les namespaces de l'autoloader et du router
use App\Autoloader;
use App\Core\Router;

// On inclut l'autoloader
include '../Autoloader.php';
Autoloader::register();

// On instancie le routeur
$route = new Router();

// On lance l'appli
$route->routes();

?>