<?php $title = 'Team-eRacing - GP - Pole Position & Fastest Lap'; ?>

<?php if (!empty($message ?? '') && !empty($classMsg ?? '')): ?>
    <div class="<?= htmlspecialchars($classMsg ?? '') ?>">
        <?= htmlspecialchars($message ?? '') ?>
    </div>
<?php endif; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour Dashboard</a>

        <div class="category-title has-content section-title-crud">
            <h2 class="dashboard-crud-title no-margin title-ppfl">
                GP - Pole Position & Fastest Lap
            </h2>
            <p class="dashboard-crud-subtitle">Joindre les noms des pilotes et leurs chronos</p>
        </div>

        <a class="nav-btn-dashboard" href="index.php?controller=gpstats&action=create">Ajouter Pole Position & Fastest Lap</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table table-th-responsive fix">
            <thead>
                <tr>
                    <th class="name-gp-verylong text-center">GP</th>
                    <th class="upside">Pole Position</th>
                    <th class="width-chrono"></th>
                    <th class="upside">Fastest Lap</th>
                    <th class="width-chrono"></th>
                    <th class="width-actions text-center">Actions</th>
                </tr>
                <tr>
                    <th class="name-gp-verylong"></th>
                    <th>Pilote</th>
                    <th class="th-responsive width-chrono text-center">
                            <span class="label-aria">Chrono</span>
                            <span aria-hidden="true" class="label-long">Chrono</span>
                            <span aria-hidden="true" class="label-medium">Chrono</span>
                            <span aria-hidden="true" class="label-short">Chro</span>
                    </th>
                    <th>Pilote</th>
                    <th class="th-responsive width-chrono text-center">
                            <span class="label-aria">Chrono</span>
                            <span aria-hidden="true" class="label-long">Chrono</span>
                            <span aria-hidden="true" class="label-medium">Chrono</span>
                            <span aria-hidden="true" class="label-short">Chro</span>
                    </th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $stat): ?>
                <tr>
                    <td class="name-gp-verylong down">
                        <?= htmlspecialchars($stat->category_name ?? '') ?> 
                        - S<?= htmlspecialchars($stat->season_number ?? '') ?> 
                        / GP <?= htmlspecialchars($stat->gp_ordre ?? '') ?>
                        <span class="country-code">
                            <?= htmlspecialchars($stat->country_code ?? '') ?>
                            - <?= htmlspecialchars($stat->circuit_name ?? '') ?>
                        </span>
                    </td>
                    <td class="text-long-responsive down"><?= htmlspecialchars($stat->pole_driver_name ?? '') ?></td>
                    <td class="width-chrono text-center">
                        <span class="badge-purple-dashboard">
                            <?= htmlspecialchars($stat->pole_position_time ?? '') ?>
                        </span>
                    </td>
                    <td class="text-long-responsive down"><?= htmlspecialchars($stat->fl_driver_name ?? '') ?></td>
                    <td class="width-chrono text-center">
                        <span class="badge-purple-dashboard">
                            <?= htmlspecialchars($stat->fastest_lap_time ?? '') ?>
                        </span>
                    </td>
                    <td class="width-actions text-center">
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
