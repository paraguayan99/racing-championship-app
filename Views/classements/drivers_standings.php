<?php
// ============================================
// drivers_standings.php
// ============================================

// Détermine catégorie et saison courante pour le titre
$categoryTitle = '';
$seasonTitle = '';

if (!empty($list)) {
    $categoryTitle = $list[0]->category ?? '';
    $seasonTitle = ($seasonFilter === 'active')
        ? 'Actuelle'
        : 'Saison ' . $list[0]->season_number;
}

$title = "Classement Pilotes - $categoryTitle - $seasonTitle";

// Fonction pour afficher les badges podium
function podiumBadge($pos) {
    if ($pos == 1) return '<span class="badge badge-gold">1</span>';
    if ($pos == 2) return '<span class="badge badge-silver">2</span>';
    if ($pos == 3) return '<span class="badge badge-bronze">3</span>';

    return '<span class="badge badge-normal">' . $pos . '</span>';
}
?>

<div class="section-dashboard">
    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=classements&action=index">Retour</a>

        <h1>
            Classement Pilotes –
            <?= htmlspecialchars($categoryTitle) ?> –
            <?= htmlspecialchars($seasonTitle) ?>
        </h1>

        <!-- Sélecteur de saison -->
        <form method="get" class="season-selector">
            <input type="hidden" name="controller" value="classements">
            <input type="hidden" name="action" value="driversStandings">

            <label for="season_filter">Afficher saison :</label>
            <select name="season_id" id="season_filter" onchange="this.form.submit()">

                <!-- Saison actuelle -->
                <option value="active" <?= $seasonFilter === 'active' ? 'selected' : '' ?>>
                    Actuelle
                </option>

                <?php
                // Grouper par catégorie
                $categories = [];
                foreach ($seasons as $season) {
                    $categories[$season->category][] = $season;
                }

                foreach ($categories as $catName => $catSeasons): ?>
                    <optgroup label="<?= htmlspecialchars($catName) ?>">
                        <?php foreach ($catSeasons as $season): ?>
                            <?php if ($season->status === 'desactive'): ?>
                                <option value="<?= $season->season_id ?>"
                                    <?= $seasonFilter == $season->season_id ? 'selected' : '' ?>>

                                    Saison <?= htmlspecialchars($season->season_number) ?>
                                    - <?= htmlspecialchars($season->videogame) ?>
                                    - <?= htmlspecialchars($season->platform) ?>

                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>

            </select>
        </form>
    </div>

    <!-- Tableau -->
    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Pos</th>
                    <th>Pilote</th>
                    <th>Team</th>
                    <th>Points</th>
                    <th>Victoires</th>
                    <th>Podiums</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($list)): ?>
                    <?php $position = 1; ?>
                    <?php foreach ($list as $row): ?>
                        <tr>

                            <!-- Position + badge podium -->
                            <td><?= podiumBadge($position++) ?></td>

                            <!-- Pilote avec dégradé -->
                            <td class="driver-cell" style="--team-color: <?= htmlspecialchars($row->team_color ?? '') ?>;">
                                <div class="driver-gradient"></div>

                                <span class="driver-content">
                                    <?php if (!empty($row->driver_flag)): ?>
                                        <img src="<?= htmlspecialchars($row->driver_flag) ?>" class="driver-flag" alt="flag">
                                    <?php endif; ?>

                                    <?= htmlspecialchars($row->nickname) ?>
                                </span>
                            </td>

                            <!-- Team -->
                            <td class="team-cell" style="background: <?= htmlspecialchars($row->team_color ?? '') ?>;">
                                
                                <?php if (!empty($row->team_logo)): ?>
                                    <img src="<?= htmlspecialchars($row->team_logo) ?>" class="team-logo" alt="logo">
                                <?php endif; ?>

                                <span class="team-name">
                                    <?= htmlspecialchars($row->team_name ?? '') ?>
                                </span>

                            </td>

                            <!-- Points -->
                            <td>
                                <?= htmlspecialchars(rtrim(rtrim(number_format($row->total_points ?? 0, 1, '.', ''), '0'), '.')) ?>
                            </td>

                            <td><?= htmlspecialchars($row->wins ?? 0) ?></td>
                            <td><?= htmlspecialchars($row->podiums ?? 0) ?></td>

                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">
                            Aucun pilote trouvé pour cette saison.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>
</div>
