<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- Google Fonts pour les polices -->
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<!-- CSS personnel, pas de Framework -->
<link rel="stylesheet" href="style-v1.2.css" />
<!-- Icones Vectorielles avec FontAwesome -->
<script src="https://kit.fontawesome.com/ff03dfd379.js" crossorigin="anonymous"></script>
<title><?= $title ?></title>
</head>

<body>
<header>
<div class="logo_and_name_header">
    <div class="logo_header">
        <a href="index.php"><img src="img/logo_team_eracing.png" alt="Logo Communauté Team-eRacing"></a>
    </div>
    <h2 class="name_header">Team-eRacing</h2>
</div>
<nav>
<a class="nav-btn" href="index.php?controller=classements">CLASSEMENTS</a>
<a class="nav-btn red" href="index.php?controller=join">NOUS REJOINDRE</a>
</nav>
</header>

<main>

<!-- Affichage dynamique de la variable $content -->
<?= $content ?>

</main>


<footer>
<div>
<a class="nav-btn" href="index.php?controller=mentions">MENTIONS LÉGALES</a>
<a class="nav-btn" href="index.php?controller=dashboard">DASHBOARD</a>
</div>
<div class="logo_footer"><a href="index.php"><img src="img/logo_team_eracing.png" alt="Logo Communauté Team-eRacing"></a></div>
</footer>
</body>
</html>