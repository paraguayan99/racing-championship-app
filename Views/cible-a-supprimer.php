<?php
session_start();

/* configuration connexion BDD */

define('SERVER'  ,"localhost");
define('USER'    ,"root");
define('PASSWORD',"");
define('BASE'    ,"ecf2");

// const SERVER = 'sqlprive-pc2372-001.eu.clouddb.ovh.net:35167';
// const USER = 'cefiidev1493';
// const PASSWORD = '2B3sB5Qgp';
// const BASE = 'cefiidev1493';

try {
    $connexion = new PDO("mysql:host=".SERVER.";dbname=".BASE, USER, PASSWORD);
}
catch (Exception $e) {
    echo "Echec de la connexion".$e->getMessage();
}

// Attaques XSS - récupère pseudo et mdp, protège des injections de scripts
$pseudo = isset($_POST['pseudo']) ? htmlspecialchars($_POST['pseudo'], ENT_QUOTES) : NULL;
$mdp = isset($_POST['mdp']) ? htmlspecialchars($_POST['mdp'], ENT_QUOTES) : NULL;

$requete = $connexion->prepare("SELECT * FROM lecteur WHERE pseudo = :parm1 AND mdp = :parm2");
$requete->bindparam(':parm1', $pseudo);
$requete->bindparam(':parm2', $mdp);

try
{
    $requete->execute();
}
catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}    

// Récupération de toutes les lignes d'un jeu de résultats
$resultat = $requete->fetch(PDO::FETCH_ASSOC);
    
if ($resultat) { 		
    session_regenerate_id();
    $_SESSION['login'] = $pseudo;
    $_SESSION['prenom'] = $resultat['prenom'];
    $_SESSION['nom'] = $resultat['nom'];
    $_SESSION['admin'] = $resultat['admin'];
    $_SESSION['id_lecteur'] = $resultat['id_lecteur'];
    // Faille CSRF - Génération du token
    if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
        $_SESSION['token_time'] = time();
    } else {
        unset($_SESSION['token']);
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
        $_SESSION['token_time'] = time();
    }
}
else {
    echo "Erreur de connexion";
    $_SESSION["errorConnect"] = TRUE;
}

// On redirige sur le site après avoir effectué tous les tests
header("location:../public/index.php?controller=creation&action=index");
?>