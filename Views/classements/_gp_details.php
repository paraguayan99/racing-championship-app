<?php
// Fonction pour les badges podium
function podiumBadge($pos) {
    return match($pos) {
        1 => '<span class="badge badge-gold">1</span>',
        2 => '<span class="badge badge-silver">2</span>',
        3 => '<span class="badge badge-bronze">3</span>',
        default => '<span class="badge badge-normal">' . $pos . '</span>',
    };
}
?>

<?php if ($gp): ?>
    <h3>
        GP <?= htmlspecialchars($gp->gp_ordre) ?> – <?= htmlspecialchars($gp->circuit_name ?? '-') ?> (<?= htmlspecialchars($gp->country_name ?? '-') ?>)
        / Saison <?= htmlspecialchars($gp->season_number) ?> – <?= htmlspecialchars($gp->category) ?>
    </h3>
    <p><strong>Pole :</strong> <?= htmlspecialchars($gp->pole_driver ?? '-') ?> <?= $gp->pole_time ? "($gp->pole_time)" : "" ?></p>
    <p><strong>Fastest Lap :</strong> <?= htmlspecialchars($gp->fastest_lap_driver ?? '-') ?> <?= $gp->fastest_lap_time ? "($gp->fastest_lap_time)" : "" ?></p>

    <h4>Classement GP</h4>
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Pos</th>
                    <th>Pilote</th>
                    <th>Équipe</th>
                    <th>Points</th>
                    <th>Points texte</th>
                </tr>
            </thead>
            <tbody>
                <?php $position = 1; ?>
                <?php foreach ($gp->points as $point): ?>
                    <tr>
                        <!-- Badge position -->
                        <td><?= podiumBadge($position++) ?></td>

                        <!-- Pilote avec dégradé couleur et drapeau -->
                        <td class="driver-cell" style="--team-color: <?= htmlspecialchars($point->team_color ?? '') ?>">
                            <div class="driver-gradient"></div>
                            <span class="driver-content">
                                <?php if (!empty($point->driver_flag ?? null)): ?>
                                    <img src="<?= htmlspecialchars($point->driver_flag) ?>" class="driver-flag" alt="flag">
                                <?php endif; ?>
                                <?= htmlspecialchars($point->nickname) ?>
                            </span>
                        </td>

                        <!-- Équipe avec logo et couleur -->
                        <td class="team-cell" style="background: <?= htmlspecialchars($point->team_color ?? '') ?>">
                            <?php if (!empty($point->team_logo)): ?>
                                <img src="<?= htmlspecialchars($point->team_logo) ?>" class="team-logo" alt="logo">
                            <?php endif; ?>
                            <span class="team-name"><?= htmlspecialchars($point->team_name ?? '-') ?></span>
                        </td>

                        <!-- Points numériques formatés -->
                        <td>
                            <?= htmlspecialchars(
                                isset($point->points_numeric) 
                                    ? rtrim(rtrim(number_format($point->points_numeric, 1, '.', ''), '0'), '.') 
                                    : ''
                            ) ?>
                        </td>

                        <!-- Points texte -->
                        <td><?= htmlspecialchars($point->points_text ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>GP non trouvé.</p>
<?php endif; ?>
