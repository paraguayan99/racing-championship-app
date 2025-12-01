<?php $title = "Supprimer l'utilisateur" ?>

<div class="section-dashboard">

    <h1>Supprimer l’utilisateur</h1>

    <p>Voulez-vous vraiment supprimer cet utilisateur ?</p>

    <form action="index.php?controller=users&action=delete&id=<?= $id ?>" method="POST">
        <?php
        // CSRF token
        use App\Core\Auth;
        $csrf = Auth::csrfToken();
        ?>
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <button type="submit" style="background:red;color:white;padding:10px">Supprimer</button>
    </form>

    <br>
    <a href="index.php?controller=users">Annuler</a>

</div>
