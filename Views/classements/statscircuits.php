<?php $title = "Team-eRacing - Circuits"; ?>

<div class="section-dashboard">

    <a class="nav-btn" href="/classements/standings">Retour aux Classements</a>
    <a class="nav-btn red" href="/palmares">Palmarès</a>

    <div class="page-header">
        <h1>Circuits</h1>
    </div>

    <!-- SÉLECTEUR DE CIRCUIT -->
    <form method="get" class="circuit-selector" action="/statscircuits/index">
        <input type="hidden" name="controller" value="statscircuits">
        <input type="hidden" name="action" value="index">

        <label for="circuit_id" class="visually-hidden">Choisir un circuit :</label>
        <div class="form-group">
            <select name="circuit_id" id="circuit_filter" onchange="this.form.submit()">
                <option value="0" <?= ($circuitId ?? 0) == 0 ? 'selected' : '' ?>>Choisir un circuit :</option>
                <?php foreach ($circuits as $c): ?>
                    <option value="<?= $c->id ?>" <?= ($circuitId ?? 0) == $c->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c->name) ?> - <?= htmlspecialchars($c->country_code) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <h2 class="category-title <?= $selectedCircuit ? 'has-content' : '' ?>">
        <?php if ($selectedCircuit): ?>
            <div class="selected-circuit">
                <img src="<?= htmlspecialchars($selectedCircuit->country_flag) ?>" alt="<?= htmlspecialchars($selectedCircuit->country) ?>" class="circuit-flag">
                <span class="circuit-title">
                    <?= htmlspecialchars($selectedCircuit->name) ?>
                    - <?= htmlspecialchars($selectedCircuit->country_name) ?>
                </span>
            </div>
        <?php endif; ?>
    </h2>

    <?php if (!empty($circuitId)): ?>

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

        <!--  TOP 10 CHRONOS  -->
        <?php if (!empty($topChronos)) : ?>
        <h3 class="gp-title">Top 10</h3>

        <p class="gp-subtitle">
            <i class="fa-solid fa-circle-chevron-right"></i> Cliquez sur le nom du Pilote pour voir son historique
        </p>

        <div class="table-responsive">
        <table class="dashboard-table fix table-th-responsive circuits-top10-table">
            <thead>
                <tr>
                    <th class="badge-width no-sort th-responsive" title="Position">
                        <span class="label-aria">Position</span>
                        <span aria-hidden="true" class="label-long"></span>
                        <span aria-hidden="true" class="label-medium"></span>
                        <span aria-hidden="true" class="label-short"></span>
                    </th>
                    <th class="th-responsive" title="Pilote">
                        <span class="label-aria">Pilote</span>
                        <span aria-hidden="true" class="label-long">Pilote</span>
                        <span aria-hidden="true" class="label-medium">Pilote</span>
                        <span aria-hidden="true" class="label-short">Pilote</span>
                    </th>
                    <th class="text-center th-responsive" title="Chrono">
                        <span class="label-aria">Chrono</span>
                        <span aria-hidden="true" class="label-long">Chrono</span>
                        <span aria-hidden="true" class="label-medium">Chrono</span>
                        <span aria-hidden="true" class="label-short">Chrono</span>
                    </th>
                    <th class="text-center th-responsive" title="Type">
                        <span class="label-aria">Type</span>
                        <span aria-hidden="true" class="label-long">Type</span>
                        <span aria-hidden="true" class="label-medium">Type</span>
                        <span aria-hidden="true" class="label-short">Type</span>
                    </th>
                    <th class="text-center th-responsive" title="Catégrorie">
                        <span class="label-aria">Catégorie</span>
                        <span aria-hidden="true" class="label-long">Catégorie</span>
                        <span aria-hidden="true" class="label-medium">Cat</span>
                        <span aria-hidden="true" class="label-short">Cat</span>
                    </th>
                    <th class="text-center th-responsive" title="Saison">
                        <span class="label-aria">Saison</span>
                        <span aria-hidden="true" class="label-long">Saison</span>
                        <span aria-hidden="true" class="label-medium">Sai</span>
                        <span aria-hidden="true" class="label-short">Sai</span>
                    </th>
                    <th class="text-center th-responsive" title="Jeu vidéo">
                        <span class="label-aria">Jeu vidéo</span>
                        <span aria-hidden="true" class="label-long">Jeu vidéo</span>
                        <span aria-hidden="true" class="label-medium">Jeu vidéo</span>
                        <span aria-hidden="true" class="label-short">Jeu</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topChronos as $i => $chrono): ?>
                    <tr>
                        <td class="badge-width" title="Position"><?= podiumBadge($i + 1) ?></td>
                        <td class="driver-cell palmares-page driver-name down" title="Pilote">
                            <a href="/statsdrivers/index/driver/<?= (int)$chrono->driver_id ?>#pilote" class="driver-cell-link">
                                <?= htmlspecialchars($chrono->nickname) ?>
                            </a>
                        </td>
                        <td class="text-center down " title="Chrono"><span class="badge-purple"><?= htmlspecialchars($chrono->chrono) ?></span></td>
                        <td class="text-center down top10-type" title="Type"><?= htmlspecialchars($chrono->chrono_type) ?></td>
                        <td class="text-center down" title="Catégorie"><?= htmlspecialchars($chrono->category) ?></td>
                        <td class="text-center down" title="Saison">S<?= htmlspecialchars($chrono->season_number) ?></td>
                        <td class="text-center down" title="Jeu vidéo"><?= htmlspecialchars($chrono->videogame)?> <?= htmlspecialchars($chrono->platform)?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>

        <!--  GP PAR CATÉGORIE  -->
        <h3 class="gp-title">Courses disputées</h3>

        <div class="table-responsive">
        <table class="dashboard-table fix table-th-responsive">
            <thead>
                <tr>
                    <th class="text-center" title="Catégorie">Catégorie</th>
                    <th class="text-center" title="Courses disputées">Courses disputées</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gpCountByCategory as $row): ?>
                    <tr>
                        <td class="text-center down" title="Catégorie"><?= htmlspecialchars($row->category) ?></td>
                        <td class="text-center" title="Courses disputées"><?= $row->gp_count ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td class="text-center" title="Total">Total</td>
                    <td class="text-center" title="Total courses disputées"><?= $totalGP ?></td>
                </tr>
            </tbody>
        </table>
        </div>
        <!--  CLASSEMENT PILOTES  -->
        <h3 class="gp-title">Classement Pilotes</h3>

        <p class="gp-subtitle">
            <i class="fa-solid fa-circle-chevron-right"></i> Cliquez sur les colonnes pour trier
        </p>
        <p class="gp-subtitle">
            <i class="fa-solid fa-circle-chevron-right"></i> Cliquez sur le nom du Pilote pour voir son historique
        </p>

        <div class="table-responsive">
        <table class="dashboard-table sortable fix table-th-responsive circuits-drivers-table">
            <thead>
                <tr>
                    <th class="badge-width no-sort th-responsive" title="Position">
                        <span class="label-aria">Position</span>
                        <span aria-hidden="true" class="label-long"></span>
                        <span aria-hidden="true" class="label-medium"></span>
                        <span aria-hidden="true" class="label-short"></span>
                    </th>
                    <th title="Pilote">Pilote</th>
                    <th class="text-center th-responsive" title="Courses">
                            <span class="label-aria">Courses</span>
                            <span aria-hidden="true" class="label-long">Courses</span>
                            <span aria-hidden="true" class="label-medium">Courses</span>
                            <span aria-hidden="true" class="label-short">Cou</span>
                    </th>
                    <th class="text-center th-responsive" title="Victoires">
                            <span class="label-aria">Victoires</span>
                            <span aria-hidden="true" class="label-long">Victoires</span>
                            <span aria-hidden="true" class="label-medium">Victoires</span>
                            <span aria-hidden="true" class="label-short">Vic</span>
                    </th>
                    <th class="text-center th-responsive" title="Podiums">
                            <span class="label-aria">Podiums</span>
                            <span aria-hidden="true" class="label-long">Podiums</span>
                            <span aria-hidden="true" class="label-medium">Podiums</span>
                            <span aria-hidden="true" class="label-short">Pod</span>
                    </th>
                    <th class="text-center th-responsive" title="Pole Position">
                            <span class="label-aria">Pole Position</span>
                            <span aria-hidden="true" class="label-long">Pole Position</span>
                            <span aria-hidden="true" class="label-medium">Pole Pos</span>
                            <span aria-hidden="true" class="label-short">PP</span>
                    </th>
                    <th class="text-center th-responsive" title="Fastest Lap">
                            <span class="label-aria">Fastest Lap</span>
                            <span aria-hidden="true" class="label-long">Fastest Lap</span>
                            <span aria-hidden="true" class="label-medium">Fast Lap</span>
                            <span aria-hidden="true" class="label-short">FL</span>
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($driversStats as $i => $d): ?>
                <tr>
                    <td class="badge-width" title="Position"><?= podiumBadge($i + 1) ?></td>
                    <td class="driver-cell palmares-page driver-name down" title="Pilote">
                        <a href="/statsdrivers/index/driver/<?= (int)$d->driver_id ?>#pilote" class="driver-cell-link">
                            <?= htmlspecialchars($d->nickname) ?>
                        </a>
                    </td>
                    <td class="text-center" title="Courses"><?= $d->gp_count ?></td>
                    <td class="text-center" title="Victoires"><?= $d->wins ?></td>
                    <td class="text-center" title="Podiums"><?= $d->podiums ?></td>
                    <td class="text-center" title="Pole Position"><?= $d->poles ?></td>
                    <td class="text-center" title="Fastest Lap"><?= $d->fastest_laps ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>

    <?php endif; ?>

</div>

<!--  TRI JS (IDENTIQUE AU PALMARÈS)  -->
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

        // === TRI AUTOMATIQUE au chargement sur Victoires ===
        const victoriesHeader = table.querySelectorAll('th')[3];
        if(victoriesHeader) victoriesHeader.click(); // déclenche le tri

    });

    function parseValue(value) {
        if (value === '') return Number.NEGATIVE_INFINITY;
        const num = value.replace(',', '.');
        if (!isNaN(num)) return parseFloat(num);
        return value.toLowerCase();
    }

    function updateResponsiveNames() {
        const w = window.innerWidth;


        /*  TYPE POLE POSITION OU FASTEST LAP (Circuits TOP 10)  */
        document.querySelectorAll('.top10-type').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
            }

            let full = el.dataset.fullname;

            // Appliquer la valeur abrégée dans data pour la conserver
            el.dataset.fullname = full;

            if (w <= 500) {
                if (full === "Pole Position") {
                    full = "PP";
                } else if (full === "Fastest Lap") {
                    full = "FL";
                }
                el.textContent = full.substring(0, 2);
            }
            else if (w <= 1050) {
                if (full === "Pole Position") {
                    full = "PoleP";
                } else if (full === "Fastest Lap") {
                    full = "FastL";
                }
                el.textContent = full.substring(0, 5);
            }
            else {
                el.textContent = full.substring(0, 20);
            }
        });

    }
    window.addEventListener('resize', updateResponsiveNames);
    updateResponsiveNames();
});
</script>
