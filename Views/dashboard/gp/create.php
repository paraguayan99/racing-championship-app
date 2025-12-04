<?php $title = 'Team-eRacing - Gestion des Grand Prix'; ?>

<div class="login-container">

    <a class="nav-btn-dashboard" href="index.php?controller=gp">Retour à la liste</a>

    <h1>Créer un Grand Prix</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?= $form->getFormElements(); ?>

</div>
