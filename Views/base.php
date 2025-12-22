<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?= htmlspecialchars($title ?? 'Team-eRacing | Championnat F1 25 en ligne sur PS5') ?></title>
<meta name="description" content="Team-eRacing organise des championnats F1 25 en ligne sur PS5. Communauté F1 francophone, courses diffusées sur Twitch, replays YouTube et inscriptions sur Discord.">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://www.team-eracing.fr/">
<!-- Google Fonts pour les polices -->
<link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<!-- CSS personnel, pas de Framework -->
<link rel="stylesheet" href="stylev1.9.css" />
<link rel="stylesheet" href="style700px-mobilev1.5.css" media="screen and (max-width: 700px)" />
<link rel="stylesheet" href="style900px-tablettev1.6.css" media="screen and (min-width: 701px) and (max-width: 900px)" />
<link rel="stylesheet" href="style1400px-desktopv1.5.css" media="screen and (min-width: 901px)" />
<!-- Icones Vectorielles avec FontAwesome -->
<script src="https://kit.fontawesome.com/ff03dfd379.js" crossorigin="anonymous"></script>
</head>

<body>
<header>
<div class="logo_and_name_header">
    <div class="logo_header">
        <a href="index.php"><img src="img/logo_team_eracing.png" alt="Logo Communauté Team-eRacing"></a>
    </div>
    <span class="name_header">
        Team-eRacing
    </span>
</div>
<nav>
    <a class="nav-btn" href="index.php?controller=classements&action=standings">CLASSEMENTS</a>
    <a class="nav-btn red" href="index.php#discord">NOUS REJOINDRE</a>
</nav>
</header>

<main>

<!-- Affichage dynamique de la variable $content -->
<?= $content ?>

</main>


<footer>
    <nav aria-label="Liens de pied de page">
        <a class="nav-btn" href="index.php?controller=mentions">Mentions légales</a>
        <a class="nav-btn" href="index.php?controller=dashboard">Dashboard</a>
    </nav>

    <div class="logo_footer">
        <p>© 2025 Team-eRacing</p>
        <a href="index.php">
            <img src="img/logo_team_eracing.png" alt="Logo Team-eRacing">
        </a>
    </div>
</footer>

<button id="scrollToTop" aria-label="Retour en haut de page">
    <i class="fas fa-chevron-up"></i>
</button>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const scrollBtn = document.getElementById("scrollToTop");

    window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
            scrollBtn.classList.add("show");
        } else {
            scrollBtn.classList.remove("show");
        }
    });

    scrollBtn.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
});
</script>
</body>
</html>