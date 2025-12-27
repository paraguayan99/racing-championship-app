<?php $title = 'Team-eRacing - Pilotes'; ?>

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
                Pilotes
            </h2>
            <p class="dashboard-crud-subtitle">Créer leurs pseudos et nationalités</p>
        </div>

        <a class="nav-btn-dashboard" href="index.php?controller=drivers&action=create">Ajouter pilote</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Pseudo</th>
                    <th>Pays</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $driver): ?>
                <tr>
                    <td><?= htmlspecialchars($driver->nickname) ?></td>
                    <td><?= htmlspecialchars($driver->country) ?></td>
                    <td><?= htmlspecialchars($driver->status) ?></td>
                    <td class="actions">
                        <a class="action-btn edit" href="index.php?controller=drivers&action=update&id=<?= $driver->id ?>">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn delete" href="index.php?controller=drivers&action=delete&id=<?= $driver->id ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
