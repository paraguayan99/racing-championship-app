<?php
session_start();

$title = "Création de votre compte";
?>

<h2>Création de votre compte</h2>

<?php
    unset($_SESSION['token']);
    session_destroy();

    // Ajout du form en méthode POST manuellement ($startForm désactivé dans le CreationController)
    $formPost = '<form action="#" method="POST" enctype="multipart/form-data">';

    echo $formPost .$addForm;
?>