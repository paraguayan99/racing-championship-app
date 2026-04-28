<?php $title = "Team-eRacing - Classements";

// Fonction badges podium
function podiumBadge($pos) {
    switch ($pos) {
        case 1:
            return '<span class="badge badge-gold">1</span>';
        case 2:
            return '<span class="badge badge-silver">2</span>';
        case 3:
            return '<span class="badge badge-bronze">3</span>';
        default:
            return '<span class="badge badge-normal">' . $pos . '</span>';
    }
}

// Fonction badges Liste des GP
function gpBadge($gpNumber) {
    return '<span class="badge badge-normal">' . (int)$gpNumber . '</span>';
}
?>

<div class="section-dashboard">

    <a class="nav-btn red" href="/palmares">Palmarès</a>
    <a class="nav-btn red" href="/statscircuits">Circuits</a>

    <div class="page-header">
        <h1>Classements</h1>

        <?php if ($lastGPUpdate): ?>
            <p class="last-update">
                <span class="lu-label">Dernière mise à jour</span> :
                <span class="lu-date"><?= date('d/m/Y H:i', strtotime($lastGPUpdate->updated_at)) ?></span>

                <span class="lu-tablet">
                    <span class="lu-sep"> / </span>
                    <span class="lu-category"><?= htmlspecialchars($lastGPUpdate->category_name) ?></span>
                    <span class="lu-sep"> - </span>
                    <span class="lu-season">Saison <?= htmlspecialchars($lastGPUpdate->season_number) ?></span>
                    <span class="lu-sep"> - </span>
                    <span class="lu-gp">GP <?= htmlspecialchars($lastGPUpdate->gp_ordre) ?></span>
                </span>

                <span class="lu-desktop">
                    <span class="lu-sep"> - </span>
                    <span class="lu-circuit">
                        <?= htmlspecialchars($lastGPUpdate->circuit_name) ?>
                        (<?= htmlspecialchars($lastGPUpdate->country_name) ?>)
                    </span>
                </span>
            </p>
        <?php endif; ?>
    </div>
    <div>
        <!-- Sélecteur de saison -->
        <form method="get" action="/classements/standings">
            <input type="hidden" name="controller" value="classements">
            <input type="hidden" name="action" value="standings">

            <label for="season_filter" class="visually-hidden">Afficher une saison :</label>
            <div class="form-group">
                <select name="season_id" id="season_filter" onchange="this.form.submit()">
                    <option value="active" <?= $seasonFilter === 'active' ? 'selected' : '' ?>>Saison actuelle</option>

                    <?php
                    $categories = [];
                    foreach ($seasons as $season) {
                        $categories[$season->category][] = $season;
                    }

                    foreach ($categories as $catName => $catSeasons): ?>
                        <optgroup label="<?= htmlspecialchars($catName) ?>">
                            <?php foreach ($catSeasons as $season): ?>
                                <?php if ($season->status === 'desactive'): ?>
                                    <option value="<?= $season->season_id ?>" <?= $seasonFilter == $season->season_id ? 'selected' : '' ?>>
                                        S<?= htmlspecialchars($season->season_number) ?>
                                        - <?= htmlspecialchars($season->videogame) ?>
                                        - <?= htmlspecialchars($season->platform) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>

    <!-- Tableaux par catégorie -->
    <?php if (!empty($listByCategory)): ?>
        <?php foreach ($listByCategory as $categoryName => $drivers): 
                $seasonNumber = $drivers[0]->season_number ?? null; 
            ?>
            <div class="category-block"
                    style="--category-color: <?= htmlspecialchars($categoryColors[$categoryName] ?? '#E10600') ?>">

                <h2 class="category-title has-content">
                    <span class="category-name">
                        <?= htmlspecialchars($categoryName) ?>
                    </span>

                <?php if ($seasonNumber): ?>
                    <span class="season-title">
                        Saison <?= htmlspecialchars($seasonNumber) ?>
                    </span>
                <?php endif; ?>

                    <?php
                    $extra = [];

                    if (!empty($drivers[0]->videogame)) {
                        $extra[] = htmlspecialchars($drivers[0]->videogame);
                    }
                    if (!empty($drivers[0]->platform)) {
                        $extra[] = htmlspecialchars($drivers[0]->platform);
                    }

                    if ($extra): ?>
                        <span class="category-extra">
                            <?= implode(' - ', $extra) ?>
                        </span>
                    <?php endif; ?>

                    <?php $seasonName = $drivers[0]->season_name ?? null; ?>
                    <?php if (!empty($seasonName)): ?>
                        <span class="season-title">
                            <?= htmlspecialchars($seasonName) ?>
                        </span>
                    <?php endif; ?>
                </h2>

                <!-- Classement Pilotes -->
                <?php if (!empty($listByCategory[$categoryName])): ?>
                    <h3 class="gp-title" style="margin-top:20px;">Classement Pilotes</h3>

                    <div class="table-responsive">
                        <table class="dashboard-table fix table-th-responsive drivers-table">
                            <thead>
                                <tr>
                                    <th class="badge-width th-responsive" title="Position">
                                        <span class="label-aria">Position</span>
                                    </th>
                                    <th title="Pilote">Pilote</th>
                                    <th class="th-responsive" title="Équipe">
                                            <span class="label-aria">Équipe</span>
                                            <span aria-hidden="true" class="label-long">Équipe</span>
                                            <span aria-hidden="true" class="label-medium"></span>
                                            <span aria-hidden="true" class="label-short"></span>
                                    </th>
                                    <th class="text-center th-responsive" title="Points">
                                            <span class="label-aria">Points</span>
                                            <span aria-hidden="true" class="label-long">Points</span>
                                            <span aria-hidden="true" class="label-medium">Pts</span>
                                            <span aria-hidden="true" class="label-short">Pts</span>
                                    </th>
                                    <th class="text-center th-responsive" title="Grands Prix">GP</th>
                                    <th class="text-center th-responsive" title="Victoires">
                                            <span class="label-aria">Victoires</span>
                                            <span aria-hidden="true" class="label-long">Victoires</span>
                                            <span aria-hidden="true" class="label-medium">Vic</span>
                                            <span aria-hidden="true" class="label-short">Vi</span>
                                    </th>
                                    <th class="text-center th-responsive" title="Podiums">
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
                                <?php $position = 1; ?>
                                <?php foreach ($listByCategory[$categoryName] as $row): ?>
                                    <tr>
                                        <td class="badge-width" title="Position"><?= podiumBadge($position++) ?></td>

                                        <!-- Pilote -->
                                        <td class="driver-cell down" 
                                            style="--team-color: <?= htmlspecialchars($row->team_color ?? '') ?>
                                            "
                                            title="Pilote">
                                            <div class="driver-gradient"></div>
                                            
                                            <span class="driver-content">
                                                <?php if (!empty($row->driver_flag ?? null)): ?>
                                                    <img src="<?= htmlspecialchars($row->driver_flag) ?>" class="drivers-teams-flag" alt="flag">
                                                <?php endif; ?>
                                                <span class="driver-name">
                                                    <?= htmlspecialchars($row->nickname) ?>
                                                </span>
                                            </span>
                                        </td>

                                        <!-- Équipe -->
                                        <td class="team-cell down"
                                            style="
                                                --team-color: <?= htmlspecialchars($row->team_color ?? '') ?>;
                                                --team-logo: url('<?= htmlspecialchars($row->team_logo ?? '') ?>');
                                            "
                                            title="Équipe">
                                            <span class="team-name"><?= htmlspecialchars($row->team_name ?? '') ?></span>
                                        </td>


                                        <td class="text-center down" title="Points"><?= htmlspecialchars(rtrim(rtrim(number_format($row->total_points ?? 0, 1, '.', ''), '0'), '.')) ?></td>
                                        <td class="text-center" title="Grands Prix"><?= htmlspecialchars($row->gp_count ?? 0) ?></td>
                                        <td class="text-center" title="Victoires"><?= htmlspecialchars($row->wins ?? 0) ?></td>
                                        <td class="text-center" title="Podiums"><?= htmlspecialchars($row->podiums ?? 0) ?></td>
                                        <td class="text-center" title="Pole Position"><?= htmlspecialchars($row->pole_count ?? 0) ?></td>
                                        <td class="text-center" title="Fastest Lap"><?= htmlspecialchars($row->fastestlap_count ?? 0) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Classement Équipes -->
                <?php if (!empty($teamsByCategory[$categoryName])): ?>
                    <h3 style="margin-top:30px;">Classement Constructeurs</h3>

                    <div class="table-responsive">
                        <table class="dashboard-table fix table-th-responsive teams-table">
                            <thead>
                                <tr>
                                    <th class="badge-width th-responsive" title="Position">
                                        <span class="label-aria">Position</span>
                                    </th>
                                    <th title="Équipe">Équipe</th>
                                    <th class="text-center" title="Points">Points</th></tr>
                            </thead>
                            <tbody>
                                <?php $teamPos = 1; ?>
                                <?php foreach ($teamsByCategory[$categoryName] as $team): ?>
                                    <tr>
                                        <td class="badge-width" title="Position">
                                            <?= podiumBadge($teamPos++) ?>
                                        </td>

                                        <td class="teams-team-cell down" 
                                            style="--team-color: <?= htmlspecialchars($team->team_color ?? '') ?>;"
                                            title="Équipe">
                                            
                                            <!-- Gradient derrière -->
                                            <div class="team-gradient"></div>
                                            
                                            <!-- Logo derrière le contenu mais devant le gradient -->
                                            <?php if (!empty($team->team_logo ?? null)): ?>
                                                <img src="<?= htmlspecialchars($team->team_logo) ?>"
                                                    class="team-logo-bg"
                                                    alt="logo">
                                            <?php endif; ?>
                                            
                                            <!-- Contenu au-dessus -->
                                            <span class="team-content">
                                                <?php if (!empty($team->team_flag ?? null)): ?>
                                                    <img src="<?= htmlspecialchars($team->team_flag) ?>"
                                                        class="drivers-teams-flag"
                                                        alt="flag">
                                                <?php endif; ?>

                                                <span class="teams-table-team-name">
                                                    <?= htmlspecialchars($team->team_name ?? '') ?>
                                                </span>
                                            </span>
                                        </td>

                                        <td class="text-center" title="Points">
                                            <?= htmlspecialchars(rtrim(rtrim(number_format($team->total_points ?? 0, 1, '.', ''), '0'), '.')) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Pénalités -->
                <?php if (!empty($penaltiesByCategory[$categoryName])): ?>
                    <h3 style="margin-top:30px;">
                        Pénalités
                    </h3>

                    <div class="table-responsive">
                        <table class="dashboard-table fix table-th-responsive penalties-table">
                            <thead>
                                <tr>
                                    <th class="text-center" title="Grand Prix">GP</th>
                                    <th class="text-center" title="Pilote">Pilote</th>
                                    <th class="text-center" title="Équipe">Équipe</th>
                                    <th class="text-center th-responsive" title="Pénalité">
                                        <span class="label-aria">Pénalité</span>
                                        <span aria-hidden="true" class="label-long">Pénalité</span>
                                        <span aria-hidden="true" class="label-medium">Pénalité</span>
                                        <span aria-hidden="true" class="label-short">Pén</span>
                                    </th>
                                    <th class="text-center th-responsive" title="Commentaire">
                                        <span class="label-aria">Commentaire</span>
                                        <span aria-hidden="true" class="label-long">Commentaire</span>
                                        <span aria-hidden="true" class="label-medium">Commentaire</span>
                                        <span aria-hidden="true" class="label-short">Com</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($penaltiesByCategory[$categoryName] as $p): ?>
                                    <tr>
                                        <!-- GP -->
                                        <td class="gp-cell th-responsive down"
                                            title="Grand Prix">
                                            <?php if (!empty($p->country_flag)): ?>
                                                <img
                                                    src="<?= htmlspecialchars($p->country_flag) ?>"
                                                    class="drivers-teams-flag"
                                                    alt="flag">
                                            <?php endif; ?>

                                            <span class="gp-name">
                                                GP <?= htmlspecialchars($p->gp_ordre ?? '') ?>
                                                - <?= htmlspecialchars($p->circuit_name ?? '') ?>
                                            </span>
                                        </td>

                                        <!-- Pilote -->
                                        <td class="driver-cell text-center th-responsive down"
                                            title="Pilote">
                                            <span class="driver-name">
                                                <?= htmlspecialchars($p->driver_name ?? '') ?>
                                            </span>
                                        </td>

                                        <!-- Équipe -->
                                        <td class="team-cell text-center th-responsive down"
                                            title="Équipe">
                                            <span class="team-name">
                                                <?= htmlspecialchars($p->team_name ?? '') ?>
                                            </span>
                                        </td>

                                        <!-- Points retirés -->
                                        <td class="penalty-points text-center" 
                                            title="Pénalité">
                                            <?= htmlspecialchars($p->points_removed ?? 0) ?>
                                        </td>

                                        <!-- Commentaire -->
                                        <td class="penalty-comment text-center th-responsive down" 
                                            title="<?= htmlspecialchars($p->comment ?? 'Aucun commentaire') ?>">
                                            <?= nl2br(htmlspecialchars($p->comment ?? '')) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Résultats GP -->
                <?php if (!empty($gpByCategory[$categoryName])): ?>
                    <h3 class="gp-title">
                        Résultats GP
                    </h3>
                    
                    <p class="gp-subtitle">
                        <i class="fa-solid fa-circle-chevron-right"></i> Cliquez sur le GP pour voir les résultats complets
                    </p>

                <div class="table-responsive">
                <table class="dashboard-table fix table-th-responsive gp-season-table">
                    <thead>
                        <tr>
                            <th class="badge-width" title="Grand Prix">GP</th>
                            <th title="Circuit">Circuit</th>
                            <th class="text-center" title="Vainqueur">1er</th>
                            <th class="text-center" title="Second">2e</th>
                            <th class="text-center" title="Troisième">3e</th>
                            <th class="text-center th-responsive down" title="Pole Position">
                                <span class="label-aria">Pole Position</span>
                                <span aria-hidden="true" class="label-long">Pole Position</span>
                                <span aria-hidden="true" class="label-medium">Pole Pos</span>
                                <span aria-hidden="true" class="label-short">PP</span>
                            </th>
                            <th class="text-center th-responsive down" title="Fastest Lap">
                                <span class="label-aria">Fastest Lap</span>
                                <span aria-hidden="true" class="label-long">Fastest Lap</span>
                                <span aria-hidden="true" class="label-medium">Fast Lap</span>
                                <span aria-hidden="true" class="label-short">FL</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($gpByCategory[$categoryName] as $gp): ?>
                        <?php
                            $top3 = json_decode($gp->top3 ?? '[]');
                        ?>
                        <tr class="gp-row" data-gp-id="<?= (int)$gp->id ?>">

                            <!-- GP N° -->
                            <td class="badge-width"
                                title="GP <?= htmlspecialchars($gp->gp_ordre) ?>">
                                <?= gpBadge($gp->gp_ordre) ?>
                            </td>
                           
                            <!-- Circuit -->
                            <td class="circuit-cell down"
                                title="Circuit">
                                <?php if (!empty($gp->country_flag)): ?>
                                    <img src="<?= htmlspecialchars($gp->country_flag) ?>" class="drivers-teams-flag" alt="flag">
                                <?php endif; ?>
                                <span class="circuit-name">
                                    <?= htmlspecialchars($gp->circuit_name) ?>
                                </span>
                                <span class="country-code">
                                    <?= htmlspecialchars($gp->country_code) ?>
                                </span>
                            </td>

                            <!-- Podium -->
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <td class="text-center down"
                                    title="<?= !empty($top3[$i]) ? htmlspecialchars(($i+1) . 'e : ' . $top3[$i]->nickname) : '' ?>">
                                    <?php if (!empty($top3[$i])): ?>
                                        <span class="driver-name">
                                            <?= htmlspecialchars($top3[$i]->nickname ?? '') ?>
                                        </span>
                                    <?php else: ?>
                                        
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>

                            <!-- Pole -->
                            <td class="text-center down"
                                title="Pole Position">
                                <?php if (!empty($gp->pole_driver)): ?>
                                    <span class="badge badge-purple driver-name">
                                        <?= htmlspecialchars($gp->pole_driver) ?>
                                    </span>
                                <?php else: ?>
                                    
                                <?php endif; ?>
                            </td>

                            <!-- Fastest Lap -->
                            <td class="text-center down"
                                title="Fastest Lap">
                                <?php if (!empty($gp->fastest_lap_driver)): ?>
                                    <span class="badge badge-purple driver-name">
                                        <?= htmlspecialchars($gp->fastest_lap_driver) ?>
                                    </span>
                                <?php else: ?>
                                    
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
                </div>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center;">La saison n’a pas encore commencé !</p>
    <?php endif; ?>
</div>

<!-- Modal GP -->
<div id="gp-modal" class="gp-modal">
    <div class="gp-modal-content">
        <span class="gp-modal-close">&times;</span>
        <div id="gp-modal-body"></div>
    </div>
</div>

<script>
// Réduit la taille des infos de la dernière mise à jour
(function () {
    const label = document.querySelector('.lu-label');
    if (!label) return;

    function updateLabel() {
        label.textContent = window.innerWidth <= 700
            ? 'MAJ'
            : 'Dernière mise à jour';
    }

    window.addEventListener('resize', updateLabel);
    updateLabel();
})();
</script>

<script>

// Ouvre le modal Résultats du GP en cliquant sur une ligne GP
document.addEventListener('click', function (e) {
    const row = e.target.closest('.gp-row');
    if (!row) return;

    const gpId = row.dataset.gpId;
    if (!gpId) return;

    fetch(`/index.php?controller=classements&action=gpDetails&gp_id=${gpId}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('gp-modal-body').innerHTML = html;
            document.getElementById('gp-modal').style.display = 'block';
            updateResponsiveNames();
        });
});

// Fermer le modal
document.querySelector('.gp-modal-close').addEventListener('click', () => {
    document.getElementById('gp-modal').style.display = 'none';
});

// Fermer modal si clic en dehors
window.addEventListener('click', e => {
    if (e.target === document.getElementById('gp-modal')) {
        document.getElementById('gp-modal').style.display = 'none';
    }
});
</script>

<!-- Responsive - Réduit taille du nom des pilotes / des écuries / des circuits sur Mobile & Tablette -->
<script>
    function updateResponsiveNames() {
        const w = window.innerWidth;

        /*  CIRCUIT  (Tableau Liste des GP) */
        document.querySelectorAll('.gp-season-table .circuit-name').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
            }

            const full = el.dataset.fullname;

            if (w <= 600) {
                el.textContent = full.substring(0, 3);
            }
            else if (w <= 750) {
                el.textContent = full.substring(0, 4);
            }
            else if (w <= 900) {
                el.textContent = full.substring(0, 6);
            }
            else if (w <= 1000) {
                el.textContent = full.substring(0, 9);
            }
            else if (w <= 1200) {
                el.textContent = full.substring(0, 12);
            }
            else {
                el.textContent = full.substring(0, 20);
            }
        });

        /*  PILOTES  (Tableau Liste des GP) */
        document.querySelectorAll('.gp-season-table .driver-name').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
            }

            const full = el.dataset.fullname;

            if (w <= 600) {
                el.textContent = full.substring(0, 4);
            }
            else if (w <= 700) {
                el.textContent = full.substring(0, 7);
            }
            else if (w <= 1000) {
                el.textContent = full.substring(0, 10);
            }
            else {
                el.textContent = full.substring(0, 18);
            }
        });

    }
    window.addEventListener('resize', updateResponsiveNames);
    updateResponsiveNames();
</script>
