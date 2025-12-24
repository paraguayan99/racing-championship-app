<?php $title = "Palmarès"; ?>

<div class="section-dashboard">
    <a class="nav-btn" href="index.php?controller=classements&action=standings">Retour aux Classements</a>
    <a class="nav-btn red" href="index.php?controller=statscircuits">Circuits</a>

    <div class="page-header">
        <h1>Palmarès</h1>
    </div>

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

    <h2 class="category-title has-content">
        <span>
            <?= htmlspecialchars($category) ?>
        </span>
    </h2>

    <!-- DRIVERS -->
    <h3 class="gp-title">Palmarès Pilotes</h3>
    
    <p class="gp-subtitle">
        <span class="label-long">Champ = Champions / ViceC = Vice-Champions / Trois = Troisièmes / Vict = Victoires / Podiu = Podiums</span>
        <span class="label-medium">Cha = Champions / Vice = Vice-Champions / Troi = Troisièmes / Vict = Victoires / Podi = Podiums</span>
        <span class="label-short">C = Champions / 2 = Vice-Champions / 3 = Troisièmes / Vi = Victoires / Po = Podiums</span>
    </p>

    <div class="table-responsive">
    <table class="dashboard-table sortable palmares-table palmares-drivers-table">
        <thead>
            <tr>
                <th class="badge-width no-sort th-responsive">
                        <span class="label-aria">Position</span>
                        <span aria-hidden="true" class="label-long"></span>
                        <span aria-hidden="true" class="label-medium"></span>
                        <span aria-hidden="true" class="label-short"></span>
                </th>
                <th>Pilote</th>
                <th class="text-center th-responsive">
                        <span class="label-aria">Champions</span>
                        <span aria-hidden="true" class="label-long">Champ</span>
                        <span aria-hidden="true" class="label-medium">Cha</span>
                        <span aria-hidden="true" class="label-short">C</span>
                </th>
                <th class="text-center th-responsive">
                        <span class="label-aria">Vice-Champions</span>
                        <span aria-hidden="true" class="label-long">ViceC</span>
                        <span aria-hidden="true" class="label-medium">Vice</span>
                        <span aria-hidden="true" class="label-short">2</span>
                </th>
                <th class="text-center th-responsive">
                        <span class="label-aria">Troisièmes</span>
                        <span aria-hidden="true" class="label-long">Trois</span>
                        <span aria-hidden="true" class="label-medium">Troi</span>
                        <span aria-hidden="true" class="label-short">3</span>
                </th>
                <th class="text-center th-responsive">
                        <span class="label-aria">Victoires</span>
                        <span aria-hidden="true" class="label-long">Vict</span>
                        <span aria-hidden="true" class="label-medium">Vict</span>
                        <span aria-hidden="true" class="label-short">Vi</span>
                </th>
                <th class="text-center th-responsive">
                        <span class="label-aria">Podiums</span>
                        <span aria-hidden="true" class="label-long">Podiu</span>
                        <span aria-hidden="true" class="label-medium">Podi</span>
                        <span aria-hidden="true" class="label-short">Po</span>
                </th>
                <th class="text-center">GP</th>
                <th class="text-center th-responsive">
                        <span class="label-aria">Points</span>
                        <span aria-hidden="true" class="label-long">Pts</span>
                        <span aria-hidden="true" class="label-medium">Pts</span>
                        <span aria-hidden="true" class="label-short">Pts</span>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($drivers as $i => $d): ?>
            <tr>
                <td class="badge-width"><?= podiumBadge($i + 1) ?></td>
                <td class="driver-name"><?= htmlspecialchars($d->nickname) ?></td>
                <td class="text-center"><?= $d->titles ?></td>
                <td class="text-center"><?= $d->vice_titles ?></td>
                <td class="text-center"><?= $d->third_places ?></td>
                <td class="text-center"><?= $d->wins ?></td>
                <td class="text-center"><?= $d->podiums ?></td>
                <td class="text-center"><?= htmlspecialchars($d->total_gp ?? 0) ?></td>
                <td class="text-center"><?= htmlspecialchars(rtrim(rtrim(number_format($d->total_points ?? 0, 1, '.', ''),'0'),'.')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>

    <!-- TEAMS -->
    <?php if (!empty($teamsByCategory[$category])): ?>
    <h3>Palmarès Équipes</h3>
    <div class="table-responsive">
    <table class="dashboard-table sortable palmares-table palmares-teams-table">
        <thead>
            <tr>
                <th class="badge-width no-sort th-responsive">
                        <span class="label-aria">Position</span>
                        <span aria-hidden="true" class="label-long"></span>
                        <span aria-hidden="true" class="label-medium"></span>
                        <span aria-hidden="true" class="label-short"></span>
                </th>
                <th>Équipe</th>
                <th class="text-center th-responsive">
                        <span class="label-aria">Champions</span>
                        <span aria-hidden="true" class="label-long">Champions</span>
                        <span aria-hidden="true" class="label-medium">Champions</span>
                        <span aria-hidden="true" class="label-short">Champi</span>
                </th>
                <th class="text-center th-responsive">
                        <span class="label-aria">Points</span>
                        <span aria-hidden="true" class="label-long">Points</span>
                        <span aria-hidden="true" class="label-medium">Points</span>
                        <span aria-hidden="true" class="label-short">Points</span>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($teamsByCategory[$category] as $i => $t): ?>
            <tr>
                <td class="badge-width"><?= podiumBadge($i + 1) ?></td>
                <td class="team-name"><?= htmlspecialchars($t->team_name) ?></td>
                <td class="text-center"><?= $t->titles ?></td>
                <td class="text-center"><?= htmlspecialchars(rtrim(rtrim(number_format($t->total_points ?? 0, 1, '.', ''),'0'),'.')) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
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

                // retirer highlight et flèches des autres colonnes
                table.querySelectorAll('th').forEach((th, idx) => {
                    if (th !== header) {
                        th.classList.remove('asc', 'desc');
                        tbody.querySelectorAll('tr').forEach(row => row.children[idx].classList.remove('highlight-column'));
                    }
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

    // === PALMARÈS PILOTES : Titres DESC ===
    document.querySelectorAll('table.palmares-drivers-table').forEach(table => {
        forceSortDesc(table, 2); // colonne Titres
    });

    // === PALMARÈS ÉQUIPES : Titres DESC ===
    document.querySelectorAll('table.palmares-teams-table').forEach(table => {
        forceSortDesc(table, 2); // colonne Titres
    });

    function parseValue(value) {
        if (value === '') return Number.NEGATIVE_INFINITY;
        const num = value.replace(',', '.');
        if (!isNaN(num)) return parseFloat(num);
        return value.toLowerCase();
    }

    function updateResponsiveNames() {
        const w = window.innerWidth;

        /* ===== PILOTES (Palmarès Pilotes) ===== */
        document.querySelectorAll('.driver-name').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
            }

            const full = el.dataset.fullname;

            if (w <= 500) {
                el.textContent = full.substring(0, 10);
            }
            else if (w <= 700) {
                el.textContent = full.substring(0, 16);
            }
            else if (w <= 900) {
                el.textContent = full.substring(0, 20);
            }
            else {
                el.textContent = full.substring(0, 22);
            }
        });

        /* ===== EQUIPES (Palmarès Equipes) ===== */
        document.querySelectorAll('.team-name').forEach(el => {
            if (!el.dataset.fullname) {
                el.dataset.fullname = el.textContent.replace(/\s+/g, ' ').trim();
            }

            const full = el.dataset.fullname;

            if (w <= 500) {
                el.textContent = full.substring(0, 18);
            }
            else if (w <= 700) {
                el.textContent = full.substring(0, 28);
            }
            else if (w <= 900) {
                el.textContent = full.substring(0, 40);
            }
            else {
                el.textContent = full.substring(0, 50);
            }
        });
    }

    window.addEventListener('resize', updateResponsiveNames);
    updateResponsiveNames();

});
</script>



