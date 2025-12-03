<?php $title = 'Team-eRacing - Ajustements manuels' ?>

<div class="login-container">

    <a class="nav-btn-dashboard" href="index.php?controller=manualadjustments">Retour à la liste</a>

    <h1>Créer un ajustement</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

        <?= $form->getFormElements(); ?>

</div>
