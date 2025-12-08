<?php $title = 'Team-eRacing - Résultats des GP'; ?>

<?php if (!empty($message ?? '') && !empty($classMsg ?? '')): ?>
    <div class="<?= htmlspecialchars($classMsg) ?>">
        <?= htmlspecialchars($message ?? '') ?>
    </div>
<?php endif; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour au Dashboard</a>
        <h1>Résultats des GP des Saisons actives</h1>
        <a class="nav-btn-dashboard" href="index.php?controller=gppoints&action=create">Ajouter Résultats d'un GP</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th></th>
                    <th>GP</th>
                    <th>Pilote</th>
                    <th>Team</th>
                    <th>Position</th>
                    <th>Points numériques</th>
                    <th>Points texte</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $currentGP = null;
                foreach ($list as $pt): 
                    // Identifier le GP unique
                    $gpKey = $pt->category_name . '_' . $pt->season_number . '_' . $pt->gp_ordre;

                    if ($gpKey !== $currentGP):
                        $currentGP = $gpKey;
                ?>
                <tr class="gp-summary" data-gp="<?= $gpKey ?>">
                    <td><button class="toggle-gp"><i class="fa-solid fa-arrow-down"></i></button></td>
                    <td colspan="7">
                        <?= htmlspecialchars($pt->category_name ?? '') ?> 
                        - Saison <?= htmlspecialchars($pt->season_number ?? '') ?> 
                        / GP <?= htmlspecialchars($pt->gp_ordre ?? '') ?> 
                        - <?= htmlspecialchars($pt->country_name ?? '') ?>
                    </td>
                </tr>
                <?php endif; ?>

                <tr class="gp-detail" data-gp="<?= $gpKey ?>" style="display:none;">
                    <td></td>
                    <td></td>
                    <td><?= htmlspecialchars($pt->driver_nickname ?? '') ?></td>
                    <td><?= htmlspecialchars($pt->team_name ?? '') ?></td>
                    <td><?= $pt->position === null ? '' : $pt->position ?></td>
                    <td><?= htmlspecialchars(rtrim(rtrim(number_format($pt->points_numeric, 1, '.', ''), '0'), '.')) ?></td>
                    <td><?= htmlspecialchars($pt->points_text ?? '') ?></td>
                    <td class="actions">
                        <a class="action-btn edit" href="index.php?controller=gppoints&action=update&id=<?= $pt->id ?>">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn delete" href="index.php?controller=gppoints&action=delete&id=<?= $pt->id ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
document.querySelectorAll('.toggle-gp').forEach(btn => {
    btn.addEventListener('click', function() {
        const tr = this.closest('tr');
        const gpKey = tr.dataset.gp;

        // Basculer l'affichage des détails
        document.querySelectorAll('.gp-detail[data-gp="'+gpKey+'"]').forEach(detail => {
            detail.style.display = detail.style.display === 'none' ? '' : 'none';
        });

        // Changer l'icône
        const icon = this.querySelector('i');
        if (icon.classList.contains('fa-arrow-down')) {
            icon.classList.remove('fa-arrow-down');
            icon.classList.add('fa-arrow-up');
        } else {
            icon.classList.remove('fa-arrow-up');
            icon.classList.add('fa-arrow-down');
        }
    });
});
</script>

