<?php $title = "Team-eRacing - Ajouter Résultats d'un GP"; ?>

<div class="login-container">

    <a class="nav-btn-dashboard" href="index.php?controller=gppoints">Retour à la liste</a>

    <h1>Ajouter Résultats d'un GP</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?= $form->getFormElements(); ?>

</div>
