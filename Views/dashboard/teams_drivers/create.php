<?php $title = 'Team-eRacing - Associations Pilotes - Teams'; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=teamsdrivers">Retour</a>

        <div class="category-title has-content section-title-crud">
            <h2 class="dashboard-crud-title no-margin">
                Ajouter association
            </h2>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?= $form->getFormElements(); ?>

</div>
