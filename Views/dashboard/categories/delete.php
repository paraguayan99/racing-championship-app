<?php $title = "Team-eRacing - Catégories" ?>

<div class="section-dashboard">

    <h1>Supprimer une catégorie</h1>

    <p>Voulez-vous vraiment supprimer cette catégorie ?</p>

    <div class="delete-actions">

        <form action="index.php?controller=categories&action=delete&id=<?= $id ?>" method="POST">
            <?php
            // CSRF token
            use App\Core\Auth;
            $csrf = Auth::csrfToken();
            ?>
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>

        <a href="index.php?controller=categories" class="btn btn-light">Annuler</a>

    </div>

</div>
