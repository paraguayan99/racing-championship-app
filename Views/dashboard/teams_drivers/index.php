<?php $title = 'Team-eRacing - Associations Pilotes - Teams'; ?>

<?php if (!empty($message ?? '') && !empty($classMsg ?? '')): ?>
    <div class="<?= htmlspecialchars($classMsg ?? '') ?>">
        <?= htmlspecialchars($message ?? '') ?>
    </div>
<?php endif; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour Dashboard</a>

        <div class="category-title has-content section-title-crud">
            <h2 class="dashboard-crud-title no-margin title-teams-drivers">
                Associations Pilotes-Teams
            </h2>
            <p class="dashboard-crud-subtitle">Insérer teams au classement pilotes sans impacter le constructeur</p>
        </div>

        <a class="nav-btn-dashboard" href="index.php?controller=teamsdrivers&action=create">Ajouter association</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table fix">
            <thead>
                <tr>
                    <th class="width-small-info text-center">Saison</th>
                    <th>Pilote</th>
                    <th>Écurie</th>
                    <th class="width-actions text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $td): ?>
                    <?php if (isset($td->season_status) && $td->season_status === 'active'): ?>
                        <tr>
                            <td class="width-small-info text-center upside"><?= htmlspecialchars($td->category_name ?? '') ?> - S<?= htmlspecialchars($td->season_number ?? '') ?></td>
                            <td class="upside"><?= htmlspecialchars($td->driver) ?></td>
                            <td class="upside"><?= htmlspecialchars($td->team) ?></td>
                            <td class="width-actions text-center">
                                <a class="action-btn edit" href="index.php?controller=teamsdrivers&action=update&id=<?= $td->id ?>">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <a class="action-btn delete" href="index.php?controller=teamsdrivers&action=delete&id=<?= $td->id ?>">
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