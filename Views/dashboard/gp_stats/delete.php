<?php $title = "Team-eRacing - Supprimer Statistiques d'un GP"; ?>

<div class="section-dashboard">

    <h1>Supprimer statistiques :
        <?= htmlspecialchars($gpName) ?>
    </h1>

    <p class="warning-text">Voulez-vous vraiment supprimer les statistiques de ce GP ?</p>

    <div class="delete-actions">
        <form action="index.php?controller=gpstats&action=delete&gp_id=<?= $id ?>" method="POST">
            <?php
            use App\Core\Auth;
            $csrf = Auth::csrfToken();
            ?>
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>

        <a href="index.php?controller=gpstats" class="btn btn-light">Annuler</a>
    </div>

</div>
