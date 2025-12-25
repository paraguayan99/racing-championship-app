<?php $title = "Statistiques par circuit"; ?>

<div class="section-dashboard">

    <a class="nav-btn" href="index.php?controller=classements&action=standings">Retour aux Classements</a>
    <a class="nav-btn red" href="index.php?controller=palmares">Palmarès</a>

    <div class="page-header">
        <h1>Circuits</h1>
    </div>

    <!-- SÉLECTEUR DE CIRCUIT -->
    <form method="get" class="circuit-selector">
        <input type="hidden" name="controller" value="statscircuits">
        <input type="hidden" name="action" value="index">

        <label for="circuit_id" class="visually-hidden">Choisir un circuit :</label>
        <div class="form-group">
            <select name="circuit_id" onchange="this.form.submit()">
                <option value="">Choisir un circuit :</option>
                <?php foreach ($circuits as $c): ?>
                    <option value="<?= $c->id ?>" <?= ($circuitId ?? null) == $c->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c->name) ?> (<?= htmlspecialchars($c->country) ?>)
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
                    - <?= htmlspecialchars($selectedCircuit->country) ?>
                </span>
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
        <?php if (!empty($topChronos)) : ?>
        <h3 class="gp-title">Top Chronos</h3>

        <p class="gp-subtitle">
        <span class="label-long"></span>
        <span class="label-medium">Cat = Catégorie / Sai = Saison / Cons = Console</span>
        <span class="label-short">Cat = Catégorie / Sai = Saison / Cons = Console</span>
        </p>

        <div class="table-responsive">
        <table class="dashboard-table table-th-responsive circuits-top10-table">
            <thead>
                <tr>
                    <th class="badge-width no-sort th-responsive">
                        <span class="label-aria">Position</span>
                        <span aria-hidden="true" class="label-long"></span>
                        <span aria-hidden="true" class="label-medium"></span>
                        <span aria-hidden="true" class="label-short"></span>
                    </th>
                    <th class="th-responsive">
                        <span class="label-aria">Pilote</span>
                        <span aria-hidden="true" class="label-long">Pilote</span>
                        <span aria-hidden="true" class="label-medium">Pilote</span>
                        <span aria-hidden="true" class="label-short">Pilote</span>
                    </th>
                    <th class="text-center th-responsive">
                        <span class="label-aria">Chrono</span>
                        <span aria-hidden="true" class="label-long">Chrono</span>
                        <span aria-hidden="true" class="label-medium">Chrono</span>
                        <span aria-hidden="true" class="label-short">Chrono</span>
                    </th>
                    <th class="text-center th-responsive">
                        <span class="label-aria">Type</span>
                        <span aria-hidden="true" class="label-long">Type</span>
                        <span aria-hidden="true" class="label-medium">Type</span>
                        <span aria-hidden="true" class="label-short">Type</span>
                    </th>
                    <th class="text-center th-responsive">
                        <span class="label-aria">Catégorie</span>
                        <span aria-hidden="true" class="label-long">Catégorie</span>
                        <span aria-hidden="true" class="label-medium">Cat</span>
                        <span aria-hidden="true" class="label-short">Cat</span>
                    </th>
                    <th class="text-center th-responsive">
                        <span class="label-aria">Saison</span>
                        <span aria-hidden="true" class="label-long">Saison</span>
                        <span aria-hidden="true" class="label-medium">Sai</span>
                        <span aria-hidden="true" class="label-short">Sai</span>
                    </th>
                    <th class="text-center th-responsive">
                        <span class="label-aria">Jeu</span>
                        <span aria-hidden="true" class="label-long">Jeu</span>
                        <span aria-hidden="true" class="label-medium">Jeu</span>
                        <span aria-hidden="true" class="label-short">Jeu</span>
                    </th>
                    <th class="text-center th-responsive">
                        <span class="label-aria">Console</span>
                        <span aria-hidden="true" class="label-long">Console</span>
                        <span aria-hidden="true" class="label-medium">Cons</span>
                        <span aria-hidden="true" class="label-short">Cons</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topChronos as $i => $chrono): ?>
                    <tr>
                        <td class="badge-width"><?= podiumBadge($i + 1) ?></td>
                        <td class="driver-name"><?= htmlspecialchars($chrono->nickname) ?></td>
                        <td class="text-center"><span class="badge-purple"><?= htmlspecialchars($chrono->chrono) ?></span></td>
                        <td class="text-center top10-type"><?= htmlspecialchars($chrono->chrono_type) ?></td>
                        <td class="text-center top10-category-console"><?= htmlspecialchars($chrono->category) ?></td>
                        <td class="text-center"><?= htmlspecialchars($chrono->season_number) ?></td>
                        <td class="text-center top10-videogame"><?= htmlspecialchars($chrono->videogame) ?></td>
                        <td class="text-center top10-category-console"><?= htmlspecialchars($chrono->platform) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>

        <!-- ================= GP PAR CATÉGORIE ================= -->
        <h3 class="gp-title">Courses disputées</h3>

        <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th class="text-center">Catégorie</th>
                    <th class="text-center">Courses disputées</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gpCountByCategory as $row): ?>
                    <tr>
                        <td class="text-center"><?= htmlspecialchars($row->category) ?></td>
                        <td class="text-center"><?= $row->gp_count ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td class="text-center">Total</td>
                    <td class="text-center"><?= $totalGP ?></td>
                </tr>
            </tbody>
        </table>
        </div>
        <!-- ================= CLASSEMENT PILOTES ================= -->
        <h3 class="gp-title">Classement Pilotes</h3>

        <p class="gp-subtitle">
            <span class="label-long"></span>
            <span class="label-medium">Vict = Victoires / Podi = Podiums / PoleP = Pole Position / FastL = Fastest Lap</span>
            <span class="label-short">Vi = Victoires / Po = Podiums / PP = Pole Position / FL = Fastest Lap</span>
        </p>

        <div class="table-responsive">
        <table class="dashboard-table sortable  table-th-responsive circuits-drivers-table">
            <thead>
                <tr>
                    <th class="badge-width no-sort th-responsive">
                        <span class="label-aria">Position</span>
                        <span aria-hidden="true" class="label-long"></span>
                        <span aria-hidden="true" class="label-medium"></span>
                        <span aria-hidden="true" class="label-short"></span>
                    </th>
                    <th>Pilote</th>
                    <th class="text-center">GP</th>
                    <th class="text-center th-responsive">
                            <span class="label-aria">Victoires</span>
                            <span aria-hidden="true" class="label-long">Victoires</span>
                            <span aria-hidden="true" class="label-medium">Vict</span>
                            <span aria-hidden="true" class="label-short">Vi</span>
                    </th>
                    <th class="text-center th-responsive">
                            <span class="label-aria">Podiums</span>
                            <span aria-hidden="true" class="label-long">Podiums</span>
                            <span aria-hidden="true" class="label-medium">Podi</span>
                            <span aria-hidden="true" class="label-short">Po</span>
                    </th>
                    <th class="text-center th-responsive">
                            <span class="label-aria">Pole Position</span>
                            <span aria-hidden="true" class="label-long">Pole Pos</span>
                            <span aria-hidden="true" class="label-medium">PoleP</span>
                            <span aria-hidden="true" class="label-short">PP</span>
                    </th>
                    <th class="text-center th-responsive">
                            <span class="label-aria">Fastest Lap</span>
                            <span aria-hidden="true" class="label-long">Fastest Lap</span>
                            <span aria-hidden="true" class="label-medium">FastL</span>
                            <span aria-hidden="true" class="label-short">FL</span>
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($driversStats as $i => $d): ?>
                <tr>
                    <td class="badge-width"><?= podiumBadge($i + 1) ?></td>
                    <td class="drivers-standings-name"><?= htmlspecialchars($d->nickname) ?></td>
                    <td class="text-center"><?= $d->gp_count ?></td>
                    <td class="text-center"><?= $d->wins ?></td>
                    <td class="text-center"><?= $d->podiums ?></td>
                    <td class="text-center"><?= $d->poles ?></td>
                    <td class="text-center"><?= $d->fastest_laps ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>

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

                // Ajouter highlight à la colonne triée
                rows.forEach(row => row.children[columnIndex].classList.add('highlight-column'));

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

    function updateResponsiveNames() {
        const w = window.innerWidth;

        /* ===== CIRCUIT TITLE ===== */
        document.querySelectorAll('.circuit-title').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
            }

            const full = el.dataset.fullname;

            if (w <= 1400) {
                el.textContent = full.substring(0, 32);
            }
        });

        /* ===== PILOTES (Circuits TOP 10) ===== */
        document.querySelectorAll('.driver-name').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
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

        /* ===== TYPE POLE POSITION OU FASTEST LAP (Circuits TOP 10) ===== */
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

        /* ===== CATEGORIES ET CONSOLE (Circuits TOP 10) ===== */
        document.querySelectorAll('.top10-category-console').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
            }

            const full = el.dataset.fullname;

            if (w <= 500) {
                el.textContent = full.substring(0, 4);
            }
            else if (w <= 700) {
                el.textContent = full.substring(0, 5);
            }
            else if (w <= 900) {
                el.textContent = full.substring(0, 6);
            }
            else {
                el.textContent = full.substring(0, 8);
            }
        });

        /* ===== JEU VIDEO (Circuits TOP 10) ===== */
        document.querySelectorAll('.top10-videogame').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
            }

            const full = el.dataset.fullname;

            if (w <= 500) {
                el.textContent = full.substring(0, 6);
            }
            else if (w <= 700) {
                el.textContent = full.substring(0, 10);
            }
            else if (w <= 900) {
                el.textContent = full.substring(0, 14);
            }
            else {
                el.textContent = full.substring(0, 20);
            }
        });

        /* ===== PILOTES (Classement Pilotes) ===== */
        document.querySelectorAll('.drivers-standings-name').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
            }

            const full = el.dataset.fullname;

            if (w <= 500) {
                el.textContent = full.substring(0, 18);
            }
            else if (w <= 900) {
                el.textContent = full.substring(0, 22);
            }
            else {
                el.textContent = full.substring(0, 30);
            }
        });

    }

    window.addEventListener('resize', updateResponsiveNames);
    updateResponsiveNames();

});
</script>
