<?php
session_start();

$title = "ADMIN - Modification d'un livre";
?>

<h2>ADMIN - Modification d'un livre</h2>

<?php
// Détournement de session -> Test le temps de connexion de 5 minutes maximum, il faudra s'identifier à nouveau
$timestamp = time() - (5 * 60);

// Sécurité -> Test login, token et durée de session
if (isset($_SESSION["login"]) && isset($_SESSION['token']) && $_SESSION['token_time'] > $timestamp) {
    echo '<div class="container">
                <div class="col-12 rounded justify-content-end text-center text-white bg-secondary">
                    Vous êtes connecté (<b>' .$_SESSION['prenom'] .' ' .$_SESSION['nom'] .' - ' .$_SESSION['login'] .'</b>)
                        <button type="button" class="btn btn-primary"><a class="text-white" href="../Views/deconnexion.php">Me déconnecter</a></button>
                        <button type="button" class="btn btn-warning">ADMIN</button>
                </div>';
    if (!empty($erreur)) {
    echo '<div class="alert alert-danger" role="alert">'
        .$erreur .'</div>';
    }

    // Ajout du form en méthode POST manuellement ($startForm désactivé dans le CreationController)
    $formPost = '<form action="#" method="POST" enctype="multipart/form-data">';
    // Ajout de l'input hidden pour le token
    $inputToken = '<input type="hidden" name="token" id="token" value=' .$_SESSION['token'] .'>';

    echo '
    <section class="row">
        <div class="col-12">' .$formPost .$inputToken .$updateForm .'</div>
    </section>
    ';

} elseif (isset($_SESSION["errorConnect"])){
    // Si les identifiants sont incorrectes, message d'erreur et bouton pour réessayer la connexion
    echo '<form action="../Views/cible.php" method="post" class="container">
                <div class="col-12 rounded justify-content-end text-center bg-info bg-danger text-white">
                Vos identifiants sont incorrects <a class ="text-white" href="../Views/deconnexion.php">Réessayer</a>
                </div>
            </form>';
} else {
    // Si aucune session n'existe ou a existé, et si il n'y a pas eu de tentative de connexion
    // Alors le formulaire de connexion s'affiche normalement en ayant pris soin de faire un session_destroy
    unset($_SESSION['token']);
    session_destroy();
    echo '<form action="../Views/cible.php" method="post" class="container">
                <div class="col-12 rounded py-2 justify-content-end text-center text-white bg-secondary">
                    <label for="pseudo">Pseudo :</label>
                    <input type="text" class="btn btn-light" name="pseudo" id="pseudo">
                
                    <label for="mdp">MDP :</label>
                    <input type="text" class="btn btn-light" name="mdp" id="mdp">

                    <input type="submit" class="btn btn-primary" value="Se connecter">
                    <a class="btn btn-outline-light" href="index.php?controller=creation&action=addLecteur">Créer son compte</a>
                </div>
            </form>';
}
?>
