# Racing Championship App
_Web MVC PHP platform — Gestion d’un championnat de courses en ligne_
### 🌐 https://team-eracing.fr/
---

## 🎯 Objectif du projet

Créer une application web permettant de **centraliser, automatiser et rendre consultables** :

- les classements pilotes et équipes
- les palmarès
- l’historique des saisons
- les statistiques principales

Le projet remplace les anciens classements issus d’**images Excel statiques** par une **base de données dynamique**, tout en offrant une vitrine accessible et optimisée SEO.

---

## 👤 Contexte & communauté

Passionné d’informatique depuis l’enfance, j’ai évolué comme boulanger puis commercial avant de me reconvertir vers le développement web.
### 🌐 https://achardcedric.fr/

Depuis 2008, la communauté organise un championnat en ligne (PlayStation), aujourd’hui à sa **26ᵉ saison**, répartie en catégories **F1 / F2**.

- 🏎️ Courses : 2 fois par semaine, 21h–23h
- 🎧 Organisation : bénévoles, modérateurs, pilotes équipés (volant, casque, playseat)

Depuis 2016, je participe activement à l’organisation. Jusqu’ici, les classements étaient gérés sous Excel, ce qui limitait :

- l’automatisation
- la lisibilité
- la fiabilité des calculs

Cette application web devient l’**outil central** pour gérer le championnat et valoriser la communauté. 

Elle a été réalisée en novembre et décembre 2025 dans le cadre de mon projet
de fin de formation DWWM.

Les documents présentés lors de l'examen de janvier 2026 sont disponibles
dans le dossier `docs/` de ce dépôt :
https://github.com/paraguayan99/racing-championship-app/tree/main/docs/

---

## 📌 Fonctionnalités principales

### 🔎 Côté public

- Page d’accueil (présentation + liens YouTube, Twitch, Discord)
- Classements / Palmarès / Circuits
  - filtres par saison et catégorie
  - tri dynamique
  - statistiques globales
- Lien « Nous rejoindre » (Discord)
- Mentions légales & Politique de confidentialité (RGPD)

### 🔐 Dashboard (authentification requise)

- Gestion : catégories, saisons, pilotes, équipes, Grand Prix, résultats
- CRUD complet (lecture, ajout, modification, suppression)
- Formulaire de saisie des résultats

**Gestion des rôles :**

- Administrateur → accès total à la BDD
- Modérateur → gestion des saisons actives
- Utilisateur → aucun accès

---

## 🏗️ Architecture & stack technique

- **Front-end** : HTML, CSS, JavaScript  
- **Back-end** : PHP (architecture MVC)  
- **Base de données** : MySQL  
- **Hébergement** : Local (WAMP) → OVH (tests) → O2SWITCH (déploiement final)  
- **Versioning** : Git + GitHub  
- **IDE** : Visual Studio Code  

---

## 🔒 Sécurité

- CAPTCHA et limite de tentatives de connexion (attaques par force brute et robots)
- Protection contre injections SQL & XSS  
- Tokens CSRF sur formulaires POST  
- Sessions sécurisées / prévention hijacking  
- HTTPS obligatoire  
- Respect RGPD  

---

## 🌐 Accessibilité & SEO

### SEO

- Balises meta dynamiques  
- Sitemap XML & robots.txt  
- Mobile-first

### Accessibilité

- Contraste & tailles lisibles  
- Images avec attributs ALT  
- Navigation clavier  
- ARIA pour éléments dynamiques

---

## 🗄️ Base de données — 15 Tables

### Administrateur (accès total à la bdd)

users, roles, countries, circuits, categories,
seasons, teams, drivers, manual_adjustments

### Modérateur (gestion des saisons actives)

teams_drivers, gp, gp_points, gp_stats, penalties

### Non accessible via le Dashboard de l'application web

updates_log → permet d'afficher la date de dernière mise à jour

## 🗄️ Base de données — 2 Vues

drivers_standings, teams_standings

## 🗄️ Base de données — 4 tables de cache

cache_drivers_palmares, cache_drivers_standings, cache_teams_palmares, cache_teams_standings
> Mises à jour à chaque saisie de résultats pour optimiser les performances des vues complexes.

## 🧮 Règles de calcul
``` > Les égalités dans tous les classements font l'objet d'un départage. ```

### Classement pilotes

comptabilise le nombre de Pole, Fastest Lap, Victoires, Podiums, GP et Points selon le calcul suivant :

``` somme(points GP) − pénalités des GP − ajustements manuels de la saison ```

### Classement équipes

comptabilise le nombre de Points selon le calcul suivant :

``` somme(points des pilotes ayant roulé pour cette équipe à chaque GP) − pénalités des GP − ajustements manuels de la saison ```

### Palmarès pilotes

Sur toutes les saisons et par catégorie : 

comptabilise le nombre de Titres, Vice-champions, Troisièmes, Victoires, Podiums, GP et Points.

### Palmarès équipes

Sur toutes les saisons et par catégorie : 

comptabilise le nombre de Titres et les Points.

### Statistiques Circuits

Sur toutes les saisons et par circuit : 

Top 10 des chronos, Nombre de courses disputées sur ce circuit, et Classement des statistiques par pilote
