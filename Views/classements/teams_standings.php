<?php
// ============================================
// teams_standings.php
// ============================================
?>
<?php /* FILE: Views/classements/teams_standings.php */ ?>
<?php $title = 'Classement Constructeurs'; ?>
<div class="section-dashboard">
    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=classements">Retour</a>
        <h1>Classement Constructeurs</h1>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Saison</th>
                    <th>Catégorie</th>
                    <th>Équipe</th>
                    <th>Points</th>
                    <th>Victoires</th>
                    <th>Podiums</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row->season_number) ?></td>
                    <td><?= htmlspecialchars($row->category) ?></td>
                    <td><?= htmlspecialchars($row->team_name) ?></td>
                    <td><?= htmlspecialchars($row->total_points) ?></td>
                    <td><?= htmlspecialchars($row->wins) ?></td>
                    <td><?= htmlspecialchars($row->podiums) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
