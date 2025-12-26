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
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>GP</th>
                    <th>Pilote</th>
                    <th>Team</th>
                    <th>Point(s) retiré(s)</th>
                    <th>Commentaire</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $penalty): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($penalty->category_name ?? '') ?>
                        - Saison <?= htmlspecialchars($penalty->season_number ?? '') ?>
                        / GP <?= htmlspecialchars($penalty->gp_ordre ?? '') ?>
                        - <?= htmlspecialchars($penalty->country_name ?? '') ?>
                    </td>
                    <td><?= htmlspecialchars($penalty->driver_nickname ?? '') ?></td>
                    <td><?= htmlspecialchars($penalty->team_name ?? '') ?></td>
                    <td><?= htmlspecialchars($penalty->points_removed ?? '') ?></td>
                    <td><?= htmlspecialchars($penalty->comment ?? '') ?></td>
                    <td class="actions">
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
