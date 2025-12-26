<?php 
// Récupération des infos pour l'afficher
$userId = $_SESSION['user_id'] ?? null;
$email = 'Email inconnu';
$role = 'Rôle inconnu';

if ($userId) {
    $currentUser = \App\Models\UsersModel::findById($userId);
    if ($currentUser) {
        $email = $currentUser['email'];
        $role = $currentUser['role'];
    }
}
?>

<?php $title="Team-eRacing - Dashboard"; ?>

<div class="section-dashboard">
    <div class="btn-header-dashboard">
        <a class="home-nav-btn-dashboard nav-btn-dashboard red" href="index.php?controller=auth&action=logout">Déconnexion</a>
        <span>Connecté : <?= htmlspecialchars($email) ?> - <?= htmlspecialchars($role) ?></span>
    </div>

    <div class="page-header page-header-dashboard">
        <h1>Dashboard Modérateur</h1>
        <p class="page-header-dashboard-subtitle-right">
            <span>Accès à la gestion des saisons actuelles</span>
        </p>
    </div>

    <div>
        <h2 class="category-title has-content category-title-dashboard">
            <span>Saisons en cours</span>
        </h2>
    </div>

        <div class="section-btn-dashboard">
        <a class="home-nav-btn-dashboard nav-btn-dashboard btn-width-large" href="index.php?controller=gp&action=index">
            <h3 class="no-margin">Calendriers</h3>
            <span class="btn-subtitle">Constituer les ordres d'apparition des Grands Prix durant la Saison</span>
        </a>

        <a class="home-nav-btn-dashboard nav-btn-dashboard btn-width-large" href="index.php?controller=teamsdrivers&action=index">
            <h3 class="no-margin">Associations Pilotes - Teams</h3>
            <span class="btn-subtitle">Insérer teams au classement pilotes sans impacter le constructeur</span>
        </a>

        <a class="home-nav-btn-dashboard nav-btn-dashboard btn-width-large" href="index.php?controller=gppoints&action=index">
            <h3 class="no-margin">GP - Résultats</h3>
            <span class="btn-subtitle">Compléter les résultats des Grands Prix</span>
        </a>

        <a class="home-nav-btn-dashboard nav-btn-dashboard btn-width-large" href="index.php?controller=gpstats&action=index">
            <h3 class="no-margin">GP - Pole Position & Fastest Lap</h3>
            <span class="btn-subtitle">Joindre les noms des pilotes et leurs chronos</span>
        </a>

        <a class="home-nav-btn-dashboard nav-btn-dashboard btn-width-large" href="index.php?controller=penalties&action=index">
            <h3 class="no-margin">Pénalités</h3>
            <span class="btn-subtitle">Appliquer au pilote et/ou l’équipe engagée sur un GP</span>
        </a>
    </div>
</div>