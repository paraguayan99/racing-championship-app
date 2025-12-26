<?php $title = 'Team-eRacing - Ajustements manuels'; ?>

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
                Ajustements manuels
            </h2>
            <p class="dashboard-crud-subtitle">Mettre à jour les classements pilotes et équipes sans publier les détails</p>
        </div>

        <a class="nav-btn-dashboard" href="index.php?controller=manualadjustments&action=create">Ajouter ajustement manuel</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Saison</th>
                    <th>Pilote</th>
                    <th>Équipe</th>
                    <th>Points</th>
                    <th>Commentaires</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $adj): ?>
                <tr>
                    <td><?= htmlspecialchars($adj->category_name ?? '') ?> - Saison <?= htmlspecialchars($adj->season_number ?? '') ?></td>
                    <td><?= htmlspecialchars($adj->driver_nickname ?? '') ?></td>
                    <td><?= htmlspecialchars($adj->team_name ?? '') ?></td>
                    <td>
                        <?= htmlspecialchars(
                            rtrim(
                                rtrim(number_format($adj->points, 1, '.', ''), '0'),
                                '.'
                            )
                        ) ?>
                    </td>
                    <td><?= htmlspecialchars($adj->comment ?? '') ?></td>
                    <td class="actions">
                        <a class="action-btn edit" href="index.php?controller=manualadjustments&action=update&id=<?= $adj->id ?>">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn delete" href="index.php?controller=manualadjustments&action=delete&id=<?= $adj->id ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
