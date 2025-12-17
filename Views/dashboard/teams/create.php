<?php $title = 'Team-eRacing - Équipes' ?>

<div class="section-dashboard">

    <a class="nav-btn-dashboard" href="index.php?controller=teams">Retour à la liste</a>

    <h1>Créer une équipe</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

        <?= $form->getFormElements(); ?>

</div>
