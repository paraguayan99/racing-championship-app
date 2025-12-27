<?php $title = 'Team-eRacing - Utilisateurs'; ?>

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
                Utilisateurs
            </h2>
            <p class="dashboard-crud-subtitle">Gérer les membres du site et leurs rôles</p>
        </div>

        <a class="nav-btn-dashboard" href="index.php?controller=users&action=create">Ajouter utilisateur</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Rôle</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user->role) ?></td>
                    <td><?= htmlspecialchars($user->email) ?></td>
                    <td class="actions">
                        <a class="action-btn edit" href="index.php?controller=users&action=update&id=<?= $user->id ?>">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn delete" href="index.php?controller=users&action=delete&id=<?= $user->id ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>