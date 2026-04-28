<?php $title = "Team-eRacing - GP - Résultats"; ?>

<!-- Définir des valeurs par défaut si les variables $message et $classMsg n'existent pas -->
<!-- Cela permet d'éviter les Warning PHP -->
<?php if (!empty($message ?? '') && !empty($classMsg ?? '')): ?>
    <div class="<?= htmlspecialchars($classMsg) ?>">
        <?= htmlspecialchars($message ?? '') ?>
    </div>
<?php endif; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="/gppoints">Retour</a>

        <div class="category-title has-content section-title-crud">
            <h2 class="dashboard-crud-title no-margin">
                Ajouter résultats
            </h2>
        </div>
    </div>

    <?= $form->getFormElements(); ?>

</div>

<script>
// Redimensionnement du nom des GP dans le select psur mobile
// Nom original sauvegardé dans option.dataset.fullText si passage du mode portrait au mode paysage
function truncateGpSelect() {
    const select = document.querySelector('select[name="gp_id"]');
    if (!select) return;

    Array.from(select.options).forEach(option => {
        if (option.value === '') return;

        if (!option.dataset.fullText) {
            option.dataset.fullText = option.text;
        }

        if (window.innerWidth <= 700) {
            option.text = option.dataset.fullText.substring(0, 25);
        } else {
            option.text = option.dataset.fullText;
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    window.addEventListener('resize', truncateGpSelect);
    truncateGpSelect();
});
</script>