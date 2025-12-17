<?php $title = "Statistiques par circuit"; ?>

<div class="section-dashboard">

    <a class="nav-btn" href="index.php?controller=classements&action=standings">Retour aux Classements</a>
    <a class="nav-btn red" href="index.php?controller=palmares">Palmarès</a>

    <h1>Statistiques par circuit</h1>

    <!-- SÉLECTEUR DE CIRCUIT -->
    <form method="get" class="circuit-selector">
        <input type="hidden" name="controller" value="statscircuits">
        <input type="hidden" name="action" value="index">

        <label for="circuit_id">Choisir un circuit :</label>
        <select name="circuit_id" onchange="this.form.submit()">
            <option value="">-- Choisir un circuit --</option>
            <?php foreach ($circuits as $c): ?>
                <option value="<?= $c->id ?>" <?= ($circuitId ?? null) == $c->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c->name) ?> (<?= htmlspecialchars($c->country) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <h2>
        <?php if ($selectedCircuit): ?>
        <div class="selected-circuit">
            <img src="<?= htmlspecialchars($selectedCircuit->country_flag) ?>" alt="<?= htmlspecialchars($selectedCircuit->country) ?>" class="circuit-flag">
            <strong><?= htmlspecialchars($selectedCircuit->name) ?></strong> – <?= htmlspecialchars($selectedCircuit->country) ?>
        </div>
        <?php endif; ?>
    </h2>

    <?php if (!empty($circuitId)): ?>

    <?php
        function podiumBadge($pos) {
            return match($pos) {
                1 => '<span class="badge badge-gold">1</span>',
                2 => '<span class="badge badge-silver">2</span>',
                3 => '<span class="badge badge-bronze">3</span>',
                default => '<span class="badge badge-normal">' . $pos . '</span>',
            };
        }
    ?>

        <!-- ================= TOP 10 CHRONOS ================= -->
        <h2>Top 10 Chronos</h2>

        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Pos</th>
                    <th>Pilote</th>
                    <th>Chrono</th>
                    <th>Type</th>
                    <th>Catégorie</th>
                    <th>Saison</th>
                    <th>Jeu</th>
                    <th>Plateforme</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topChronos as $i => $chrono): ?>
                    <tr>
                        <td><?= podiumBadge($i + 1) ?></td>
                        <td><?= htmlspecialchars($chrono->nickname) ?></td>
                        <td><span class="badge-purple"><?= htmlspecialchars($chrono->chrono) ?></span></td>
                        <td><?= htmlspecialchars($chrono->chrono_type) ?></td>
                        <td><?= htmlspecialchars($chrono->category) ?></td>
                        <td><?= htmlspecialchars($chrono->season_number) ?></td>
                        <td><?= htmlspecialchars($chrono->videogame) ?></td>
                        <td><?= htmlspecialchars($chrono->platform) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- ================= GP PAR CATÉGORIE ================= -->
        <h2>Nombre de courses disputées par catégorie</h2>

        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Catégorie</th>
                    <th>Courses disputées</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gpCountByCategory as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row->category) ?></td>
                        <td><?= $row->gp_count ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td><strong>Total</strong></td>
                    <td><strong><?= $totalGP ?></strong></td>
                </tr>
            </tbody>
        </table>

        <!-- ================= CLASSEMENT PILOTES ================= -->
        <h2>Classement pilotes</h2>

        <table class="dashboard-table sortable">
            <thead>
                <tr>
                    <th class="no-sort">Pos</th>
                    <th>Pilote</th>
                    <th>GP</th>
                    <th>Victoires</th>
                    <th>Podiums</th>
                    <th>Poles</th>
                    <th>Fastest Laps</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($driversStats as $i => $d): ?>
                <tr>
                    <td><?= podiumBadge($i + 1) ?></td>
                    <td><?= htmlspecialchars($d->nickname) ?></td>
                    <td><?= $d->gp_count ?></td>
                    <td><?= $d->wins ?></td>
                    <td><?= $d->podiums ?></td>
                    <td><?= $d->poles ?></td>
                    <td><?= $d->fastest_laps ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</div>

<!-- ================= TRI JS (IDENTIQUE AU PALMARÈS) ================= -->
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

        // === TRI AUTOMATIQUE au chargement sur Victoires (col index 3) ===
        const victoriesHeader = table.querySelectorAll('th')[3];
        if(victoriesHeader) victoriesHeader.click(); // déclenche le tri

    });

    function parseValue(value) {
        if (value === '') return Number.NEGATIVE_INFINITY;
        const num = value.replace(',', '.');
        if (!isNaN(num)) return parseFloat(num);
        return value.toLowerCase();
    }

});
</script>
