<?php $title = "Team-eRacing - Pénalités"; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="/penalties">Retour</a>

        <div class="category-title has-content section-title-crud">
            <h2 class="dashboard-crud-title no-margin">
                Ajouter pénalité
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