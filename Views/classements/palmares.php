<?php $title = "Palmarès"; ?>

<div class="section-dashboard">
    <a class="nav-btn" href="index.php?controller=classements&action=standings">Retour aux Classements</a>
    <a class="nav-btn red" href="index.php?controller=statscircuits">Circuits</a>

    <h1>Palmarès</h1>

<?php
// Fonction PHP pour les badges de position
function podiumBadge($pos) {
    return match($pos) {
        1 => '<span class="badge badge-gold">1</span>',
        2 => '<span class="badge badge-silver">2</span>',
        3 => '<span class="badge badge-bronze">3</span>',
        default => '<span class="badge badge-normal">' . $pos . '</span>',
    };
}
?>

<?php foreach ($driversByCategory as $category => $drivers): ?>
<div class="category-block"
     style="--category-color: <?= htmlspecialchars($drivers[0]->category_color) ?>">

    <h2><?= htmlspecialchars($category) ?></h2>

    <!-- DRIVERS -->
    <h3>Palmarès Pilotes</h3>
    <table class="dashboard-table sortable">
        <thead>
            <tr>
                <th>Pos</th>
                <th>Pilote</th>
                <th>Titres</th>
                <th>Vice-Champion</th>
                <th>3e</th>
                <th>Victoires</th>
                <th>Podiums</th>
                <th>GP</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($drivers as $i => $d): ?>
            <tr>
                <td><?= podiumBadge($i + 1) ?></td>
                <td><?= htmlspecialchars($d->nickname) ?></td>
                <td><?= $d->titles ?></td>
                <td><?= $d->vice_titles ?></td>
                <td><?= $d->third_places ?></td>
                <td><?= $d->wins ?></td>
                <td><?= $d->podiums ?></td>
                <td><?= htmlspecialchars($d->total_gp ?? 0) ?></td>
                <td><?= htmlspecialchars(rtrim(rtrim(number_format($d->total_points ?? 0, 1, '.', ''),'0'),'.')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- TEAMS -->
    <?php if (!empty($teamsByCategory[$category])): ?>
    <h3>Palmarès Équipes</h3>
    <table class="dashboard-table sortable">
        <thead>
            <tr>
                <th>Pos</th>
                <th>Équipe</th>
                <th>Titres</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($teamsByCategory[$category] as $i => $t): ?>
            <tr>
                <td><?= podiumBadge($i + 1) ?></td>
                <td><?= htmlspecialchars($t->team_name) ?></td>
                <td><?= $t->titles ?></td>
                <td><?= htmlspecialchars(rtrim(rtrim(number_format($t->total_points ?? 0, 1, '.', ''),'0'),'.')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

</div>
<?php endforeach; ?>

</div>

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

                table.querySelectorAll('th').forEach(th => {
                    if (th !== header) th.classList.remove('asc', 'desc');
                });

                if(columnIndex === 0) return; // ignore colonne Pos

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

                // recalculer badges Pos
                rows.forEach((row, index) => {
                    row.children[0].innerHTML = podiumBadge(index + 1);
                });

                header.classList.toggle('asc', ascStates[columnIndex]);
                header.classList.toggle('desc', !ascStates[columnIndex]);

                ascStates[columnIndex] = !ascStates[columnIndex];
            });

        });

    });

    function parseValue(value) {
        if (value === '') return Number.NEGATIVE_INFINITY;
        const num = value.replace(',', '.');
        if (!isNaN(num)) return parseFloat(num);
        return value.toLowerCase();
    }

});
</script>


