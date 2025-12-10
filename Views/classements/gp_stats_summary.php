<?php
// ============================================
// gp_stats_summary.php
// ============================================
?>
<?php /* FILE: Views/classements/gp_stats_summary.php */ ?>
<?php $title = 'Stats GP – Pole & Hotlap'; ?>
<div class="section-dashboard">
    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=classements">Retour</a>
        <h1>Statistiques GP</h1>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Saison</th>
                    <th>Catégorie</th>
                    <th>Circuit</th>
                    <th>Pole</th>
                    <th>Temps Pole</th>
                    <th>Hotlap</th>
                    <th>Temps Hotlap</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row->season_number) ?></td>
                    <td><?= htmlspecialchars($row->category) ?></td>
                    <td><?= htmlspecialchars($row->circuit_name) ?></td>
                    <td><?= htmlspecialchars($row->pole_driver_name ?? '') ?></td>
                    <td><?= htmlspecialchars($row->pole_position_time ?? '') ?></td>
                    <td><?= htmlspecialchars($row->fastest_driver_name ?? '') ?></td>
                    <td><?= htmlspecialchars($row->fastest_lap_time ?? '') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>