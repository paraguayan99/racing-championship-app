<?php $title = 'Team-eRacing - Affectations Pilotes - Écuries'; ?>

<?php if (!empty($message ?? '') && !empty($classMsg ?? '')): ?>
    <div class="<?= htmlspecialchars($classMsg ?? '') ?>">
        <?= htmlspecialchars($message ?? '') ?>
    </div>
<?php endif; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour au Dashboard</a>
        <h1>Gestion des affectations Pilote - Écurie des Saisons Actives</h1>
        <a class="nav-btn-dashboard" href="index.php?controller=teamsdrivers&action=create">Ajouter une affectation</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Saison</th>
                    <th>Pilote</th>
                    <th>Écurie</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $td): ?>
                    <?php if (isset($td->season_status) && $td->season_status === 'active'): ?>
                        <tr>
                            <td><?= htmlspecialchars($td->category_name ?? '') ?> - Saison <?= htmlspecialchars($td->season_number ?? '') ?></td>
                            <td><?= htmlspecialchars($td->driver) ?></td>
                            <td><?= htmlspecialchars($td->team) ?></td>
                            <td class="actions">
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