<?php $title = 'Team-eRacing - Circuits'; ?>

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
        <h1>Gestion des circuits</h1>
        <a class="nav-btn-dashboard" href="index.php?controller=circuits&action=create">Ajouter un nouveau circuit</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Pays</th>
                    <th>Statut</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $circuit): ?>
                <tr>
                    <td><?= htmlspecialchars($circuit->id) ?></td>
                    <td><?= htmlspecialchars($circuit->name) ?></td>
                    <td><?= htmlspecialchars($circuit->country) ?></td>
                    <td><?= htmlspecialchars($circuit->status) ?></td>
                    <td class="actions">
                        <a class="action-btn edit" href="index.php?controller=circuits&action=update&id=<?= $circuit->id ?>">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn delete" href="index.php?controller=circuits&action=delete&id=<?= $circuit->id ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
