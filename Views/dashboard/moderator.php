<?php var_dump($_SESSION) ?>

<?php $title="Team-eRacing - Dashboard"; ?>

<div class="section-dashboard">
    <h1>Dashboard Modérateur</h1>
    <p>Accès uniquement aux tables de gestion course</p>

    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=gp&action=index">Grand Prix</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=gppoints&action=index">Résultats Points GP</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=penalties&action=index">Pénalités</a></div>
    <br>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=actualteams&action=index">Teams Actuels</a></div>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard" href="index.php?controller=teamsdrivers&action=index">Teams / Pilotes</a></div>
    <br>
    <div class="section-btn-dashboard"><a class="nav-btn-dashboard red" href="index.php?controller=auth&action=logout">Déconnexion</a></div>
</div>