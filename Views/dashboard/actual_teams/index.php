<?php $title = 'Team-eRacing - Teams Actuels' ?>

<div class="section-dashboard">
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour au Dashboard</a></div>
    <h1>Teams Actuels</h1>

    <table>
    <tr><th>ID</th><th>Team</th><th>Pilote1</th><th>Pilote2</th><th>Actions</th></tr>

    <?php foreach($list as $t): ?>
    <tr>
    <td><?= $t->id ?></td>
    <td><?= $t->team_id ?></td>
    <td><?= $t->driver1_id ?></td>
    <td><?= $t->driver2_id ?></td>

    <td>
    <a href="index.php?controller=actualteams&action=edit&id=<?= $t->id ?>">Modifier</a> |
    <a href="index.php?controller=actualteams&action=delete&id=<?= $t->id ?>" onclick="return confirm('Supprimer ?')">Suppr</a>
    </td>
    </tr>
    <?php endforeach; ?>
    </table>

    <h1>Ajouter un Team </h1>
    <a href="index.php?controller=actualteams&action=create">Ajouter</a>
    <br>
    La VIEWS du Formulaire à créer dans les Controllers avec le Core/Form.php
</div>
