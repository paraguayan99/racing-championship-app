<?php $title = "Team-eRacing - Supprimer Résultats d'un GP"; ?>

<div class="section-dashboard">

    <h1>Supprimer résultat :
        <?= htmlspecialchars($gpName) ?>
        ( <?= htmlspecialchars($driverName) ?> -
        <?= htmlspecialchars($teamName) ?> )
    </h1>

    <p class="warning-text">Voulez-vous vraiment supprimer le résultat de ce GP ?</p>

    <div class="delete-actions">
        <form action="index.php?controller=gppoints&action=delete&id=<?= $id ?>" method="POST">
            <?php
            use App\Core\Auth;
            $csrf = Auth::csrfToken();
            ?>
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>

        <a href="index.php?controller=gppoints" class="btn btn-light">Annuler</a>
    </div>

</div>
