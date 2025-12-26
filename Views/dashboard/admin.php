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
        <h1>Dashboard Administrateur</h1>
        <p class="page-header-dashboard-subtitle-right">
            <span>Accès total à la base de données</span>
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


    <div>
        <h2 class="category-title has-content category-title-dashboard">
            <span>Paramètres généraux</span>
        </h2>
    </div>

    <div class="section-btn-dashboard">
        <a class="home-nav-btn-dashboard nav-btn-dashboard btn-width-large" href="index.php?controller=users&action=index">
            <h3 class="no-margin">Utilisateurs</h3>
            <span class="btn-subtitle">Gérer les membres du site et leurs rôles</span>
        </a>

        <a class="home-nav-btn-dashboard nav-btn-dashboard btn-width-large" href="index.php?controller=categories&action=index">
            <h3 class="no-margin">Catégories</h3>
            <span class="btn-subtitle">Mettre en place leurs noms et leurs couleurs</span>
        </a>

        <a class="home-nav-btn-dashboard nav-btn-dashboard btn-width-large" href="index.php?controller=seasons&action=index">
            <h3 class="no-margin">Saisons</h3>
            <span class="btn-subtitle">Générer de nouvelles saisons pour les catégories mises en place</span>
        </a>

        <a class="home-nav-btn-dashboard nav-btn-dashboard btn-width-large" href="index.php?controller=countries&action=index">
            <h3 class="no-margin">Pays</h3>
            <span class="btn-subtitle">Associer ces pays ensuite aux pilotes, teams et circuits</span>
        </a>

        <a class="home-nav-btn-dashboard nav-btn-dashboard btn-width-large" href="index.php?controller=circuits&action=index">
            <h3 class="no-margin">Circuits</h3>
            <span class="btn-subtitle">Etablir leurs noms et pays</span>
        </a>

        <a class="home-nav-btn-dashboard nav-btn-dashboard btn-width-large" href="index.php?controller=teams&action=index">
            <h3 class="no-margin">Teams</h3>
            <span class="btn-subtitle">Attribuer leurs noms, logos, couleurs et nationalités</span>
        </a>

        <a class="home-nav-btn-dashboard nav-btn-dashboard btn-width-large" href="index.php?controller=drivers&action=index">
            <h3 class="no-margin">Pilotes</h3>
            <span class="btn-subtitle">Créer leurs pseudos et nationalités</span>
        </a>
        
        <a class="home-nav-btn-dashboard nav-btn-dashboard btn-width-large" href="index.php?controller=manualadjustments&action=index">
            <h3 class="no-margin">Ajustements manuels</h3>
            <span class="btn-subtitle">Mettre à jour les classements pilotes et équipes sans publier les détails</span>
        </a>
    </div>

</div>