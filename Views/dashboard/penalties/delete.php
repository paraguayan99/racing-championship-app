<?php $title = "Team-eRacing - Supprimer une Pénalité"; ?> 

<div class="section-dashboard">

    <h1>Supprimer la pénalité : <?= htmlspecialchars($gps[$penalty->gp_id] ?? 'Inconnu') ?></h1>

    <div>
        <?php if (!empty($driverName) && $driverName !== 'Aucun') : ?>
            <?= htmlspecialchars($driverName) ?> -
        <?php endif; ?>

        <?php if (!empty($teamName) && $teamName !== 'Aucun') : ?>
            <?= htmlspecialchars($teamName) ?> -
        <?php endif; ?>

        Point(s) retiré(s) :</strong> <?= htmlspecialchars($penalty->points_removed) ?>
    </div>

    <p class="warning-text">Voulez-vous vraiment supprimer cette pénalité ?</p>

    <div class="delete-actions">
        <form action="index.php?controller=penalties&action=delete&id=<?= $id ?>" method="POST">
            <?php
            use App\Core\Auth;
            $csrf = Auth::csrfToken();
            ?>
            <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>

        <a href="index.php?controller=penalties" class="btn btn-light">Annuler</a>
    </div>

</div>

