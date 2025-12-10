<?php
// ============================================
// driver_stats_all_seasons.php
// ============================================
?>
<?php /* FILE: Views/classements/driver_stats_all_seasons.php */ ?>
<?php $title = 'Stats Pilotes - Toutes Saisons'; ?>
<div class="section-dashboard">
    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=classements">Retour</a>
        <h1>Stats Pilotes - Toutes Saisons</h1>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table" id="driver-stats-table">
            <thead>
                <tr>
                    <th>Catégorie</th>
                    <th>Pilote</th>
                    <th><a href="#" onclick="sortTable(2)">Total GP</a></th>
                    <th><a href="#" onclick="sortTable(3)">Total Points</a></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row->category) ?></td>
                    <td><?= htmlspecialchars($row->nickname) ?></td>
                    <td><?= htmlspecialchars($row->total_gp) ?></td>
                    <td>
                        <?= htmlspecialchars(
                            rtrim(
                                rtrim(number_format($row->total_points, 1, '.', ''), '0'),
                                '.'
                            )
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Simple tri de tableau en JS pour Total GP et Total Points
function sortTable(colIndex) {
    const table = document.getElementById("driver-stats-table");
    const tbody = table.tBodies[0];
    const rows = Array.from(tbody.querySelectorAll("tr"));
    const sorted = rows.sort((a, b) => {
        const aVal = parseFloat(a.cells[colIndex].textContent) || 0;
        const bVal = parseFloat(b.cells[colIndex].textContent) || 0;
        return bVal - aVal; // tri DESC
    });
    tbody.innerHTML = "";
    tbody.append(...sorted);
}
</script>