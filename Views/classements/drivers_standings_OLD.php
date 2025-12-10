<?php
// ============================================
// drivers_standings.php
// ============================================
?>
<?php $title = 'Classement Pilotes'; ?>
<div class="section-dashboard">
    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=classements&action=index">Retour</a>
        <h1>Classement Pilotes</h1>

        <form method="get" style="margin-top: 10px;">
            <input type="hidden" name="controller" value="classements">
            <input type="hidden" name="action" value="driversStandings">
            <label for="season_filter">Afficher saison :</label>
            <select name="season_id" id="season_filter" onchange="this.form.submit()">

                <!-- Actuelle -->
                <option value="active" <?= $seasonFilter === 'active' ? 'selected' : '' ?>>Actuelle</option>

                <?php
                // Grouper les saisons par catégorie
                $categories = [];
                foreach ($seasons as $season) {
                    $categories[$season->category][] = $season;
                }

                foreach ($categories as $categoryName => $categorySeasons): ?>
                    <optgroup label="<?= htmlspecialchars($categoryName) ?>">
                        <?php foreach ($categorySeasons as $season): ?>
                            <?php if ($season->status === 'desactive'): ?>
                                <option value="<?= $season->season_id ?>" <?= $seasonFilter == $season->season_id ? 'selected' : '' ?>>
                                    Saison <?= htmlspecialchars($season->season_number) ?> - <?= htmlspecialchars($season->videogame) ?> - <?= htmlspecialchars($season->platform) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
        </form>

    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Saison</th>
                    <th>Catégorie</th>
                    <th>Pilote</th>
                    <th>Team</th>
                    <th>Points</th>
                    <th>Victoires</th>
                    <th>Podiums</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($list)): ?>
                    <?php foreach ($list as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row->season_number) ?></td>
                            <td><?= htmlspecialchars($row->category ?? '-') ?></td>
                            <td><?= htmlspecialchars($row->nickname) ?></td>
                            <td><?= htmlspecialchars($row->team_name ?? '-') ?></td>
                            <td>
                                <?= htmlspecialchars(rtrim(rtrim(number_format($row->total_points ?? 0, 1, '.', ''), '0'), '.')) ?>
                            </td>
                            <td><?= htmlspecialchars($row->wins ?? 0) ?></td>
                            <td><?= htmlspecialchars($row->podiums ?? 0) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center;">Aucun pilote trouvé pour cette saison.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
