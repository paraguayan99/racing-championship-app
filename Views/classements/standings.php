<?php
$seasonTitle = ($seasonFilter === 'active') 
    ? 'Saison actuelle' 
    : 'Saison ' . ($listByCategory[array_key_first($listByCategory)][0]->season_number ?? '');
$title = "Classement – $seasonTitle";



// Fonction badges podium
function podiumBadge($pos) {
    return match($pos) {
        1 => '<span class="badge badge-gold">1</span>',
        2 => '<span class="badge badge-silver">2</span>',
        3 => '<span class="badge badge-bronze">3</span>',
        default => '<span class="badge badge-normal">' . $pos . '</span>',
    };
}
?>

<div class="section-dashboard">
    <a class="nav-btn red" href="index.php?controller=palmares">Palmarès</a>
    <div class="section-header">
        <h1>Classements – <?= htmlspecialchars($seasonTitle) ?></h1>

        <?php if ($lastGPUpdate): ?>
            <p class="last-update">
                Dernière mise à jour : <?= date('d/m/Y H:i', strtotime($lastGPUpdate->updated_at)) ?>
                - <?= htmlspecialchars($lastGPUpdate->category_name) ?> Saison <?= htmlspecialchars($lastGPUpdate->season_number) ?>
                - GP <?= htmlspecialchars($lastGPUpdate->gp_ordre) ?>
                - <?= htmlspecialchars($lastGPUpdate->circuit_name) ?> (<?= htmlspecialchars($lastGPUpdate->country_name) ?>)
            </p>
        <?php endif; ?>

        <!-- Sélecteur de saison -->
        <form method="get" class="season-selector">
            <input type="hidden" name="controller" value="classements">
            <input type="hidden" name="action" value="standings">

            <label for="season_filter">Afficher saison :</label>
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
        </form>
    </div>

    <!-- Tableaux par catégorie -->
    <?php if (!empty($listByCategory)): ?>
        <?php foreach ($listByCategory as $categoryName => $drivers): ?>
            <div class="category-block"
                    style="--category-color: <?= htmlspecialchars($categoryColors[$categoryName] ?? '#E10600') ?>">

                <h2>
                    <?= htmlspecialchars($categoryName) ?>

                    <?php if (!empty($drivers[0]->platform) || !empty($drivers[0]->videogame)): ?>
                        <span class="season-extra-info">
                            / 
                            <?php if (!empty($drivers[0]->platform)): ?>
                                <?= htmlspecialchars($drivers[0]->platform) ?>
                            <?php endif; ?>

                            <?php if (!empty($drivers[0]->platform) && !empty($drivers[0]->videogame)): ?>
                                – 
                            <?php endif; ?>

                            <?php if (!empty($drivers[0]->videogame)): ?>
                                <?= htmlspecialchars($drivers[0]->videogame) ?>
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>
                </h2>

                <!-- Classement Pilotes -->
                <?php if (!empty($listByCategory[$categoryName])): ?>
                    <h3 style="margin-top:30px;">Classement Pilotes <?= htmlspecialchars($categoryName) ?></h3>

                    <div class="table-responsive">
                        <table class="dashboard-table">
                            <thead>
                                <tr>
                                    <th>Pos</th><th>Pilote</th><th>Équipe</th><th>Points</th><th>GP</th>
                                    <th>Victoires</th><th>Podiums</th><th>Pole</th><th>FastestLap</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $position = 1; ?>
                                <?php foreach ($listByCategory[$categoryName] as $row): ?>
                                    <tr>
                                        <td><?= podiumBadge($position++) ?></td>

                                        <!-- Pilote -->
                                        <td class="driver-cell" style="--team-color: <?= htmlspecialchars($row->team_color ?? '') ?>">
                                            <div class="driver-gradient"></div>
                                            <span class="driver-content">
                                                <?php if (!empty($row->driver_flag ?? null)): ?>
                                                    <img src="<?= htmlspecialchars($row->driver_flag) ?>" class="driver-flag" alt="flag">
                                                <?php endif; ?>
                                                <?= htmlspecialchars($row->nickname) ?>
                                            </span>
                                        </td>

                                        <!-- Équipe -->
                                        <td class="team-cell" style="background: <?= htmlspecialchars($row->team_color ?? '') ?>">
                                            <?php if (!empty($row->team_flag ?? null)): ?>
                                                <img src="<?= htmlspecialchars($row->team_flag) ?>" class="driver-flag" alt="flag">
                                            <?php endif; ?>
                                            <?php if (!empty($row->team_logo ?? null)): ?>
                                                <img src="<?= htmlspecialchars($row->team_logo) ?>" class="team-logo" alt="logo">
                                            <?php endif; ?>
                                            <span class="team-name"><?= htmlspecialchars($row->team_name ?? '') ?></span>
                                        </td>

                                        <td><?= htmlspecialchars(rtrim(rtrim(number_format($row->total_points ?? 0, 1, '.', ''), '0'), '.')) ?></td>
                                        <td><?= htmlspecialchars($row->gp_count ?? 0) ?></td>
                                        <td><?= htmlspecialchars($row->wins ?? 0) ?></td>
                                        <td><?= htmlspecialchars($row->podiums ?? 0) ?></td>
                                        <td><?= htmlspecialchars($row->pole_count ?? 0) ?></td>
                                        <td><?= htmlspecialchars($row->fastestlap_count ?? 0) ?></td>
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
                        <table class="dashboard-table">
                            <thead>
                                <tr><th>Pos</th><th>Équipe</th><th>Points</th></tr>
                            </thead>
                            <tbody>
                                <?php $teamPos = 1; ?>
                                <?php foreach ($teamsByCategory[$categoryName] as $team): ?>
                                    <tr>
                                        <td><?= podiumBadge($teamPos++) ?></td>
                                        <td class="team-cell" style="background: <?= htmlspecialchars($team->team_color ?? '') ?>">
                                            <?php if (!empty($team->team_flag ?? null)): ?>
                                                <img src="<?= htmlspecialchars($team->team_flag) ?>" class="driver-flag" alt="flag">
                                            <?php endif; ?>
                                            <?php if (!empty($team->team_logo ?? null)): ?>
                                                <img src="<?= htmlspecialchars($team->team_logo) ?>" class="team-logo" alt="logo">
                                            <?php endif; ?>
                                            <span class="team-name"><?= htmlspecialchars($team->team_name ?? '') ?></span>
                                        </td>
                                        <td><?= htmlspecialchars(rtrim(rtrim(number_format($team->total_points ?? 0, 1, '.', ''), '0'), '.')) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Pénalités -->
                <?php if (!empty($penaltiesByCategory[$categoryName])): ?>
                    <h3 style="margin-top:30px;">Pénalités <?= htmlspecialchars($categoryName) ?></h3>

                    <div class="table-responsive">
                        <table class="dashboard-table">
                            <thead>
                                <tr>
                                    <th>GP</th>
                                    <th>Pilote</th>
                                    <th>Équipe</th>
                                    <th>Points retirés</th>
                                    <th>Commentaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($penaltiesByCategory[$categoryName] as $p): ?>
                                    <tr>
                                        <!-- GP avec flag -->
                                        <td>
                                            <?php if (!empty($p->country_flag)): ?>
                                                <img src="<?= htmlspecialchars($p->country_flag) ?>" class="driver-flag" alt="flag">
                                            <?php endif; ?>
                                            GP <?= htmlspecialchars($p->gp_ordre ?? '') ?> - <?= htmlspecialchars($p->circuit_name ?? '') ?>
                                        </td>

                                        <!-- Pilote avec gradient -->
                                        <td class="driver-cell" style="--team-color: <?= htmlspecialchars($p->team_color ?? '') ?>">
                                            <div class="driver-gradient"></div>
                                            <span class="driver-content">
                                                <?php if (!empty($p->driver_flag ?? null)): ?>
                                                    <img src="<?= htmlspecialchars($p->driver_flag) ?>" class="driver-flag" alt="flag">
                                                <?php endif; ?>
                                                <?= htmlspecialchars($p->driver_name ?? '') ?>
                                            </span>
                                        </td>

                                        <!-- Équipe avec background couleur -->
                                        <td class="team-cell" style="background: <?= htmlspecialchars($p->team_color ?? '') ?>">
                                            <?php if (!empty($p->team_logo ?? null)): ?>
                                                <img src="<?= htmlspecialchars($p->team_logo) ?>" class="team-logo" alt="logo">
                                            <?php endif; ?>
                                            <span class="team-name"><?= htmlspecialchars($p->team_name ?? '') ?></span>
                                        </td>

                                        <!-- Points retirés -->
                                        <td class="penalty-points"><?= htmlspecialchars($p->points_removed ?? 0) ?></td>

                                        <!-- Commentaire -->
                                        <td><?= nl2br(htmlspecialchars($p->comment ?? '')) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

                <!-- Résultats GP -->
                <?php if (!empty($gpByCategory[$categoryName])): ?>
                    <h3 style="margin-top:30px;">Résultats GP <?= htmlspecialchars($categoryName) ?></h3>
                    <button class="gp-toggle-btn-category" data-category="<?= htmlspecialchars($categoryName) ?>">Afficher tous les GP</button>

                    <?php foreach ($gpByCategory[$categoryName] as $gp): ?>
                        <?php $top3 = json_decode($gp->top3 ?? '[]'); ?>
                        <div class="gp-content gp-category-<?= htmlspecialchars($categoryName) ?>">
                            <div class="table-responsive">

                                <!-- Titre GP avec le drapeau -->
                                <h4>
                                    <?php if (!empty($gp->country_flag)): ?>
                                        <img src="<?= htmlspecialchars($gp->country_flag) ?>" class="driver-flag" alt="flag">
                                    <?php endif; ?>

                                    GP <?= htmlspecialchars($gp->gp_ordre) ?>
                                    - <?= htmlspecialchars($gp->circuit_name ?? '') ?> (<?= htmlspecialchars($gp->country_name ?? '') ?>)
                                    / <?= $gp->total_gp ?>
                                </h4>

                                <table class="dashboard-table">
                                    <thead>
                                        <tr>
                                            <th>Pos</th><th>Pilote</th><th>Équipe</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $pos = 1; ?>
                                        <?php foreach ($top3 as $entry): ?>
                                        <tr>
                                            <td><?= podiumBadge($pos++) ?></td>

                                            <!-- Pilote avec dégradé + flag -->
                                            <td class="driver-cell" style="--team-color: <?= htmlspecialchars($entry->team_color ?? '') ?>">
                                                <div class="driver-gradient"></div>
                                                <span class="driver-content">
                                                    <?php if (!empty($entry->driver_flag ?? null)): ?>
                                                        <img src="<?= htmlspecialchars($entry->driver_flag) ?>" class="driver-flag" alt="flag">
                                                    <?php endif; ?>
                                                    <?= htmlspecialchars($entry->nickname ?? '') ?>
                                                </span>
                                            </td>

                                            <!-- Équipe -->
                                            <td class="team-cell" style="background: <?= htmlspecialchars($entry->team_color ?? '') ?>">
                                                <?php if (!empty($entry->team_logo ?? null)): ?>
                                                    <img src="<?= htmlspecialchars($entry->team_logo) ?>" class="team-logo-gp" alt="logo">
                                                <?php endif; ?>
                                                <span class="team-name"><?= htmlspecialchars($entry->team_name ?? '') ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <p><strong>Pole :</strong> <?= htmlspecialchars($gp->pole_driver ?? '') ?> <?= !empty($gp->pole_time) ? "({$gp->pole_time})" : "" ?></p>
                                <p><strong>Fastest Lap :</strong> <?= htmlspecialchars($gp->fastest_lap_driver ?? '') ?> <?= !empty($gp->fastest_lap_time) ? "({$gp->fastest_lap_time})" : "" ?></p>

                                <a href="#" class="gp-details-link" data-gp-id="<?= $gp->id ?>">Afficher les résultats complets</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
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

/* Ouvre le modal avec détails GP */
document.querySelectorAll('.gp-details-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const gpId = this.dataset.gpId;
        fetch(`index.php?controller=classements&action=gpDetails&gp_id=${gpId}`)
            .then(res => res.text())
            .then(html => {
                document.getElementById('gp-modal-body').innerHTML = html;
                document.getElementById('gp-modal').style.display = 'block';
            });
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

