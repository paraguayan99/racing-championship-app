<?php $title = "Team-eRacing - Pilotes" ?>

<div class="section-dashboard">

    <h1>Supprimer pilote : <?= $nickname ?></h1>

    <p>Voulez-vous vraiment supprimer ce pilote ?</p>

    <div class="delete-actions">

    <form action="index.php?controller=drivers&action=delete&id=<?= $id ?>" method="POST">
        <?php
        // CSRF token
        use App\Core\Auth;
        $csrf = Auth::csrfToken();
        ?>
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <button type="submit" class="btn btn-danger">Supprimer</button>
    </form>

    <a href="index.php?controller=drivers" class="btn btn-light">Annuler</a>

    </div>

</div>