<?php $title = 'Team-eRacing - Catégories' ?>

<div class="login-container">

    <a class="nav-btn-dashboard" href="index.php?controller=categories">Retour à la liste</a>

    <h1>Créer une catégorie</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?= $form->getFormElements(); ?>

</div>
