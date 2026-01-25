<?php $title = "Team-eRacing - Calendriers"; ?>

<div class="section-dashboard">

    <div class="section-header">
        <div class="category-title has-content section-title-crud big-line-height">
            <h2 class="dashboard-crud-title no-margin">
                Supprimer GP 
            </h2>
            <p class="dashboard-crud-subtitle"><?= htmlspecialchars($countryName) ?> - <?= htmlspecialchars($name) ?> / <?= htmlspecialchars($seasonName) ?></p>
        </div>
    </div>

    <h3 class="h3-delete">Voulez-vous vraiment supprimer ?</h3>

    <div class="delete-actions">
        <div class="delete-width">
            <form action="/gp/delete/<?= $id ?>" method="POST">
                <?php
                use App\Core\Auth;
                $csrf = Auth::csrfToken();
                ?>
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                <button type="submit" class="btn red">Supprimer</button>
            </form>
        </div>

        <div class="annule-width">
            <a href="/gp" class="btn black">Annuler</a>
        </div>
    </div>

</div>

