<?php $title = "Team-eRacing - GP - Pole Position & Fastest Lap"; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="/gpstats">Retour</a>

        <div class="category-title has-content section-title-crud">
            <h2 class="dashboard-crud-title no-margin title-ppfl">
                Modifier Pole Position & Fastest Lap
            </h2>
        </div>
    </div>

        <?= $form->getFormElements() ?>

</div>

<script>
// SCRIPT POUR AIDER A RESPECTER LE FORMAT DES CHRONOS
document.addEventListener('DOMContentLoaded', function() {
    const timeFields = [
        document.querySelector('input[name="pole_position_time"]'),
        document.querySelector('input[name="fastest_lap_time"]')
    ];

    // Regex strict pour vérifier m:ss.mmm
    const timePattern = /^\d+:[0-5]\d\.\d{3}$/;

    timeFields.forEach(field => {
        if (!field) return;

        field.addEventListener('input', () => {
            let val = field.value.replace(',', '.'); // remplacer virgule par point si besoin

            // Supprimer les caractères invalides
            val = val.replace(/[^0-9:\.]/g, '');

            // Ajouter automatiquement les ":" et "." si l'utilisateur tape juste des chiffres
            if (/^\d{1,2}$/.test(val)) {
                val = val; // minutes seules, pas de modification
            } else if (/^\d{1,2}\d{2}$/.test(val)) {
                // transformer "1125" => "1:12.5"
                val = val.replace(/^(\d+)(\d{2})(\d*)$/, '$1:$2.$3');
            } else if (/^\d+:\d{2}$/.test(val)) {
                // ajouter un point après les 2 secondes
                val = val + '.';
            }

            field.value = val;

            // Bord rouge si invalide
            if (!timePattern.test(field.value) && field.value !== '') {
                field.style.borderColor = 'red';
                field.setCustomValidity('Format invalide : m:ss.mmm (ex: 1:12.562)');
            } else {
                field.style.borderColor = '';
                field.setCustomValidity('');
            }
        });

        // Validation à la soumission
        field.form.addEventListener('submit', (e) => {
            if (!timePattern.test(field.value) && field.value !== '') {
                e.preventDefault();
                alert('Format invalide pour le temps : m:ss.mmm (ex: 1:12.562)');
                field.focus();
            }
        });
    });
});
</script>

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
