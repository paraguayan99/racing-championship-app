<?php $title = "Palmarès"; ?>

<div class="section-dashboard">
    <a class="nav-btn" href="index.php?controller=classements&action=standings">Retour</a>

    <h1>Palmarès</h1>

<?php foreach ($driversByCategory as $category => $drivers): ?>
<div class="category-block"
     style="--category-color: <?= htmlspecialchars($drivers[0]->category_color) ?>">

    <h2><?= htmlspecialchars($category) ?></h2>

    <!-- DRIVERS -->
    <h3>Palmarès Pilotes</h3>
    <table class="dashboard-table sortable">
        <thead>
            <tr>
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
        <?php foreach ($drivers as $d): ?>
            <tr>
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
                <th>Équipe</th>
                <th>Titres</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($teamsByCategory[$category] as $t): ?>
            <tr>
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

    document.querySelectorAll('table.sortable th').forEach(header => {

        let asc = false;

        header.addEventListener('click', () => {

            const table = header.closest('table');
            const tbody = table.querySelector('tbody');

            // 🔹 UX : reset des indicateurs de tri sur les autres colonnes
            table.querySelectorAll('th').forEach(th => {
                if (th !== header) {
                    th.classList.remove('asc', 'desc');
                }
            });

            // index réel de la colonne dans CE tableau
            const columnIndex = Array.from(header.parentNode.children).indexOf(header);

            const rows = Array.from(tbody.querySelectorAll('tr'));

            rows.sort((a, b) => {

                const cellA = a.children[columnIndex]?.innerText.trim() ?? '';
                const cellB = b.children[columnIndex]?.innerText.trim() ?? '';

                const valA = parseValue(cellA);
                const valB = parseValue(cellB);

                if (valA < valB) return asc ? -1 : 1;
                if (valA > valB) return asc ? 1 : -1;
                return 0;
            });

            // 🔹 UX : mise à jour du sens de tri visible
            header.classList.toggle('asc', asc);
            header.classList.toggle('desc', !asc);

            asc = !asc;

            rows.forEach(row => tbody.appendChild(row));
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

