<?php $title = "Team-eRacing - Ajouter Statistiques d'un GP"; ?>

<div class="section-dashboard">

    <a class="nav-btn-dashboard" href="index.php?controller=gpstats">Retour à la liste</a>

    <h1>Ajouter Statistiques d'un GP</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= htmlspecialchars($classMsg) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?= $form->getFormElements(); ?>

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
                // transformer "11234" => "1:12.34" (approximation)
                val = val.replace(/^(\d+)(\d{2})(\d{0,3})$/, '$1:$2.$3');
            } else if (/^\d+:\d{1,2}$/.test(val)) {
                // ajouter un point à la fin si pas présent
                val = val + '.000';
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

