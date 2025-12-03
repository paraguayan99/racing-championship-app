<?php $title = "Team-eRacing - Saisons" ?>

<div class="section-dashboard">

    <h1>Supprimer saison : n°<?= $season_number ?> de <?= $category_name ?> </h1>

    <p>Voulez-vous vraiment supprimer cette saison ?</p>

    <div class="delete-actions">

        <form action="index.php?controller=seasons&action=delete&id=<?= $id ?>" method="POST">
            <?php
            use App\Core\Auth;
            $csrf = Auth::csrfToken();
            ?>
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>

        <a href="index.php?controller=seasons" class="btn btn-light">Annuler</a>

    </div>

</div>
