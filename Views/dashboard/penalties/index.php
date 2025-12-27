<?php $title = 'Team-eRacing - Pénalités'; ?>

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
                Pénalités
            </h2>
            <p class="dashboard-crud-subtitle">Appliquer au pilote et/ou l’équipe engagée sur un GP</p>
        </div>

        <a class="nav-btn-dashboard" href="index.php?controller=penalties&action=create">Ajouter pénalité</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table table-th-responsive fix">
            <thead>
                <tr>
                    <th class="name-gp-verylong text-center">GP</th>
                    <th>Pilote</th>
                    <th>Team</th>
                    <th class="th-responsive width-numbers text-center">
                        <span class="label-aria">Pénalité</span>
                            <span aria-hidden="true" class="label-long">Pénalité</span>
                            <span aria-hidden="true" class="label-medium">Pénalité</span>
                            <span aria-hidden="true" class="label-short">Pén</span>
                    </th>
                    <th class="th-responsive text-center">
                            <span class="label-aria">Commentaire</span>
                            <span aria-hidden="true" class="label-long">Commentaire</span>
                            <span aria-hidden="true" class="label-medium">Commentaire</span>
                            <span aria-hidden="true" class="label-short">Com</span>
                    </th>
                    <th class="width-actions text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $penalty): ?>
                <tr>
                    <td class="name-gp-verylong down">
                        <?= htmlspecialchars($penalty->category_name ?? '') ?> 
                        - S<?= htmlspecialchars($penalty->season_number ?? '') ?> 
                        / GP <?= htmlspecialchars($penalty->gp_ordre ?? '') ?>
                        <span class="country-code">
                            <?= htmlspecialchars($penalty->country_code ?? '') ?>
                            - <?= htmlspecialchars($penalty->circuit_name ?? '') ?>
                        </span>
                    </td>
                    <td class="text-long-responsive down"><?= htmlspecialchars($penalty->driver_nickname ?? '') ?></td>
                    <td class="text-long-responsive down"><?= htmlspecialchars($penalty->team_name ?? '') ?></td>
                    <td class="width-numbers text-center td-bold"><?= htmlspecialchars($penalty->points_removed ?? '') ?></td>
                    <td class="text-long-responsive down"><?= htmlspecialchars($penalty->comment ?? '') ?></td>
                    <td class="width-actions text-center">
                        <a class="action-btn edit" href="index.php?controller=penalties&action=update&id=<?= $penalty->id ?>">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn delete" href="index.php?controller=penalties&action=delete&id=<?= $penalty->id ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
