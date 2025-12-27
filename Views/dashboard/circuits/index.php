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
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour Dashboard</a>

        <div class="category-title has-content section-title-crud">
            <h2 class="dashboard-crud-title no-margin">
                Circuits
            </h2>
            <p class="dashboard-crud-subtitle">Etablir leurs noms et pays</p>
        </div>

        <a class="nav-btn-dashboard" href="index.php?controller=circuits&action=create">Ajouter circuit</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table fix">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Pays</th>
                    <th class="status text-center">Statut</th>
                    <th class="width-actions text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $circuit): ?>
                <tr>
                    <td class="down"><?= htmlspecialchars($circuit->name) ?></td>
                    <td class="down"><?= htmlspecialchars($circuit->country) ?></td>
                    <td class="status text-center down"><?= htmlspecialchars($circuit->status) ?></td>
                    <td class="width-actions text-center">
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
