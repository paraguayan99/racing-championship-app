<?php $title = "Team-eRacing - Pilotes"; ?>

<?php
function sdPodiumBadge(int $pos): string {
    switch ($pos) {
        case 1:  return '<span class="badge badge-gold">1</span>';
        case 2:  return '<span class="badge badge-silver">2</span>';
        case 3:  return '<span class="badge badge-bronze">3</span>';
        default: return '<span class="badge badge-normal">' . $pos . '</span>';
    }
}

function sdRankBadge($rank): string {
    if (!is_numeric($rank)) return '<span class="badge badge-normal">-</span>';
    return sdPodiumBadge((int) $rank);
}
?>

<div class="section-dashboard">

    <a class="nav-btn statsdrivers" href="/classements/standings">Retour aux Classements</a>
    <a class="nav-btn red statsdrivers" href="/palmares">Palmarès</a>
    <a class="nav-btn red statsdrivers" href="/statscircuits">Circuits</a>

    <?php if (!$driver): ?>

        <div class="page-header">
            <h1>Pilote inconnu</h1>
        </div>
        <p style="text-align:center;">Ce pilote n'existe pas encore !</p>

    <?php else: ?>

        <!-- EN-TÊTE PILOTE -->
        <h1 class="page-header has-content header-statsdrivers">
            <div class="selected-statsdrivers">
                <?php if (!empty($driver->country_flag)): ?>
                    <img
                        src="/<?= htmlspecialchars($driver->country_flag) ?>"
                        alt="<?= htmlspecialchars($driver->country_name ?? '') ?>"
                        class="statsdrivers-flag"
                    >
                <?php endif; ?>
                <span class="statsdrivers-title">
                    <?= htmlspecialchars($driver->nickname) ?>
                    <?php if (!empty($driver->country_name)): ?>
                    <?php endif; ?>
                </span>
            </div>
        </h1>

        <?php if (empty($historyByCategory)): ?>
            <p style="text-align:center;">Aucun historique disponible pour ce pilote.</p>

        <?php else: ?>

            <?php
            $categoryOrder     = ['F1', 'F2', 'F3'];
            $orderedCategories = array_intersect($categoryOrder, array_keys($historyByCategory));
            foreach (array_keys($historyByCategory) as $cat) {
                if (!in_array($cat, $orderedCategories)) $orderedCategories[] = $cat;
            }
            ?>

            <?php foreach ($orderedCategories as $category):
                $rows     = $historyByCategory[$category];
                $catColor = $rows[0]->category_color ?? '#e10600';

                $totalPts  = array_sum(array_map(fn($r) => (float)$r->total_points,     $rows));
                $totalGp   = array_sum(array_map(fn($r) => (int)  $r->gp_count,         $rows));
                $totalWins = array_sum(array_map(fn($r) => (int)  $r->wins,             $rows));
                $totalPod  = array_sum(array_map(fn($r) => (int)  $r->podiums,          $rows));
                $totalPole = array_sum(array_map(fn($r) => (int)  $r->pole_count,       $rows));
                $totalFL   = array_sum(array_map(fn($r) => (int)  $r->fastestlap_count, $rows));
            ?>

            <div class="category-block"
                 style="--category-color: <?= htmlspecialchars($catColor) ?>">

                <h2 class="category-title has-content category-title-statsdrivers">
                    <span class="category-name"><?= htmlspecialchars($category) ?></span>
                </h2>

                <div class="table-responsive statsdrivers-table">
                    <table class="dashboard-table fix table-th-responsive statsdrivers-table">
                        <thead>
                            <tr>
                                <th class="text-center th-responsive down" title="Saison">
                                    <span class="label-aria">Saison</span>
                                    <span aria-hidden="true" class="label-long">Saison</span>
                                    <span aria-hidden="true" class="label-medium">Saison</span>
                                    <span aria-hidden="true" class="label-short">Sai</span>
                                </th>
                                <th title="Jeu vidéo">Jeu</th>
                                <th class="th-responsive" title="Équipe">
                                    <span class="label-aria">Équipe</span>
                                    <span aria-hidden="true" class="label-long">Équipe</span>
                                    <span aria-hidden="true" class="label-medium">Équipe</span>
                                    <span aria-hidden="true" class="label-short"></span>
                                </th>
                                <th class="badge-width th-responsive down" title="Position">
                                    <span class="label-aria">Position</span>
                                    Pos
                                </th>
                                <th class="text-center th-responsive down" title="Points">
                                    <span class="label-aria">Points</span>
                                    <span aria-hidden="true" class="label-long">Points</span>
                                    <span aria-hidden="true" class="label-medium">Pts</span>
                                    <span aria-hidden="true" class="label-short">Pts</span>
                                </th>
                                <th class="text-center th-responsive down" title="Grands Prix">GP</th>
                                <th class="text-center th-responsive down" title="Victoires">
                                    <span class="label-aria">Victoires</span>
                                    <span aria-hidden="true" class="label-long">Victoires</span>
                                    <span aria-hidden="true" class="label-medium">Vic</span>
                                    <span aria-hidden="true" class="label-short">Vi</span>
                                </th>
                                <th class="text-center th-responsive down" title="Podiums">
                                    <span class="label-aria">Podiums</span>
                                    <span aria-hidden="true" class="label-long">Podiums</span>
                                    <span aria-hidden="true" class="label-medium">Pod</span>
                                    <span aria-hidden="true" class="label-short">Po</span>
                                </th>
                                <th class="text-center th-responsive down" title="Pole Position">
                                    <span class="label-aria">Pole Position</span>
                                    <span aria-hidden="true" class="label-long">Pole Pos</span>
                                    <span aria-hidden="true" class="label-medium">PoleP</span>
                                    <span aria-hidden="true" class="label-short">PP</span>
                                </th>
                                <th class="text-center th-responsive down" title="Fastest Lap">
                                    <span class="label-aria">Fastest Lap</span>
                                    <span aria-hidden="true" class="label-long">Fast Lap</span>
                                    <span aria-hidden="true" class="label-medium">FastL</span>
                                    <span aria-hidden="true" class="label-short">FL</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row):
                                $seasonNum = str_pad((int)$row->season_number, 2, '0', STR_PAD_LEFT);
                                $rank      = $ranksBySeason[$row->season_id] ?? null;
                            ?>
                            <tr>
                                <!-- Saison -->
                                <td class="text-center down" title="Saison">
                                    S<?= htmlspecialchars($seasonNum) ?>
                                </td>

                                <!-- Jeu vidéo -->
                                <td class="team-cell-gradient down" 
                                    style="--team-color: <?= htmlspecialchars($row->team_color ?? '') ?>
                                    "
                                    title="Jeu vidéo">
                                    <?= htmlspecialchars($row->videogame) ?>
                                </td>

                                <!-- Équipe -->
                                <td class="team-cell down"
                                    style="
                                        --team-color: <?= htmlspecialchars($row->team_color ?? '') ?>;
                                        --team-logo: url('/<?= htmlspecialchars($row->team_logo ?? '') ?>');
                                    "
                                    title="Équipe">
                                    <span class="team-name"><?= htmlspecialchars($row->team_name ?? '-') ?></span>
                                </td>

                                <!-- Position au classement de la saison -->
                                <td class="badge-width" title="Position">
                                    <?= sdRankBadge($rank) ?>
                                </td>

                                <!-- Points -->
                                <td class="text-center down" title="Points">
                                    <?= htmlspecialchars(rtrim(rtrim(number_format((float)($row->total_points ?? 0), 1, '.', ''), '0'), '.')) ?>
                                </td>

                                <!-- GP -->
                                <td class="text-center" title="Grands Prix">
                                    <?= (int)($row->gp_count ?? 0) ?>
                                </td>

                                <!-- Victoires -->
                                <td class="text-center" title="Victoires">
                                    <?= (int)($row->wins ?? 0) ?>
                                </td>

                                <!-- Podiums -->
                                <td class="text-center" title="Podiums">
                                    <?= (int)($row->podiums ?? 0) ?>
                                </td>

                                <!-- Pole Position -->
                                <td class="text-center" title="Pole Position">
                                    <?= (int)($row->pole_count ?? 0) ?>
                                </td>

                                <!-- Fastest Lap -->
                                <td class="text-center" title="Fastest Lap">
                                    <?= (int)($row->fastestlap_count ?? 0) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>

                        <tfoot>
                            <tr style="background-color: var(--category-color); color: #FFFFFF">
                                <td class="text-center tfoot-very-small-text down"><strong><?= count($rows) ?></strong></td>
                                <td colspan="3"></td>
                                <td class="text-center tfoot-very-small-text down">
                                    <strong><?= htmlspecialchars(rtrim(rtrim(number_format($totalPts, 1, '.', ''), '0'), '.')) ?></strong>
                                </td>
                                <td class="text-center tfoot-very-small-text down"><strong><?= $totalGp ?></strong></td>
                                <td class="text-center tfoot-very-small-text down"><strong><?= $totalWins ?></strong></td>
                                <td class="text-center tfoot-very-small-text down"><strong><?= $totalPod ?></strong></td>
                                <td class="text-center tfoot-very-small-text down"><strong><?= $totalPole ?></strong></td>
                                <td class="text-center tfoot-very-small-text down"><strong><?= $totalFL ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div><!-- /.category-block -->

            <?php endforeach; ?>

        <?php endif; ?>

    <?php endif; ?>

</div><!-- /.section-dashboard -->
