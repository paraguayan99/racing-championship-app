<?php
// Fonction pour les badges podium
function podiumBadge($pos) {

    if ($pos === null || $pos === 0) {
        return '<span class="badge badge-normal badge-empty">-</span>';
    }

    return match($pos) {
        1 => '<span class="badge badge-gold">1</span>',
        2 => '<span class="badge badge-silver">2</span>',
        3 => '<span class="badge badge-bronze">3</span>',
        default => '<span class="badge badge-normal">' . $pos . '</span>',
    };
}
?>

<?php if ($gp): ?>
    <h3 class="modal-gp-title has-content"
        style="--category-color: <?= htmlspecialchars($gp->category_color ?? '#E10600') ?>;">

        <span class="gp-title-text-modal">
            <!-- Ajout du drapeau du pays du GP -->
            <?php if (!empty($gp->country_flag)): ?>
                <img src="<?= htmlspecialchars($gp->country_flag) ?>" class="drivers-teams-flag" alt="flag">
            <?php endif; ?>

            GP <?= htmlspecialchars($gp->gp_ordre) ?>
            - <?= htmlspecialchars($gp->circuit_name ?? '') ?>
            (<?= htmlspecialchars($gp->country_name ?? '') ?>)
            / Saison <?= htmlspecialchars($gp->season_number) ?> - <?= htmlspecialchars($gp->category) ?>
        </span>
        </h3>

    <!-- Affichage de Pole Position et/ou Fastest Lap si les données sont présentes -->
    <?php if (!empty($gp->pole_driver) || !empty($gp->pole_time)) : ?>
    <p class="modal-pp-fl">
        <?= !empty($gp->pole_time) ? "<span class='badge badge-purple'>$gp->pole_time</span>" : "" ?>
        <strong>Pole Position :</strong>
        <?= htmlspecialchars($gp->pole_driver ?? '') ?>
    </p>
    <?php endif; ?>

    <?php if (!empty($gp->fastest_lap_driver) || !empty($gp->fastest_lap_time)) : ?>
        <p class="modal-pp-fl">
            <?= !empty($gp->fastest_lap_time) ? "<span class='badge badge-purple'>$gp->fastest_lap_time</span>" : "" ?>
            <strong>Fastest Lap :</strong>
            <?= htmlspecialchars($gp->fastest_lap_driver ?? '') ?>
        </p>
    <?php endif; ?>

    <!-- Tableau des résultats du GP -->
    <div class="table-responsive">
        <table class="dashboard-table modal-gp-results-table"
                style="--category-color: <?= htmlspecialchars($gp->category_color ?? '#E10600') ?>;">
            <thead>
                <tr>
                    <th class="badge-width">Position</th>
                    <th>Pilote</th>
                    <th>Équipe</th>
                    <th class="text-center">Points</th>
                    <th>Commentaire</th>
                </tr>
            </thead>
            <tbody>
                <?php $position = 1; ?>
                <?php foreach ($gp->points as $point): ?>
                    <tr>

                        <!-- Badge position -->
                        <td class="badge-width"><?= podiumBadge($point->position) ?></td>

                        <!-- Pilote (drapeau + dégradé équipe) -->
                        <td class="driver-cell" 
                            style="--team-color: <?= htmlspecialchars($point->team_color ?? '') ?>
                            ">
                            <div class="driver-gradient"></div>

                            <span class="driver-content">
                                <?php if (!empty($point->driver_flag)): ?>
                                    <img src="<?= htmlspecialchars($point->driver_flag) ?>" class="drivers-teams-flag" alt="flag">
                                <?php endif; ?>
                                <span class="driver-name">
                                    <?= htmlspecialchars($point->nickname) ?>
                                </span>
                            </span>
                        </td>

                        <!-- Équipe (logo + couleur) -->
                        <td class="team-cell"
                            style="
                                --team-color: <?= htmlspecialchars($point->team_color ?? '') ?>;
                                --team-logo: url('<?= htmlspecialchars($point->team_logo ?? '') ?>');
                            ">
                            <span class="team-name"><?= htmlspecialchars($point->team_name ?? '') ?></span>
                        </td>

                        <!-- Points numériques formatés -->
                        <td class="text-center">
                            <?= htmlspecialchars(
                                isset($point->points_numeric)
                                    ? rtrim(rtrim(number_format($point->points_numeric, 1, '.', ''), '0'), '.')
                                    : ''
                            ) ?>
                        </td>

                        <!-- Points texte -->
                        <td class="text-center"><?= htmlspecialchars($point->points_text ?? '') ?></td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <p>GP non trouvé.</p>
<?php endif; ?>






