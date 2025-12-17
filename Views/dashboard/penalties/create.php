<?php $title = "Team-eRacing - Ajouter une Pénalité"; ?>

<div class="section-dashboard">

    <a class="nav-btn-dashboard" href="index.php?controller=penalties">Retour à la liste</a>

    <h1>Ajouter une Pénalité</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?= $form->getFormElements(); ?>

</div>
