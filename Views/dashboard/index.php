<!-- $title = 'Team-eRacing - Dashboard' -->

<!-- <div class="section">
<div class="text-block">
<p>SECTION DASHBOARD</p>
</div>
<img src="https://media.gettyimages.com/id/90739292/fr/photo/united-kingdom-this-picture-shows-the-main-information-screen-and-the-computer-set-up-of-the.jpg?s=2048x2048&w=gi&k=20&c=a1Qg4GCcoxYbE_aRnVJqm8YlA-16tKBsZtozSDpLTj8=" alt="Image 1">
</div> -->

<?php

use App\Models\User;

// La session doit déjà être démarrée dans le controller ou middleware
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?controller=Auth&action=login');
    exit();
}

// On peut récupérer le rôle pour afficher certaines sections
$role = $_SESSION['role'] ?? '';

// Début du buffer pour capturer le contenu dynamique
ob_start();

// Définition du titre de la page
$title = "Team-eRacing - Dashboard";
?>
<div class="section-dashboard">

    <h1>Bienvenue sur le Dashboard</h1>
    <p>Connecté en tant que : <strong><?= htmlspecialchars($role) ?></strong></p>
    <a href="index.php?controller=Auth&action=logout">Déconnexion</a>

    <nav class="dashboard-nav">
        <ul>
            <li><a href="index.php?controller=Dashboard&action=index">Accueil</a></li>
            <?php if ($role === 'admin' || $role === 'moderator'): ?>
                <li><a href="index.php?controller=Dashboard&action=manageUsers">Gestion des utilisateurs</a></li>
            <?php endif; ?>
            <?php if ($role === 'admin'): ?>
                <li><a href="index.php?controller=Dashboard&action=settings">Paramètres</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <section class="dashboard-content">
        <h2>Tableau de bord</h2>
        <p>Ici vous pouvez gérer les éléments de votre site.</p>

        <section>
            <h3>Statistiques rapides</h3>
            <ul>
                <li>Nombre d'utilisateurs : <?= User::count() ?></li>
                <li>Autres stats…</li>
            </ul>
        </section>

        <section>
            <h3>Liste des utilisateurs</h3>
            <?php
            $users = User::all();
            if ($users):
            ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Rôle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u->id) ?></td>
                            <td><?= htmlspecialchars($u->email) ?></td>
                            <td><?= htmlspecialchars($u->role) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>Aucun utilisateur trouvé.</p>
            <?php endif; ?>
        </section>

        <section>
            <h3>Actions rapides</h3>
            <ul>
                <li><a href="index.php?controller=Dashboard&action=manageUsers">Gérer les utilisateurs</a></li>
                <li><a href="index.php?controller=Dashboard&action=managePosts">Gérer les articles</a></li>
            </ul>
        </section>
    </section>

</div>

<?php
// // Fin du buffer, contenu stocké dans $content
// $content = ob_get_clean();

// // Inclusion du template de base avec chemin correct
// require_once __DIR__ . '/../base.php';
