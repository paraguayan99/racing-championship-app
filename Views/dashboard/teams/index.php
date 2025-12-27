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
        <table class="dashboard-table fix">
            <thead>
                <tr>
                    <th class="teams-logo text-center down">Logo</th>
                    <th class="teams-color text-center down">Couleur</th>
                    <th>Nom</th>
                    <th>Pays</th>
                    <th class="status text-center">Statut</th>
                    <th class="width-actions text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $team): ?>
                <tr>
                    <td class="teams-logo text-center down">
                        <?php if (!empty($team->logo ?? '')): ?>
                            <img 
                                src="<?= htmlspecialchars($team->logo) ?>" 
                                alt="logo"
                                class="preview-logo">
                        <?php endif; ?>
                    </td>
                    <td class="text-center down">
                        <span class="preview-color"
                                style="--preview-color: <?= htmlspecialchars($team->color ?? '') ?>;">
                        </span>
                    </td>
                    <td class="down"><?= htmlspecialchars($team->name) ?></td>
                    <td class="down"><?= htmlspecialchars($team->country) ?></td>
                    <td class="status text-center down"><?= htmlspecialchars($team->status) ?></td>
                    <td class="width-actions text-center">
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
