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
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour Dashboard</a>

        <div class="category-title has-content section-title-crud">
            <h2 class="dashboard-crud-title no-margin">
                Saisons
            </h2>
            <p class="dashboard-crud-subtitle">Générer de nouvelles saisons pour une catégorie</p>
        </div>

        <a class="nav-btn-dashboard" href="index.php?controller=seasons&action=create">Ajouter saison</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table table-th-responsive fix">
            <thead>
                <tr>
                    <th class="width-numbers text-center">N°</th>
                    <th class="down">Catégorie</th>
                    <th class="text-center th-responsive">
                        <span class="label-aria">Jeu vidéo</span>
                        <span aria-hidden="true" class="label-long">Jeu vidéo</span>
                        <span aria-hidden="true" class="label-medium">Jeu vidéo</span>
                        <span aria-hidden="true" class="label-short">Jeu</span>
                    </th>
                    <th class="text-center th-responsive text-center">
                        <span class="label-aria">Console</span>
                        <span aria-hidden="true" class="label-long">Console</span>
                        <span aria-hidden="true" class="label-medium">Cons</span>
                        <span aria-hidden="true" class="label-short">Cons</span>
                    </th>
                    <th class="status text-center">Status</th>
                    <th class="width-actions text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $season): ?>
                <tr>
                    <td class="width-numbers text-center"><?= htmlspecialchars($season->season_number) ?></td>
                    <td class="down"><?= htmlspecialchars($season->category ?? $season->category_id) ?></td>
                    <td class="down text-center"><?= htmlspecialchars($season->videogame) ?></td>
                    <td class="down text-center"><?= htmlspecialchars($season->platform) ?></td>
                    <td class="status text-center down"><?= htmlspecialchars($season->status) ?></td>
                    <td class="width-actions text-center">
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
