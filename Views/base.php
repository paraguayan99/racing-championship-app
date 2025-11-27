<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css" />
<title><?= $title ?></title>
</head>

<body>
<header>
<div class="logo_team_eracing"><a href="index.php"><img src="img/logo_team_eracing.png" alt="Logo Communauté Team-eRacing"></a></div>
<nav>
<a class="nav-btn" href="index.php?controller=Classements">CLASSEMENTS</a>
<a class="nav-btn red" href="index.php?controller=Join">NOUS REJOINDRE</a>
</nav>
</header>

<main>

<!-- Affichage dynamique de la variable $content -->
<?= $content ?>

</main>


<footer>
<div class="footer-links">
<a href="index.php?controller=Mentions">MENTIONS LÉGALES</a>
<a href="index.php?controller=Dashboard">DASHBOARD</a>
</div>
<div class="logo_team_eracing"><a href="index.php"><img src="img/logo_team_eracing.png" alt="Logo Communauté Team-eRacing"></a></div>
</footer>
</body>
</html>