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
        <h1>Dashboard Utilisateur</h1>
    </div>

    <?php var_dump($_SESSION) ?>
</div>