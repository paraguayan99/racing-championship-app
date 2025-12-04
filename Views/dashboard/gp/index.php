<?php $title = 'Team-eRacing - Gestion des Grand Prix'; ?>

<?php if (!empty($message ?? '') && !empty($classMsg ?? '')): ?>
    <div class="<?= htmlspecialchars($classMsg ?? '') ?>">
        <?= htmlspecialchars($message ?? '') ?>
    </div>
<?php endif; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour au Dashboard</a>
        <h1>Gestion des Grand Prix des Saisons Actives</h1>
        <a class="nav-btn-dashboard" href="index.php?controller=gp&action=create">Ajouter un GP</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Saison</th>
                    <th>Ordre GP</th>
                    <th>Circuit</th>
                    <th>Pays</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $gp): ?>
                    <?php if (isset($gp->season_status) && $gp->season_status === 'active'): ?>
                        <tr>
                            <td><?= htmlspecialchars($gp->category ?? '') ?> - Saison <?= htmlspecialchars($gp->season_number ?? '') ?></td>
                            <td><?= htmlspecialchars($gp->gp_ordre ?? '') ?></td>
                            <td><?= htmlspecialchars($gp->circuit_name ?? '') ?></td>
                            <td><?= htmlspecialchars($gp->countryName ?? '') ?></td>
                            <td class="actions">
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

