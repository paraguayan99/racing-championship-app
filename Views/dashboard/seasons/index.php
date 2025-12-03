<?php $title = 'Team-eRacing - Saisons'; ?>

<!-- Définir des valeurs par défaut si les variables $message et $classMsg n'existent pas -->
<!-- Cela permet d'éviter les Warning PHP -->
<?php if (!empty($message ?? '') && !empty($classMsg ?? '')): ?>
    <div class="<?= htmlspecialchars($classMsg ?? '') ?>">
        <?= htmlspecialchars($message ?? '') ?>
    </div>
<?php endif; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour au Dashboard</a>
        <h1>Gestion des saisons</h1>
        <a class="nav-btn-dashboard" href="index.php?controller=seasons&action=create">Ajouter une nouvelle saison</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Numéro de saison</th>
                    <th>Catégorie</th>
                    <th>Jeu vidéo</th>
                    <th>Plateforme</th>
                    <th>Status</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $season): ?>
                <tr>
                    <td><?= htmlspecialchars($season->season_number) ?></td>
                    <td><?= htmlspecialchars($season->category ?? $season->category_id) ?></td>
                    <td><?= htmlspecialchars($season->videogame) ?></td>
                    <td><?= htmlspecialchars($season->platform) ?></td>
                    <td><?= htmlspecialchars($season->status) ?></td>
                    <td class="actions">
                        <a class="action-btn edit" href="index.php?controller=seasons&action=update&id=<?= $season->id ?>">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn delete" href="index.php?controller=seasons&action=delete&id=<?= $season->id ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
