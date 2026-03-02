<?php $title = "Team-eRacing - Palmarès"; ?>

<div class="section-dashboard">
    <a class="nav-btn" href="/classements/standings">Retour aux Classements</a>
    <a class="nav-btn red" href="/statscircuits">Circuits</a>

    <div class="page-header">
        <h1>Palmarès</h1>
    </div>

    <!-- SÉLECTEUR DE CATÉGORIE -->
    <form method="get" action="/palmares/index">
        <input type="hidden" name="controller" value="palmares">
        <input type="hidden" name="action" value="index">

        <label for="category_filter" class="visually-hidden">Choisir une catégorie :</label>
        <div class="form-group">
            <select name="category_name" id="category_filter" onchange="this.form.submit()">
                <option value="" <?= !$categoryFilter ? 'selected' : '' ?>>Choisir une catégorie :</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= htmlspecialchars($c->name) ?>" <?= $categoryFilter === $c->name ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

<?php
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
?>

<?php foreach ($driversByCategory as $category => $drivers): ?>
    <div class="category-block"
        style="--category-color: <?= htmlspecialchars($drivers[0]->category_color) ?>">

        <h2 class="category-title has-content">
            <span>
                <?= htmlspecialchars($category) ?>
            </span>
        </h2>

        <!-- TEAMS -->
        <?php if (!empty($teamsByCategory[$category])): ?>
            <h3 class="gp-title">Palmarès Constructeurs</h3>

            <p class="gp-subtitle">
                Cliquez sur les colonnes pour trier
            </p>

            <div class="table-responsive">
                <table class="dashboard-table fix sortable table-th-responsive palmares-teams-table">
                    <thead>
                        <tr>
                            <th class="badge-width no-sort th-responsive" title="Position">
                                <span class="label-aria">Position</span>
                            </th>
                            <th title="Équipe">Équipe</th>
                            <th class="text-center th-responsive" title="Champions">
                                <span class="label-aria">Champions</span>
                                <i class="fa-solid fa-medal icon-champion" aria-hidden="true"></i>
                            </th>
                            <th class="text-center th-responsive" title="Points">
                                Points
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($teamsByCategory[$category] as $i => $t): ?>
                        <tr>
                            <td class="badge-width down" title="Position"><?= podiumBadge($i + 1) ?></td>
                            <td class="team-name down" title="Équipe"><?= htmlspecialchars($t->team_name) ?></td>
                            <td class="text-center" title="Champions"><?= $t->titles ?></td>
                            <td class="text-center" title="Points"><?= htmlspecialchars(rtrim(rtrim(number_format($t->total_points ?? 0, 1, '.', ''),'0'),'.')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <!-- DRIVERS -->
        <h3 class="gp-title">Palmarès Pilotes</h3>
        
        <p class="gp-subtitle">
            Cliquez sur les colonnes pour trier
        </p>

        <div class="table-responsive">
            <table class="dashboard-table fix sortable table-th-responsive palmares-drivers-table">
                <thead>
                    <tr>
                        <th class="badge-width no-sort th-responsive" title="Position">
                            <span class="label-aria">Position</span>
                        </th>
                        <th title="Pilote">Pilote</th>
                        <th class="text-center th-responsive down" title="Champions">
                            <span class="label-aria">Champions</span>
                            <i class="fa-solid fa-medal icon-champion" aria-hidden="true"></i>
                        </th>
                        <th class="text-center th-responsive" title="Vice-Champions">
                            <span class="label-aria">Vice-Champions</span>
                            <i class="fa-solid fa-medal icon-second" aria-hidden="true"></i>
                        </th>
                        <th class="text-center th-responsive" title="Troisièmes">
                            <span class="label-aria">Troisièmes</span>
                            <i class="fa-solid fa-medal icon-third" aria-hidden="true"></i>
                        </th>
                        <th class="text-center th-responsive" title="Victoires">
                            <span class="label-aria">Victoires</span>
                            <span aria-hidden="true" class="label-long">Victoires</span>
                            <span aria-hidden="true" class="label-medium">Vic</span>
                            <span aria-hidden="true" class="label-short">Vic</span>
                        </th>
                        <th class="text-center th-responsive" title="Podiums">
                            <span class="label-aria">Podiums</span>
                            <span aria-hidden="true" class="label-long">Podiums</span>
                            <span aria-hidden="true" class="label-medium">Pod</span>
                            <span aria-hidden="true" class="label-short">Pod</span>
                        </th>
                        <th class="text-center" title="Grands Prix">GP</th>
                        <th class="text-center th-responsive" title="Points">
                            <span class="label-aria">Points</span>
                            <span aria-hidden="true" class="label-long">Points</span>
                            <span aria-hidden="true" class="label-medium">Pts</span>
                            <span aria-hidden="true" class="label-short">Pts</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($drivers as $i => $d): ?>
                    <tr>
                        <td class="badge-width down" title="Position"><?= podiumBadge($i + 1) ?></td>
                        <td class="driver-name down" title="Pilote"><?= htmlspecialchars($d->nickname) ?></td>
                        <td class="text-center" title="Champions"><?= $d->titles ?></td>
                        <td class="text-center" title="Vice-Champions"><?= $d->vice_titles ?></td>
                        <td class="text-center" title="Troisièmes"><?= $d->third_places ?></td>
                        <td class="text-center" title="Victoires"><?= $d->wins ?></td>
                        <td class="text-center" title="Podiums"><?= $d->podiums ?></td>
                        <td class="text-center" title="Grands Prix"><?= htmlspecialchars($d->total_gp ?? 0) ?></td>
                        <td class="text-center" title="Points"><?= htmlspecialchars(rtrim(rtrim(number_format($d->total_points ?? 0, 1, '.', ''),'0'),'.')) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
<?php endforeach; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const podiumBadge = (pos) => {
        switch(pos) {
            case 1: return '<span class="badge badge-gold">1</span>';
            case 2: return '<span class="badge badge-silver">2</span>';
            case 3: return '<span class="badge badge-bronze">3</span>';
            default: return '<span class="badge badge-normal">' + pos + '</span>';
        }
    };

    const sortableTables = document.querySelectorAll('table.sortable');

    sortableTables.forEach(table => {

        let ascStates = Array.from(table.querySelectorAll('th')).map(() => false);

        table.querySelectorAll('th').forEach((header, columnIndex) => {

            header.addEventListener('click', () => {

                const tbody = table.querySelector('tbody');

                // retirer highlight et flèches des autres colonnes
                table.querySelectorAll('th').forEach((th, idx) => {
                    if (th !== header) {
                        th.classList.remove('asc', 'desc');
                        tbody.querySelectorAll('tr').forEach(row => row.children[idx].classList.remove('highlight-column'));
                    }
                });

                if(columnIndex === 0) return; // ignore colonne Position

                const rows = Array.from(tbody.querySelectorAll('tr'));

                rows.sort((a, b) => {
                    const cellA = a.children[columnIndex]?.innerText.trim() ?? '';
                    const cellB = b.children[columnIndex]?.innerText.trim() ?? '';

                    const valA = parseValue(cellA);
                    const valB = parseValue(cellB);

                    if (valA < valB) return ascStates[columnIndex] ? -1 : 1;
                    if (valA > valB) return ascStates[columnIndex] ? 1 : -1;
                    return 0;
                });

                rows.forEach(row => tbody.appendChild(row));

                // recalculer badges Position
                rows.forEach((row, index) => {
                    row.children[0].innerHTML = podiumBadge(index + 1);
                });

                // Ajouter highlight à la colonne triée
                rows.forEach(row => row.children[columnIndex].classList.add('highlight-column'));

                header.classList.toggle('asc', ascStates[columnIndex]);
                header.classList.toggle('desc', !ascStates[columnIndex]);

                ascStates[columnIndex] = !ascStates[columnIndex];
            });

        });

    });

    function forceSortDesc(table, columnIndex) {
        const header = table.querySelectorAll('th')[columnIndex];
        if (!header) return;

        // Tant que la colonne n'est pas en DESC, on clique
        let safety = 0;
        while (!header.classList.contains('desc') && safety < 3) {
            header.click();
            safety++;
        }
    }

    // PALMARÈS PILOTES : Titres DESC
    document.querySelectorAll('table.palmares-drivers-table').forEach(table => {
        forceSortDesc(table, 2); // colonne Titres
    });

    // PALMARÈS CONSTRUCTEURS : Titres DESC
    document.querySelectorAll('table.palmares-teams-table').forEach(table => {
        forceSortDesc(table, 2); // colonne Titres
    });

    function parseValue(value) {
        if (value === '') return Number.NEGATIVE_INFINITY;
        const num = value.replace(',', '.');
        if (!isNaN(num)) return parseFloat(num);
        return value.toLowerCase();
    }

});
</script>



