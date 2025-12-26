<?php $title = "Team-eRacing - Pénalités"; ?> 

<div class="section-dashboard">

    <div class="section-header">
        <div class="category-title has-content section-title-crud big-line-height">
            <h2 class="dashboard-crud-title no-margin">
                Supprimer pénalité
            </h2>
            <p class="dashboard-crud-subtitle">
                <?= htmlspecialchars($gps[$penalty->gp_id] ?? 'Inconnu') ?>
            </p>
            <p class="dashboard-crud-subtitle">
                <?php if (!empty($driverName) && $driverName !== 'Aucun') : ?>
                    <?= htmlspecialchars($driverName) ?> -
                <?php endif; ?>

                <?php if (!empty($teamName) && $teamName !== 'Aucun') : ?>
                    <?= htmlspecialchars($teamName) ?> -
                <?php endif; ?>

                <?= htmlspecialchars($penalty->points_removed) ?>
                points
            </p>
        </div>
    </div>

    <h3 class="h3-delete">Voulez-vous vraiment supprimer ?</h3>

    <div class="delete-actions">
        <div class="delete-width">
            <form action="index.php?controller=penalties&action=delete&id=<?= $id ?>" method="POST">
                <?php
                use App\Core\Auth;
                $csrf = Auth::csrfToken();
                ?>
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                <button type="submit" class="btn red">Supprimer</button>
            </form>
        </div>

        <div class="annule-width">
            <a href="index.php?controller=penalties" class="btn black">Annuler</a>
        </div>
    </div>

</div>

