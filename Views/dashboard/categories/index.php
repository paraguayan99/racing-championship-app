<?php $title = 'Team-eRacing - Catégories'; ?>

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
                Catégories
            </h2>
            <p class="dashboard-crud-subtitle">Mettre en place leurs noms et leurs couleurs</p>
        </div>

        <a class="nav-btn-dashboard" href="index.php?controller=categories&action=create">Ajouter catégorie</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table fix">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th class="categories-color text-center">Couleur</th>
                    <th class="status text-center">Status</th>
                    <th class="width-actions text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $cat): ?>
                <tr>
                    <td class="down"><?= htmlspecialchars($cat->name) ?></td>
                    <td class="categories-color text-center down">
                        <span class="preview-color"
                                style="--preview-color: <?= htmlspecialchars($cat->color) ?>;">
                        </span>
                    </td>
                    <td class="status text-center down"><?= htmlspecialchars($cat->status) ?></td>
                    <td class="width-actions text-center">
                        <a class="action-btn edit" href="index.php?controller=categories&action=update&id=<?= $cat->id ?>">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn delete" href="index.php?controller=categories&action=delete&id=<?= $cat->id ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

