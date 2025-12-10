<?php
// ============================================
// team_awards.php
// ============================================
?>
<?php /* FILE: Views/classements/team_awards.php */ ?>
<?php $title = 'Titres Constructeurs'; ?>
<div class="section-dashboard">
    <div class="section-header">
        <a class="nav-btn-dashboard" href="index.php?controller=classements">Retour</a>
        <h1>Titres Constructeurs</h1>
    </div>

    <div class="table-responsive">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Équipe</th>
                    <th>Catégorie</th>
                    <th>Titres</th>
                    <th>Vice-champions</th>
                    <th>3e Places</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row->team_name) ?></td>
                    <td><?= htmlspecialchars($row->category) ?></td>
                    <td><?= htmlspecialchars($row->titles) ?></td>
                    <td><?= htmlspecialchars($row->vice_titles) ?></td>
                    <td><?= htmlspecialchars($row->third_place) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>