<?php $title = 'Team-eRacing - GP - Résultats'; ?>

<?php if (!empty($message ?? '') && !empty($classMsg ?? '')): ?>
    <div class="<?= htmlspecialchars($classMsg) ?>">
        <?= htmlspecialchars($message ?? '') ?>
    </div>
<?php endif; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour Dashboard</a>

        <div class="category-title has-content section-title-crud">
            <h2 class="dashboard-crud-title no-margin">
                GP - Résultats
            </h2>
            <p class="dashboard-crud-subtitle">Compléter les résultats des Grands Prix</p>
        </div>

        <a class="nav-btn-dashboard" href="index.php?controller=gppoints&action=create">Ajouter résultats</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table table-th-responsive fix">
            <thead>
                <tr>
                    <th class="th-responsive width-reveal-gp">
                            <span class="label-aria">Voir résultats</span>
                            <span aria-hidden="true" class="label-long"></span>
                            <span aria-hidden="true" class="label-medium"></span>
                            <span aria-hidden="true" class="label-short"></span>
                    </th>
                    <th class="th-responsive">
                            <span class="label-aria">GP</span>
                            <span aria-hidden="true" class="label-long"></span>
                            <span aria-hidden="true" class="label-medium"></span>
                            <span aria-hidden="true" class="label-short"></span>
                    </th>
                    <th class="width-gp-points-main">Pilote</th>
                    <th class="width-gp-points-main">Team</th>
                    <th class="th-responsive width-numbers text-center">
                            <span class="label-aria">Position</span>
                            <span aria-hidden="true" class="label-long">Position</span>
                            <span aria-hidden="true" class="label-medium">Positi</span>
                            <span aria-hidden="true" class="label-short">Pos</span>
                    </th>
                    <th class="th-responsive width-numbers text-center">
                            <span class="label-aria">Points</span>
                            <span aria-hidden="true" class="label-long">Points</span>
                            <span aria-hidden="true" class="label-medium">Points</span>
                            <span aria-hidden="true" class="label-short">Pts</span>
                    </th>
                    <th class="th-responsive width-3-letters text-center">
                            <span class="label-aria">DNF / DNS / DSQ</span>
                            <span aria-hidden="true" class="label-long"></span>
                            <span aria-hidden="true" class="label-medium"></span>
                            <span aria-hidden="true" class="label-short"></span>
                    </th>
                    <th class="width-actions text-center">Actions</th>
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
                <tr data-gp="<?= $gpKey ?>">
                    <td class="width-reveal-gp text-center"><button class="btn-reveal-gp"><i class="fa-solid fa-arrow-down"></i></button></td>
                    <td colspan="7">
                        <?= htmlspecialchars($pt->category_name ?? '') ?> 
                        - Saison <?= htmlspecialchars($pt->season_number ?? '') ?> 
                        / GP <?= htmlspecialchars($pt->gp_ordre ?? '') ?> 
                        - <?= htmlspecialchars($pt->country_name ?? '') ?>
                    </td>
                </tr>
                <?php endif; ?>

                <tr class="gp-detail" data-gp="<?= $gpKey ?>" style="display:none;">
                    <td colspan="2"></td>
                    <td class="width-gp-results-main upside"><?= htmlspecialchars($pt->driver_nickname ?? '') ?></td>
                    <td class="width-gp-results-main upside"><?= htmlspecialchars($pt->team_name ?? '') ?></td>
                    <td class="width-numbers text-center"><?= $pt->position === null ? '' : $pt->position ?></td>
                    <td class="width-numbers text-center td-bold"><?= htmlspecialchars(rtrim(rtrim(number_format($pt->points_numeric, 1, '.', ''), '0'), '.')) ?></td>
                    <td class="width-3-letters text-center"><?= htmlspecialchars($pt->points_text ?? '') ?></td>
                    <td class="width-actions text-center">
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
document.querySelectorAll('.btn-reveal-gp').forEach(btn => {
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
            // Modifie la classe CSS de l'icône après ouverture
            this.classList.add('btn-reveal-gp-open');
        } else {
            icon.classList.remove('fa-arrow-up');
            icon.classList.add('fa-arrow-down');
            // Supprime la classe CSS de l'icône si ce n'est pas ouvert
            this.classList.remove('btn-reveal-gp-open');
        }
    });
});
</script>

