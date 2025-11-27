<?php
session_start();

// PAGE ERREUR ou PAGE SUCCES lors de la création d'un compte Lecteur
// En fonction du $_GET['msgError'] reçu : addToken ou addLecteurSuccess -> La page affichera les bonnes infos à l'utilisateur

$title = [
    'addToken' => "Bibliothèque - Erreur" ,
    'addLecteurSuccess' => "Bibliothèque - Succès",
];

$msgErrorStart = '<div class="col-12 justify-content-end text-center bg-info bg-danger text-white my-2 py-2">';
$msgErrorEnd = '<br><a href="index.php?controller=creation&action=index">
                    <button type="button" class="btn btn-dark my-2">Retour</button></a></div>';

$msgSuccessStart = '<div class="col-12 justify-content-end text-center bg-info bg-success text-white my-2 py-2">';
$msgSuccessEnd = '<br><a href="index.php?controller=creation&action=index">
                    <button type="button" class="btn btn-dark my-2">Retour à la connexion</button></a></div>';

$msgError = [
    'addToken' => $msgErrorStart 
                    .'Erreur d\'authentification.'
                    .$msgErrorEnd ,
    'addLecteurSuccess' => $msgSuccessStart 
                    .'Création du compte effectué avec succès.'
                    .$msgSuccessEnd ,
];

$titleError = [
    'addToken' => 'ERREUR - Accès refusé',
    'addLecteurSuccess' => 'Compte validé.',
];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Affichage dynamique de la variable $title -->
    <title><?= $title[$_GET['msgError']] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/ff03dfd379.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php echo $msgError[$_GET['msgError']] ?>
    <div class="container">
        <header class="text-center">
            <h1><?php echo $titleError[$_GET['msgError']] ?></h1>
        </header>

        <footer class="text-center">
            <p>LA BIBLIOTHEQUE DU CEFII - Copyright ©2025</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</body>
</html>