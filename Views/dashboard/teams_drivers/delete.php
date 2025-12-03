<?php $title = "Team-eRacing - Affectations Pilotes - Écuries"; ?>

<div class="section-dashboard">

    <h1>Supprimer affectation :
        <?= htmlspecialchars($seasonName) ?> / 
        <?= htmlspecialchars($driverName) ?>  
        <?= htmlspecialchars($teamName) ?>
    </h1>

    <p>Voulez-vous vraiment supprimer cette affectation ?</p>

    <div class="delete-actions">

    <form action="index.php?controller=teamsdrivers&action=delete&id=<?= $id ?>" method="POST">
        <?php
        use App\Core\Auth;
        $csrf = Auth::csrfToken();
        ?>
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <button type="submit" class="btn btn-danger">Supprimer</button>
    </form>

    <a href="index.php?controller=teamsdrivers" class="btn btn-light">Annuler</a>

    </div>

</div>
