<?php $title = "Team-eRacing - Ajustements manuels" ?>

<div class="section-dashboard">

    <h1>Supprimer ajustement :
        <?= htmlspecialchars($seasonName) ?> / 
        <?= htmlspecialchars($driverName) ?>  
        <?= htmlspecialchars($teamName) ?>
    </h1>

    <p>Voulez-vous vraiment supprimer cet ajustement ?</p>

    <div class="delete-actions">

    <form action="index.php?controller=manualadjustments&action=delete&id=<?= $id ?>" method="POST">
        <?php
        use App\Core\Auth;
        $csrf = Auth::csrfToken();
        ?>
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <button type="submit" class="btn btn-danger">Supprimer</button>
    </form>

    <a href="index.php?controller=manualadjustments" class="btn btn-light">Annuler</a>

    </div>

</div>
