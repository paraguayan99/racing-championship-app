<?php $title = 'Team-eRacing - Utilisateurs' ?>

<div class="login-container">

    <a class="nav-btn-dashboard" href="index.php?controller=users">Retour à la liste</a>

    <h1>Créer un utilisateur</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

        <?= $form->getFormElements(); ?>

</div>

