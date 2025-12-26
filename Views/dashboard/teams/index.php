<?php $title = 'Team-eRacing - Teams'; ?>

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
                Teams
            </h2>
            <p class="dashboard-crud-subtitle">Attribuer leurs noms, logos, couleurs et nationalités</p>
        </div>

        <a class="nav-btn-dashboard" href="index.php?controller=teams&action=create">Ajouter team</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Logo</th>
                    <th>Couleur</th>
                    <th>Pays</th>
                    <th>Statut</th>
                    <th class="actions-column">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $team): ?>
                <tr>
                    <td><?= htmlspecialchars($team->name) ?></td>
                    <td>
                        <?php if (!empty($team->logo ?? '')): ?>
                            <img src="<?= htmlspecialchars($team->logo) ?>" alt="logo" style="height:40px;">
                        <?php endif; ?>
                    </td>
                    <td>
                        <span style="display:inline-block;
                                    width:20px;
                                    height:20px;
                                    background-color:<?= htmlspecialchars($team->color ?? '') ?>;
                                    border-radius:4px;">
                        </span>
                        <?= htmlspecialchars($team->color ?? '') ?>
                    </td>
                    <td><?= htmlspecialchars($team->country) ?></td>
                    <td><?= htmlspecialchars($team->status) ?></td>
                    <td class="actions">
                        <a class="action-btn edit" href="index.php?controller=teams&action=update&id=<?= $team->id ?>">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn delete" href="index.php?controller=teams&action=delete&id=<?= $team->id ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
