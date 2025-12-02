<?php $title = 'Team-eRacing - Saisons' ?>

<div class="login-container">

    <a class="nav-btn-dashboard" href="index.php?controller=seasons">Retour à la liste</a>

    <h1>Créer une saison</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?= $form->getFormElements(); ?>

</div>
