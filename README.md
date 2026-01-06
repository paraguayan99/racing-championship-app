# Racing Championship App
# Web MVC PHP platform
Gestion d’un championnat de courses en ligne

🎯 Objectif du projet
Créer une application web permettant de centraliser, automatiser et rendre consultables :
les classements pilotes et équipes
les palmarès
l’historique des saisons
les statistiques principales

Le projet remplace les anciens classements issus d’images Excel statiques par une base de données dynamique, tout en offrant une vitrine accessible et optimisée SEO pour la communauté.

👤 Contexte & communauté
Passionné d’informatique depuis l’enfance, j’ai évolué comme boulanger puis commercial avant de me reconvertir vers le développement web.
Je fais partie d’une communauté de pilotes virtuels qui organise depuis 2008 un championnat en ligne (PlayStation), aujourd’hui à sa 26e saison, répartie en catégories F1 / F2.
👉 Courses : 2 fois par semaine, 21h–23h
👉 Organisation : bénévoles, modérateurs, pilotes équipés (volant, casque, playseat)
Depuis 2016, je participe activement à l’organisation et le suivi des résultats.
Jusqu’ici, les classements étaient gérés manuellement sous Excel, ce qui limitait :
l’automatisation
la lisibilité
la fiabilité des calculs

Cette application devient l’outil central pour gérer les classements du championnat et valoriser la communauté.

📌 Fonctionnalités principales
🔎 Côté public
Page Accueil (présentation + liens YouTube, Twitch, Discord)
Rubrique Classements / Palmarès / Circuits
filtres par saison et catégorie
tri dynamique
statistiques globales
Lien Nous rejoindre (invitation Discord)
Mentions légales & Politique de confidentialité (RGPD)

🔐 Dashboard (authentification requise)

Gestion des catégories, saisons, GP, pilotes, équipes, Grand Prix
CRUD complet (liste, ajout, modification, suppression)
Formulaire de saisie des résultats

Gestion des rôles :
Administrateur (accès total à la BDD)
Modérateur (accès à la gestion des saisons actives)
Utilisateur (aucun accès)

🏗️ Architecture & stack technique
Front-end : HTML, CSS, JavaScript
Back-end : PHP avec architecture MVC
Base de données : MySQL
Hébergement : Local (WAMP) → OVH (tests) → O2SWITCH (déploiement final)
Versioning : Git + GitHub
IDE : Visual Studio Code

🔒 Sécurité
Protection contre injections SQL & XSS
Tokens CSRF sur formulaires POST
Sessions sécurisées & prévention hijacking
HTTPS obligatoire
Respect RGPD

🌐 Accessibilité & SEO
- SEO
Balises meta dynamiques
Sitemap XML & robots.txt
Mobile-first
- Accessibilité
Contraste et tailles lisibles
Images avec attributs ALT
Navigation clavier
ARIA pour éléments dynamiques

🗄️ Base de données — 15 tables
- Administrateur (accès total à la BDD) :
users
roles
countries
circuits
categories
seasons
teams
drivers
manual_adjustments

- Modérateur (accès à la gestion des saisons actives) :
teams-drivers
gp
gp_points
gp_stats
penalties

- Table non accessible via le Dashboard :
updates_log
→ Permet de récupérer date de la dernière mise à jour des classements pour l'afficher sur le site

🗄️ Base de données — 4 vues
drivers_standings
drivers_palmares
teams_standings
teams_palmares

Règles de calcul :
- Classement Pilotes = comptabilise le nombre de Pole, Fastest Lap, Victoires, Podiums, GP et Points selon le calcul suivant :
    somme(points) de chaque GP de la saison
    − pénalités de chaque GP 
    - ajustements manuels de la saison
- Classement Equipes = comptabilise le nombre de Points selon le calcul suivant :
    somme(points) de chaque GP de la saison
    − pénalités de chaque GP
    - ajustements manuels de la saison
- Palmarès Pilotes = Sur toutes les saisons et par catégorie : 
    comptabilise le nombre de Titres, Vice-champions, Troisièmes, Victoires, Podiums, GP et Points.
- Palmarès Equipes = Sur toutes les saisons et par catégorie :
    comptabilise le nombre de Titres et les Points.