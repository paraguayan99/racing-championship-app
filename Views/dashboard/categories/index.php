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
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour au Dashboard</a>
        <h1>Gestion des catégories</h1>
        <a class="nav-btn-dashboard" href="index.php?controller=categories&action=create">Ajouter une nouvelle catégorie</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Status</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $cat): ?>
                <tr>
                    <td><?= htmlspecialchars($cat->name) ?></td>
                    <td><?= htmlspecialchars($cat->status) ?></td>
                    <td class="actions">
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

