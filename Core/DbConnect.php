<?php
namespace App\Core;

use PDO;
use Exception;

class DbConnect
{
    protected $connection;
    protected $request;

    // Permet aux autres classes de récupérer $connection sans violer l’encapsulation.
    public function getConnection()
    {
        return $this->connection;
    }

    // Connexion sécurisée à la BDD via PDO
    public function __construct()
    {
        try {
            // Récupération des variables depuis le fichier .env
            $host = $_ENV['DB_HOST'] ?? '';
            $port = $_ENV['DB_PORT'] ?? '';
            $db   = $_ENV['DB_NAME'] ?? '';
            $user = $_ENV['DB_USER'] ?? '';
            $pass = $_ENV['DB_PASS'] ?? '';

            // Durcir PDO / charset — Core/DbConnect.php
            // Dans le constructeur, améliore le DSN et quelques attributs PDO :
            // charset=utf8mb4 évite problèmes d'encodage (et failles potentielles).
            // ATTR_EMULATE_PREPARES = false améliore la sécurité des requêtes préparées.
            $dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $db . ';charset=utf8mb4';
                $this->connection = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
?>