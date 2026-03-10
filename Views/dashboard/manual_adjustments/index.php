<?php $title = 'Team-eRacing - Ajustements manuels'; ?>

<!-- Définir des valeurs par défaut si les variables $message et $classMsg n'existent pas -->
<!-- Cela permet d'éviter les Warning PHP -->
<?php if (!empty($message ?? '') && !empty($classMsg ?? '')): ?>
    <div class="<?= htmlspecialchars($classMsg ?? '') ?>">
        <?= htmlspecialchars($message ?? '') ?>
    </div>
<?php endif; ?>

<div class="section-dashboard">

    <div class="section-header">
        <a class="nav-btn-dashboard" href="/dashboard">Retour Dashboard</a>

        <div class="category-title has-content section-title-crud">
            <h2 class="dashboard-crud-title no-margin">
                Ajustements manuels
            </h2>
            <p class="dashboard-crud-subtitle">Mettre à jour les classements pilotes et équipes sans publier les détails</p>
        </div>

        <a class="nav-btn-dashboard" href="/manualadjustments/create">Ajouter ajustement manuel</a>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table table-th-responsive fix">
            <thead>
                <tr>
                    <th class="width-small-info text-center">Saison</th>
                    <th>Pilote</th>
                    <th>Équipe</th>
                    <th class="th-responsive width-numbers text-center">
                            <span class="label-aria">Points</span>
                            <span aria-hidden="true" class="label-long">Points</span>
                            <span aria-hidden="true" class="label-medium">Points</span>
                            <span aria-hidden="true" class="label-short">Pts</span>
                    </th>
                    <th class="th-responsive text-center">
                            <span class="label-aria">Commentaire</span>
                            <span aria-hidden="true" class="label-long">Commentaire</span>
                            <span aria-hidden="true" class="label-medium">Commentaire</span>
                            <span aria-hidden="true" class="label-short">Com</span>
                    </th>
                    <th class="width-actions text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($list as $adj): ?>
                <tr>
                    <td class="width-small-info text-center down">
                        <?= htmlspecialchars($adj->category_name ?? '') ?> 
                        - S<?= htmlspecialchars($adj->season_number ?? '') ?></td>
                    <td class="down"><?= htmlspecialchars($adj->driver_nickname ?? '') ?></td>
                    <td class="down"><?= htmlspecialchars($adj->team_name ?? '') ?></td>
                    <td class="width-numbers text-center td-bold">
                        <?= htmlspecialchars(
                            rtrim(
                                rtrim(number_format($adj->points, 1, '.', ''), '0'),
                                '.'
                            )
                        ) ?>
                    </td>
                    <td class="down"><?= htmlspecialchars($adj->comment ?? '') ?></td>
                    <td class="width-actions text-center">
                        <a class="action-btn edit" href="/manualadjustments/update/<?= $adj->id ?>">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a class="action-btn delete" href="/manualadjustments/delete/<?= $adj->id ?>">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
