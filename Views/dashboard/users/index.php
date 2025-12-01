<?php $title = 'Team-eRacing - Users';?>

<div class="section-dashboard">

    <div class="section-btn-dashboard">
        <a class="nav-btn-dashboard" href="index.php?controller=dashboard">Retour au Dashboard</a>
    </div>

    <h1>Gestion des utilisateurs</h1>

    <table class="dashboard-table">
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($list as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user->id) ?></td>
            <td><?= htmlspecialchars($user->email) ?></td>
            <td><?= htmlspecialchars($user->role) ?></td>
            <td>
                <a href="index.php?controller=users&action=update&id=<?= $user->id ?>">
                    <i class="fa-solid fa-pen" alt="Modifier"></i>
                </a>
                <a href="index.php?controller=users&action=delete&id=<?= $user->id ?>">
                    <i class="fa-solid fa-trash" alt="Supprimer"></i>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br><br>

    <h2>Créer un nouvel utilisateur</h2>

    <?php
    // Préparer les options du select
    $rolesOptions = [];
    foreach ($roles as $r) {
        $rolesOptions[$r->id] = $r->name;
    }

    echo $form->getFormElements();
    ?>

</div>