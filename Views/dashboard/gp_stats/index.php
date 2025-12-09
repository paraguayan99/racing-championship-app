<?php $title = 'Team-eRacing - Statistiques des GP'; ?>

<?php if (!empty($message ?? '') && !empty($classMsg ?? '')): ?>
    <div class="<?= htmlspecialchars($classMsg ?? '') ?>">
        <?= htmlspecialchars($message ?? '') ?>
    </div>
<?php endif; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour au Dashboard</a>
        <h1>Statistiques des GP des Saisons Actives</h1>
        <a class="nav-btn-dashboard" href="index.php?controller=gpstats&action=create">Ajouter Statistiques d'un GP</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>GP</th>
                    <th>Pole Position - Pilote</th>
                    <th>Pole Position - Temps</th>
                    <th>Meilleur Tour - Pilote</th>
                    <th>Meilleur Tour - Temps</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $stat): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($stat->category_name ?? '') ?> 
                        - Saison <?= htmlspecialchars($stat->season_number ?? '') ?> 
                        / GP <?= htmlspecialchars($stat->gp_ordre ?? '') ?> 
                        - <?= htmlspecialchars($stat->country_name ?? '') ?> 
                        - <?= htmlspecialchars($stat->circuit_name ?? '') ?>
                    </td>
                    <td><?= htmlspecialchars($stat->pole_driver_name ?? '') ?></td>
                    <td><?= htmlspecialchars($stat->pole_position_time ?? '') ?></td>
                    <td><?= htmlspecialchars($stat->fl_driver_name ?? '') ?></td>
                    <td><?= htmlspecialchars($stat->fastest_lap_time ?? '') ?></td>
                    <td class="actions">
                        <a class="action-btn edit" href="index.php?controller=gpstats&action=update&gp_id=<?= $stat->gp_id ?>">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn delete" href="index.php?controller=gpstats&action=delete&gp_id=<?= $stat->gp_id ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
