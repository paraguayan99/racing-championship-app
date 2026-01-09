<?php
namespace App;

// Charge automatiquement les fichiers de classes PHP
// class Autoloader{

//     static function register(){
//         spl_autoload_register([
//             __CLASS__,
//             'autoload'
//         ]);
//     }

//     static function autoload($class){
//         $class = str_replace(__NAMESPACE__.'\\','',$class);
//         $class = str_replace('\\','/',$class);

//         echo "Autoload cherche : " . __DIR__ . '/' . $class . '.php'; exit;

//         if(file_exists(__DIR__ . '/' . $class . '.php')){
//             require __DIR__ . '/' . $class . '.php';
//         }
//     }
// }


// Spécifique pour OVH
class Autoloader {
    static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    static function autoload($class) {
        // Supprime le namespace App\
        $class = str_replace(__NAMESPACE__ . '\\', '', $class);
        $class = str_replace('\\', '/', $class);

        // Chemin absolu depuis le dossier racine du projet
        $projectRoot = dirname(__DIR__) . '/racing-championship-app';
        $file = $projectRoot . '/' . $class . '.php';

        if (file_exists($file)) {
            require $file;
        } else {
            echo "Autoloader : fichier introuvable '$file' pour la classe $class";
            exit;
        }
    }
}