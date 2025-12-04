<?php $title = "Team-eRacing - Gestion des Grand Prix"; ?>

<div class="section-dashboard">

    <h1>Supprimer le Grand Prix <?= htmlspecialchars($countryName) ?> -
        <?= htmlspecialchars($name) ?> /
        <?= htmlspecialchars($seasonName) ?>
    </h1>

    <p>Voulez-vous vraiment supprimer ce Grand Prix ?</p>

    <div class="delete-actions">

    <form action="index.php?controller=gp&action=delete&id=<?= $id ?>" method="POST">
        <?php
        use App\Core\Auth;
        $csrf = Auth::csrfToken();
        ?>
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <button type="submit" class="btn btn-danger">Supprimer</button>
    </form>

    <a href="index.php?controller=gp" class="btn btn-light">Annuler</a>

    </div>

</div>

