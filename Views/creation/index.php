<?php
session_start();

// var_dump($_SESSION);

$title = 'Bibliothèque - Liste des livres'; 
?>

<h2>Liste des livres</h2>

<?php
// Détournement de session -> Test le temps de connexion de 5 minutes maximum, il faudra s'identifier à nouveau
$timestamp = time() - (5 * 60);

// Sécurité -> Test login, token et durée de session
if (isset($_SESSION["login"]) && isset($_SESSION['token']) && $_SESSION['token_time'] > $timestamp) {

    // On teste si l'utilisateur a les droits d'administration et fait parti de la bibliotheque
    if ($_SESSION["admin"] == "admin1234") {
        echo '<div class="container">
                <div class="col-12 rounded justify-content-end text-center text-white bg-secondary">
                    Vous êtes connecté (<b>' .$_SESSION['prenom'] .' ' .$_SESSION['nom'] .' - ' .$_SESSION['login'] .'</b>)
                        <button type="button" class="btn btn-primary"><a class="text-white" href="../Views/deconnexion.php">Me déconnecter</a></button>
                        <button type="button" class="btn btn-warning">ADMIN</button>
                </div>
                
                <a href="index.php?controller=creation&action=add"><button type="button" class="mt-2 btn btn-primary">Ajouter un livre</button></a>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Auteur</th>
                            <th scope="col" class="text-center">Emprunté le</th>
                            <th scope="col" class="text-center">Par le lecteur n°</th>
                            <th scope="col" class="text-center">Retour avant le</th>
                        </tr>
                    </thead>
                    <tbody>';

                        // On boucle dans le tableau $list qui contient la liste des créations
                        foreach ($list as $value) {
                            echo "<tr>";
                            echo "<td>" .$value->id_livre ."</td>";
                            echo "<td>" .$value->titre ."</td>";
                            echo "<td>" .$value->auteur ."</td>";
                            echo "<td class='text-center'>" .$value->date_emprunt ."</td>";
                            echo "<td class='text-center'>" .$value->id_lecteur ."</td>";
                            echo "<td class='text-center'>" .$value->date_retour ."</td>";
                            echo "<td><a href='index.php?controller=creation&action=updateCreation&id=$value->id_livre'><i class='fas fa-pen'></i></a></td>";
                            echo "<td><a href='index.php?controller=creation&action=deleteCreation&id=$value->id_livre'><i class='fas fa-trash-alt'></i></a></td>";
                            echo "</tr>";
                        }

                    echo '</tbody>
                </table>';} else {
                // Si l'utilisateur est un lecteur simple et n'a pas les droits d'administration
                echo '<div class="container">
                <div class="col-12 rounded justify-content-end text-center text-white bg-secondary">
                    Vous êtes connecté (<b>' .$_SESSION['prenom'] .' ' .$_SESSION['nom'] .' - ' .$_SESSION['login'] .'</b>)
                        <button type="button" class="btn btn-dark"><a class="text-white" href="../Views/deconnexion.php">Me déconnecter</a></button>
                </div>';

                // Message en rouge pour avertir si la personne a déjà emprunté 3 livres
                if(isset($_SESSION['ThreeBooks']) && $_SESSION['ThreeBooks'] == true) {
                    echo '<div class="col-12 rounded mt-2 py-3 justify-content-end text-center text-white fw-bold bg-danger">
                    <i class="fa-solid fa-triangle-exclamation"></i> Vous avez déjà emprunté 3 livres, merci de les rapporter si vous souhaitez en lire d\'autres <i class="fa-solid fa-triangle-exclamation"></i>
                </div>';
                }

                echo '<table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Titre</th>
                            <th scope="col">Auteur</th>
                            <th scope="col" class="text-center">De retour au plus tard le</th>
                            <th scope="col" class="text-center">Emprunter</th>
                        </tr>
                    </thead>
                    <tbody>';

                        // On boucle dans le tableau $list qui contient la liste des créations
                        foreach ($list as $value) {
                            echo "<tr>";
                            echo "<td>" .$value->titre ."</td>";
                            echo "<td>" .$value->auteur ."</td>";


                            if ($value->id_lecteur == $_SESSION['id_lecteur']) {
                                // Icone signifiant au lecteur que c'est lui qui possède ce livre
                                echo "<td class='text-primary text-center'> <i class='fa-solid fa-cart-shopping'></i> > </i>" 
                                        .$value->date_retour ."</td>";
                            } else {
                                echo "<td class='text-center'>" .$value->date_retour ."</td>";
                            }

                            if (empty($value->date_retour)) {
                                // Si date_retour n'existe pas ou est vide, on affiche le lien pour emprunter le livre
                                echo "<td class='text-center'><a href='index.php?controller=creation&action=updateEmprunt&id=$value->id_livre'><i class='fa-solid fa-cart-plus'></i></a></td>";
                            } else {
                                // Sinon, on affiche pas l'icone d'emprunt
                                echo "<td></td>";
                            }
                        }

                    echo '</tbody>
                </table>';
                }
} elseif (isset($_SESSION["errorConnect"])){
    // Si les identifiants sont incorrectes, message d'erreur et bouton pour réessayer la connexion
    echo '<form action="../Views/cible.php" method="post" class="container">
                <div class="col-12 rounded justify-content-end text-center bg-secondary bg-danger text-white">
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
