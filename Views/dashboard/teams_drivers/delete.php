<?php $title = "Team-eRacing - Associations Pilotes - Teams"; ?>

<div class="section-dashboard">

    <div class="section-header">
        <div class="category-title has-content section-title-crud big-line-height">
            <h2 class="dashboard-crud-title no-margin">
                Supprimer association
            </h2>
            <p class="dashboard-crud-subtitle">
                <?= htmlspecialchars($seasonName) ?> / 
                <?= htmlspecialchars($driverName) ?>  
                <?= htmlspecialchars($teamName) ?>
            </p>
        </div>
    </div>

    <h3 class="h3-delete">Voulez-vous vraiment supprimer ?</h3>

    <div class="delete-actions">
        <div class="delete-width">
            <form action="index.php?controller=teamsdrivers&action=delete&id=<?= $id ?>" method="POST">
                <?php
                use App\Core\Auth;
                $csrf = Auth::csrfToken();
                ?>
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                <button type="submit" class="btn red">Supprimer</button>
            </form>
        </div>

        <div class="annule-width">
            <a href="index.php?controller=teamsdrivers" class="btn black">Annuler</a>
        </div>
    </div>

</div>
