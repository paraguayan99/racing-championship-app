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
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour Dashboard</a>

        <div class="category-title has-content section-title-crud">
            <h2 class="dashboard-crud-title no-margin">
                Pays
            </h2>
            <p class="dashboard-crud-subtitle">Associer ces pays ensuite aux pilotes, teams et circuits</p>
        </div>

        <a class="nav-btn-dashboard" href="index.php?controller=countries&action=create">Ajouter pays</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table fix">
            <thead>
                <tr>
                    <th class="width-code text-center">Code</th>
                    <th>Nom</th>
                    <th>Drapeau</th>
                    <th class="width-actions text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $country): ?>
                <tr>
                    <td class="width-code text-center"><?= htmlspecialchars($country->code ?? '') ?></td>
                    <td class="down"><?= htmlspecialchars($country->name) ?></td>
                    <td class="down">
                        <?php if (!empty($country->flag)): ?>
                            <img 
                                src="<?= htmlspecialchars($country->flag) ?>" 
                                alt="Drapeau <?= htmlspecialchars($country->name) ?>"
                                class="drivers-teams-flag">
                        <?php endif; ?>
                        
                        <?= htmlspecialchars($country->flag ?? '') ?></td>
                    <td class="width-actions text-center">
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
