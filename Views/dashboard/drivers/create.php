<?php $title = 'Team-eRacing - Pilotes' ?>

<div class="section-dashboard">

    <a class="nav-btn-dashboard" href="index.php?controller=drivers">Retour à la liste</a>

    <h1>Créer un pilote</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

        <?= $form->getFormElements(); ?>

</div>
