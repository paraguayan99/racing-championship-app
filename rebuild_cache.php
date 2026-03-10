<!-- Fichier pour initaliser en BDD la mise en place des caches Palmarès 
Attention ! Cette partie commentée peut parfois empêcher le fichier de s'éxécuter.
Il est préférable de tout supprimer et de laisser la première ligne du fichier pour < ? php

Etape 1
Copier ce fichier dans public/admin/rebuild_cache.php (créer le dossier spécialement pour cela)

Etape 2 :
Initialiser la toute première mise en cache de la totalité des données
Elle ne se fait qu'une seule fois via la classe rebuildAll()

Etape 3 :
Appeler ce fichier via le navigateur en saisissant https://tonsite/admin/rebuild_cache.php
-> La page doit afficher "Cache initialisé avec succès."

Etape 4 :
Une fois cette première mise en cache faite, supprimer le dossier admin contenant ce fichier
Il ne doit pas rester accessible pour ne pas le rappeler une seconde fois.
C'est terminé, maintenant les mises à jour des données se feront via les classes
CREATE / UPDATE / DELETE des Controllers GpPoints / Penalties / ManualAdjustments -->

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Racine du projet (2 niveaux au-dessus de public/admin/)
$projectRoot = dirname(__DIR__, 2);

// Chargement du .env (identique à index.php)
$envPath = $projectRoot . '/.env';
if (file_exists($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[$key] = $value;
    }
}

// Chargement de l'autoloader (identique à index.php)
include $projectRoot . '/Autoloader.php';
\App\Autoloader::register();

// Initialise le cache
$cache = new \App\Core\PalmaresCache();
$cache->rebuildAll();

echo "Cache initialisé avec succès.";
