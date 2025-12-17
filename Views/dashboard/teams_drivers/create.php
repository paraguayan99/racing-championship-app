<?php $title = 'Team-eRacing - Affectations Pilotes - Écuries'; ?>

<div class="section-dashboard">

    <a class="nav-btn-dashboard" href="index.php?controller=teamsdrivers">Retour à la liste</a>

    <h1>Créer une affectation</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?= $form->getFormElements(); ?>

</div>
