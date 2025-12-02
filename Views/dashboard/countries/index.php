<?php $title = 'Team-eRacing - Pays'; ?>

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
        <h1>Gestion des pays</h1>
        <a class="nav-btn-dashboard" href="index.php?controller=countries&action=create">Ajouter un nouveau pays</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Code</th>
                    <th>Drapeau</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $country): ?>
                <tr>
                    <td><?= htmlspecialchars($country->id) ?></td>
                    <td><?= htmlspecialchars($country->name) ?></td>
                    <td><?= htmlspecialchars($country->code ?? '') ?></td>
                    <td><?= htmlspecialchars($country->flag ?? '') ?></td>
                    <td class="actions">
                        <a class="action-btn edit" href="index.php?controller=countries&action=update&id=<?= $country->id ?>">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn delete" href="index.php?controller=countries&action=delete&id=<?= $country->id ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
