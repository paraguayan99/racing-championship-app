<?php var_dump($_SESSION) ?>

<?php $title="Team-eRacing - Dashboard"; ?>

<div class="section-dashboard">
    <h1>Dashboard Administrateur</h1>
    <p>Accès total à la base de données</p>

    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=users&action=index">Utilisateurs</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=categories&action=index">Catégories</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=seasons&action=index">Saisons</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=countries&action=index">Pays</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=circuits&action=index">Circuits</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=teams&action=index">Teams</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=drivers&action=index">Pilotes</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=manualadjustments&action=index">Ajustements manuels</a></div>
    <br>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=teamsdrivers&action=index">Teams / Pilotes</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=gp&action=index">Grands Prix</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=gppoints&action=index">GP - Résultats</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=gpstats&action=index">GP - Pole & Hotlap</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=penalties&action=index">Pénalités</a></div>
    <br>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard red" href="index.php?controller=auth&action=logout">Déconnexion</a></div>
</div>