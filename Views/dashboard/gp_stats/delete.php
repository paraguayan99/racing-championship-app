<?php $title = "Team-eRacing - GP - Pole Position & Fastest Lap"; ?>

<div class="section-dashboard">

    <div class="section-header">
        <div class="category-title has-content section-title-crud big-line-height">
            <h2 class="dashboard-crud-title no-margin title-ppfl ppfl-delete">
                Supprimer Pole Position & Fastest Lap
            </h2>
            <p class="dashboard-crud-subtitle">
                <?= htmlspecialchars($gpName) ?>
            </p>
        </div>
    </div>

    <h3 class="h3-delete">Voulez-vous vraiment supprimer ?</h3>

    <div class="delete-actions">
        <div class="delete-width">
            <form action="index.php?controller=gpstats&action=delete&gp_id=<?= $id ?>" method="POST">
                <?php
                use App\Core\Auth;
                $csrf = Auth::csrfToken();
                ?>
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                <button type="submit" class="btn red">Supprimer</button>
            </form>
        </div>

        <div class="annule-width">
            <a href="index.php?controller=gpstats" class="btn black">Annuler</a>
        </div>
    </div>

</div>
