<?php $title = "Team-eRacing - Utilisateurs" ?>

<div class="section-dashboard">

    <h1>Supprimer un utilisateur</h1>

    <p>Voulez-vous vraiment supprimer cet utilisateur ?</p>

    <div class="delete-actions">

    <form action="index.php?controller=users&action=delete&id=<?= $id ?>" method="POST">
        <?php
        // CSRF token
        use App\Core\Auth;
        $csrf = Auth::csrfToken();
        ?>
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <button type="submit" class="btn btn-danger">Supprimer</button>
    </form>

    <a href="index.php?controller=users" class="btn btn-light">Annuler</a>

    </div>

</div>
