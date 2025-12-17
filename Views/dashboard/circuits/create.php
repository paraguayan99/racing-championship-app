<?php $title = 'Team-eRacing - Circuits' ?>

<div class="section-dashboard">

    <a class="nav-btn-dashboard" href="index.php?controller=circuits">Retour à la liste</a>

    <h1>Créer un circuit</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

        <?= $form->getFormElements(); ?>

</div>
