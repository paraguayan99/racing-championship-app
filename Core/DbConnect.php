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

    private const SERVER = 'localhost';
    private const USER = 'root';
    private const PASSWORD = '';
    private const BASE = 'racing_championship_db';

    // Connexion sécurisée à la BDD via PDO
    public function __construct()
    {
        try {
            // Durcir PDO / charset — Core/DbConnect.php
            // Dans le constructeur, améliore le DSN et quelques attributs PDO :
            // charset=utf8mb4 évite problèmes d'encodage (et failles potentielles).
            // ATTR_EMULATE_PREPARES = false améliore la sécurité des requêtes préparées.

            $dsn = 'mysql:host=' . self::SERVER . ';dbname=' . self::BASE . ';charset=utf8mb4';
                $this->connection = new PDO($dsn, self::USER, self::PASSWORD, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_EMULATE_PREPARES => false // forcer prepares natifs
                ]);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
?>