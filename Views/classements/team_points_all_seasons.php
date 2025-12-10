<?php
// ============================================
// team_points_all_seasons.php
// ============================================
?>
<?php /* FILE: Views/classements/team_points_all_seasons.php */ ?>
<?php $title = 'Points Constructeurs - Toutes Saisons'; ?>
<div class="section-dashboard">
    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=classements">Retour</a>
        <h1>Points Constructeurs - Toutes Saisons</h1>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Équipe</th>
                    <th>Total Points</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row->team_name) ?></td>
                    <td><?= htmlspecialchars($row->total_points) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>