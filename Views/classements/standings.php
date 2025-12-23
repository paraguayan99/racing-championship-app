<?php
$seasonTitle = ($seasonFilter === 'active') 
    ? 'Saison actuelle' 
    : 'Saison ' . ($listByCategory[array_key_first($listByCategory)][0]->season_number ?? '');
$title = "Classements - $seasonTitle";



// Fonction badges podium
function podiumBadge($pos) {
    return match($pos) {
        1 => '<span class="badge badge-gold">1</span>',
        2 => '<span class="badge badge-silver">2</span>',
        3 => '<span class="badge badge-bronze">3</span>',
        default => '<span class="badge badge-normal">' . $pos . '</span>',
    };
}

// Fonction badges Liste des GP
function gpBadge($gpNumber) {
    return '<span class="badge badge-normal">' . (int)$gpNumber . '</span>';
}
?>

<div class="section-dashboard">

    <a class="nav-btn red" href="index.php?controller=palmares">Palmarès</a>
    <a class="nav-btn red" href="index.php?controller=statscircuits">Circuits</a>

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
        <form method="get">
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
                                        Saison <?= htmlspecialchars($season->season_number) ?>
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
                </h2>

                <!-- Classement Pilotes -->
                <?php if (!empty($listByCategory[$categoryName])): ?>
                    <h3 style="margin-top:30px;">Classement Pilotes <?= htmlspecialchars($categoryName) ?></h3>

                    <div class="table-responsive">
                        <table class="dashboard-table drivers-table">
                            <thead>
                                <tr>
                                    <th class="badge-width">Position</th>
                                    <th>Pilote</th>
                                    <th>Équipe</th>
                                    <th class="text-center">Points</th>
                                    <th class="text-center">GP</th>
                                    <th class="text-center">Victoires</th>
                                    <th class="text-center">Podiums</th>
                                    <th class="text-center">Pole Position</th>
                                    <th class="text-center">Fastest Lap</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $position = 1; ?>
                                <?php foreach ($listByCategory[$categoryName] as $row): ?>
                                    <tr>
                                        <td class="badge-width"><?= podiumBadge($position++) ?></td>

                                        <!-- Pilote -->
                                        <td class="driver-cell" 
                                            style="--team-color: <?= htmlspecialchars($row->team_color ?? '') ?>
                                            ">
                                            <div class="driver-gradient"></div>
                                            
                                            <span class="driver-content">
                                                <?php if (!empty($row->driver_flag ?? null)): ?>
                                                    <img src="<?= htmlspecialchars($row->driver_flag) ?>" class="drivers-teams-flag" alt="flag">
                                                <?php endif; ?>

                                                <!-- Class pour cibler le nom du pilote en JavaScript et en réduire sa taille -->
                                                <span class="driver-name">
                                                    <?= htmlspecialchars($row->nickname) ?>
                                                </span>
                                            </span>
                                        </td>

                                        <!-- Équipe -->
                                        <td class="team-cell"
                                            style="
                                                --team-color: <?= htmlspecialchars($row->team_color ?? '') ?>;
                                                --team-logo: url('<?= htmlspecialchars($row->team_logo ?? '') ?>');
                                            ">
                                            <span class="team-name"><?= htmlspecialchars($row->team_name ?? '') ?></span>
                                        </td>


                                        <td class="text-center"><?= htmlspecialchars(rtrim(rtrim(number_format($row->total_points ?? 0, 1, '.', ''), '0'), '.')) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($row->gp_count ?? 0) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($row->wins ?? 0) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($row->podiums ?? 0) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($row->pole_count ?? 0) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($row->fastestlap_count ?? 0) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Classement Équipes -->
                <?php if (!empty($teamsByCategory[$categoryName])): ?>
                    <h3 style="margin-top:30px;">Classement Équipes <?= htmlspecialchars($categoryName) ?></h3>

                    <div class="table-responsive">
                        <table class="dashboard-table teams-table">
                            <thead>
                                <tr>
                                    <th class="badge-width">Position</th>
                                    <th>Équipe</th>
                                    <th class="text-center">Points</th></tr>
                            </thead>
                            <tbody>
                                <?php $teamPos = 1; ?>
                                <?php foreach ($teamsByCategory[$categoryName] as $team): ?>
                                    <tr>
                                        <td class="badge-width"><?= podiumBadge($teamPos++) ?></td>

                                        <td class="teams-team-cell" style="--team-color: <?= htmlspecialchars($team->team_color ?? '') ?>;">
                                            
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

                                                <span><?= htmlspecialchars($team->team_name ?? '') ?></span>
                                            </span>
                                        </td>

                                        <td class="text-center"><?= htmlspecialchars(rtrim(rtrim(number_format($team->total_points ?? 0, 1, '.', ''), '0'), '.')) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Pénalités -->
                <?php if (!empty($penaltiesByCategory[$categoryName])): ?>
                    <h3 style="margin-top:30px;">
                        Pénalités <?= htmlspecialchars($categoryName) ?>
                    </h3>

                    <div class="table-responsive">
                        <table class="dashboard-table penalties-table">
                            <thead>
                                <tr>
                                    <th class="text-center">GP</th>
                                    <th class="text-center">Pilote</th>
                                    <th class="text-center">Équipe</th>
                                    <th class="text-center">Pénalité</th>
                                    <th class="text-center">Commentaire</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($penaltiesByCategory[$categoryName] as $p): ?>
                                    <tr>

                                        <!-- GP -->
                                        <td class="gp-cell">
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
                                        <td class="driver-cell text-center">
                                            <span class="driver-name">
                                                <?= htmlspecialchars($p->driver_name ?? '') ?>
                                            </span>
                                        </td>

                                        <!-- Équipe -->
                                        <td class="team-cell text-center">
                                            <span class="team-name">
                                                <?= htmlspecialchars($p->team_name ?? '') ?>
                                            </span>
                                        </td>

                                        <!-- Points retirés -->
                                        <td class="penalty-points text-center">
                                            <?= htmlspecialchars($p->points_removed ?? 0) ?>
                                        </td>

                                        <!-- Commentaire -->
                                        <td class="penalty-comment text-center">
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
                        Résultats GP <?= htmlspecialchars($categoryName) ?>
                    </h3>
                    <p class="gp-subtitle">
                        Cliquez sur le GP pour voir les résultats complets
                    </p>

                <div class="table-responsive">
                <table class="dashboard-table gp-season-table">
                    <thead>
                        <tr>
                            <th class="badge-width">GP</th>
                            <th>Circuit</th>
                            <th class="text-center">1er</th>
                            <th class="text-center">2e</th>
                            <th class="text-center">3e</th>
                            <th class="text-center">Pole Position</th>
                            <th class="text-center">Fastest Lap</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($gpByCategory[$categoryName] as $gp): ?>
                        <?php
                            $top3 = json_decode($gp->top3 ?? '[]');
                        ?>
                        <tr class="gp-row" data-gp-id="<?= (int)$gp->id ?>">

                            <!-- GP N° -->
                            <td class="badge-width"><?= gpBadge($gp->gp_ordre) ?></td>
                           
                            <!-- Circuit -->
                            <td class="circuit-cell">
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
                                <td class="text-center">
                                    <?php if (!empty($top3[$i])): ?>
                                        <span class="driver-name">
                                            <?= htmlspecialchars($top3[$i]->nickname ?? '') ?>
                                        </span>
                                    <?php else: ?>
                                        
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>

                            <!-- Pole -->
                            <td class="text-center">
                                <?php if (!empty($gp->pole_driver)): ?>
                                    <span class="badge badge-purple driver-name">
                                        <?= htmlspecialchars($gp->pole_driver) ?>
                                    </span>
                                <?php else: ?>
                                    
                                <?php endif; ?>
                            </td>

                            <!-- Fastest Lap -->
                            <td class="text-center">
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
        <p style="text-align:center;">Aucun pilote trouvé pour cette saison.</p>
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
/* Toggle GP pour la catégorie */
document.querySelectorAll('.gp-toggle-btn-category').forEach(btn => {
    btn.addEventListener('click', () => {
        const category = btn.dataset.category;
        const contents = document.querySelectorAll('.gp-category-' + category);
        const isOpen = Array.from(contents).some(c => c.style.maxHeight && c.style.maxHeight !== '0px');

        contents.forEach(c => {
            if (isOpen) {
                c.style.maxHeight = '0';
                c.style.marginBottom = '0';
            } else {
                c.style.maxHeight = c.scrollHeight + 'px';
                c.style.marginBottom = '20px';
            }
        });
        btn.textContent = isOpen ? 'Afficher tous les GP' : 'Masquer tous les GP';
    });
});

/* Ouvre le modal Résultats du GP en cliquant sur une ligne GP */
document.addEventListener('click', function (e) {
    const row = e.target.closest('.gp-row');
    if (!row) return;

    const gpId = row.dataset.gpId;
    if (!gpId) return;

    fetch(`index.php?controller=classements&action=gpDetails&gp_id=${gpId}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('gp-modal-body').innerHTML = html;
            document.getElementById('gp-modal').style.display = 'block';
        });
});

/* Fermer le modal */
document.querySelector('.gp-modal-close').addEventListener('click', () => {
    document.getElementById('gp-modal').style.display = 'none';
});

/* Fermer modal si clic en dehors */
window.addEventListener('click', e => {
    if (e.target === document.getElementById('gp-modal')) {
        document.getElementById('gp-modal').style.display = 'none';
    }
});
</script>

<!-- Responsive Classements Pilotes - Réduit taille du nom des pilotes et des écuries - Mobile & Tablette -->
<script>
(function () {

    function updateResponsiveNames() {
        const w = window.innerWidth;

        /* ===== PILOTES (Classement Pilotes) ===== */
        document.querySelectorAll('.driver-name').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.trim();
            }

            const full = el.dataset.fullname;

            if (w <= 500) {
                el.textContent = full.substring(0, 8);
            }
            else if (w <= 700) {
                el.textContent = full.substring(0, 12);
            }
            else if (w <= 900) {
                el.textContent = full.substring(0, 16);
            }
            else {
                el.textContent = full.substring(0, 20);
            }
        });

        /* ===== ÉCURIES ===== (Classement Pilotes) */
        document.querySelectorAll('.drivers-table .team-name').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.trim();
            }

            const full = el.dataset.fullname;

            if (w <= 900) {
                el.textContent = full.substring(0, 10);
            }
            else {
                el.textContent = full.substring(0, 20);
            }
        });

        /* ===== ÉCURIES (Classement Équipes) ===== */
        document.querySelectorAll('.teams-table .team-content span').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.trim();
            }

            const full = el.dataset.fullname;

            if (w <= 700) {
                el.textContent = full.substring(0, 20);
            } else {
                el.textContent = full.substring(0, 30);
            }
        });

        /* ===== PENALITES ===== */
        document.querySelectorAll('.penalties-table .gp-name').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
            }

            const full = el.dataset.fullname;

            if (w <= 600) {
                el.textContent = full.substring(0, 5);
            }
            else if (w <= 700) {
                el.textContent = full.substring(0, 10);
            }
            else if (w <= 900) {
                el.textContent = full.substring(0, 12);
            }
            else {
                el.textContent = full.substring(0, 20);
            }
        });

        document.querySelectorAll('.penalties-table .driver-name').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
            }

            const full = el.dataset.fullname;

            if (w <= 600) {
                el.textContent = full.substring(0, 8);
            }
            else if (w <= 700) {
                el.textContent = full.substring(0, 16);
            }
            else if (w <= 1200) {
                el.textContent = full.substring(0, 24);
            }
            else {
                el.textContent = full.substring(0, 30);
            }
        });

        document.querySelectorAll('.penalties-table .team-name').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
            }

            const full = el.dataset.fullname;

            if (w <= 600) {
                el.textContent = full.substring(0, 8);
            }
            else if (w <= 700) {
                el.textContent = full.substring(0, 16);
            }
            else if (w <= 1200) {
                el.textContent = full.substring(0, 24);
            }
            else {
                el.textContent = full.substring(0, 30);
            }
        });

        document.querySelectorAll('.penalties-table .penalty-comment').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
            }

            const full = el.dataset.fullname;

            if (w <= 600) {
                el.textContent = full.substring(0, 5);
            }
            else if (w <= 700) {
                el.textContent = full.substring(0, 10);
            }
            else if (w <= 1000) {
                el.textContent = full.substring(0, 18);
            }
            else if (w <= 1200) {
                el.textContent = full.substring(0, 30);
            }
            else {
                el.textContent = full.substring(0, 35);
            }
        });

        /* ===== RESULTATS GP ===== */
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
                el.textContent = full.substring(0, 18);
            }
        });

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

})();
</script>





