<?php $title = 'Team-eRacing - Pays' ?>

<div class="login-container">

    <a class="nav-btn-dashboard" href="index.php?controller=countries">Retour à la liste</a>

    <h1>Créer un pays</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

        <?= $form->getFormElements(); ?>

</div>
