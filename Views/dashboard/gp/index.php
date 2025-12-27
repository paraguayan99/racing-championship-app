<?php $title = 'Team-eRacing - Calendriers'; ?>

<?php if (!empty($message ?? '') && !empty($classMsg ?? '')): ?>
    <div class="<?= htmlspecialchars($classMsg ?? '') ?>">
        <?= htmlspecialchars($message ?? '') ?>
    </div>
<?php endif; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour Dashboard</a>

        <div class="category-title has-content section-title-crud">
            <h2 class="dashboard-crud-title no-margin">
                Calendriers
            </h2>
            <p class="dashboard-crud-subtitle">Constituer les ordres d'apparition des Grands Prix durant la Saison</p>
        </div>

        <a class="nav-btn-dashboard" href="index.php?controller=gp&action=create">Ajouter GP</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table fix">
            <thead>
                <tr>
                    <th class="width-small-info text-center">Saison</th>
                    <th class="width-numbers text-center">N°</th>
                    <th>Circuit</th>
                    <th>Pays</th>
                    <th class="width-actions text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $gp): ?>
                    <?php if (isset($gp->season_status) && $gp->season_status === 'active'): ?>
                        <tr>
                            <td class="width-small-info text-center upside"><?= htmlspecialchars($gp->category ?? '') ?> - S<?= htmlspecialchars($gp->season_number ?? '') ?></td>
                            <td class="width-numbers text-center"><?= htmlspecialchars($gp->gp_ordre ?? '') ?></td>
                            <td class="upside"><?= htmlspecialchars($gp->circuit_name ?? '') ?></td>
                            <td class="upside"><?= htmlspecialchars($gp->countryName ?? '') ?></td>
                            <td class="width-actions text-center">
                                <a class="action-btn edit" href="index.php?controller=gp&action=update&id=<?= $gp->id ?>">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a class="action-btn delete" href="index.php?controller=gp&action=delete&id=<?= $gp->id ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

