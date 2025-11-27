[ Cahier des charges Projet Examen DWWM - CEFii Angers ]  

Projet « Gestion d’un championnat de courses en ligne »  

// Résumé du projet  
Créer un site web pour centraliser, automatiser et rendre consultables les classements, palmarès et l’historique d’un championnat de courses en ligne. Remplacer les classements issus d’images Excel statiques par une base de données dynamique, offrir une vitrine SEO, une page « rejoindre » (pour le Discord), et un espace administration pour reporter les résultats (calculs automatiques classement pilotes / équipes, pénalités…).  

// Objectifs  
• Centraliser les résultats saison par saison dans une BDD.  
• Calculer automatiquement les classements pilotes et écuries, et appliquer des pénalités.  
• Publier un site vitrine optimisé SEO pour attirer de nouveaux pilotes via les moteurs de recherche.  
• Fournir un espace admin/modérateur sécurisé pour ajouter/modifier/supprimer courses et résultats.  
• Produire documentation et livrables pour l’examen (code, README, manuel, démonstration).  

// Contenus Principaux  
- Visible de tous  
• Page d’accueil : présentation du championnat, news, contenus adaptés pour le SEO  
• Page Classements / Palmarès / Historique : par saison, classement pilotes, classement équipes, tri, filtres (catégorie F1/F2), vainqueurs, podiums, stats globales …  
• Page Nous rejoindre : lien/invitation Discord.  
• Footer : mentions légales, RGPD, réseaux sociaux.  

- Administration (authentification nécessaire)  
• Authentification (login) pour Administrateurs / Modérateurs.  
• CRUD : saisons, Grand Prix, résultats (liste des pilotes et leurs positions), équipes, pilotes.  
• Formulaire d’ajout de résultat par Grand Prix : entrée manuelle.  
• Interface d’application de pénalités (retirer X pts, disqualifier).  

// Exigences techniques  
- Languages & logiciels  
• Front : HTML, CSS, JavaScript.  
• Back : PHP, architecture MVC (models, views, controllers).  
• BDD : MySQL.  
• Versioning : Git + GitHub.  
• IDE : VSCode.  
• Hébergement : OVH / phpMyAdmin.  

- Architecture & bonnes pratiques  
• Respect du pattern MVC, séparation logique.  
• Sécurisation de l’application (injections SQL, XSS, faille CSRF token, sessions).  
• Accessibilité du site  
• Responsive-design (développement en mobile-first)  

// Base de données relationnelle  
• Tables : users / seasons / teams / grand_prix / results / penalties  

// Règles de calcul (barème + pénalités)  
• Calcul classement pilotes : somme points results - somme points penalties (par pilote sur une saison).  
• Calcul classement équipes : somme points des pilotes d’une même écurie.  
• Gestion égalités : comparer nombre de victoires, puis 2e places, etc.  

// Import / Migration des données existantes  
• Ajout manuellement des résultats des grand prix saison par saison.  
• Validation post-import : contrôles d’intégrité (erreur calcul, somme points, doublons).  

// Sécurité & conformité  
• HTTPS obligatoire.  
• Protection CSRF token sur tous les formulaires POST.  
• Protection des injections (XSS).  
• Protection de détournement de sessions.  
• RGPD : page mentions légales.  

// SEO & accessibilité  
• SEO :  
o URLs propres (SEO) : /classements/2025, /gp/monaco-2025.  
o Meta titles & descriptions dynamiques.  
o Sitemap XML et robots.txt.  
o Mobile-first responsive design.  

• Accessibilité :  
o Contraste couleurs, tailles de police lisibles.  
o Attributs alt sur images, formulaires accessibles (labels).  
o Navigation clavier, aria-* pour éléments dynamiques.  

// UI / UX — pages & composants (maquettes)  
• Wireframes à produire : page d’accueil, page classements / palmarès / historique, page nous rejoindre, page connexion, page profil admin / modérateur, formulaire ajout résultat grand prix.  
• Responsive : mobile / tablette / desktop.  

// Authentification & rôles  
• Rôles : admin, modérateur, public.  
• Permissions contrôlées côté serveur.  
• Page de login + mot de passe.  

// Tests & validation  
• Tests unitaires PHP (calculs points, règles égalité).  
• Tests d’intégration basiques (CRUD modèles).  
• Tests manuels de parcours (ajout résultat → recalcul classement).  
• Tests responsive design  
• Tests accessibilité  

// Documentation & livrables pour l’examen  
• README (installation, configuration, structure, commandes).  
• Guide déploiement (hébergement choisi).  
• Manuel utilisateur (comment consulter classements, chercher pilotes).  
• Manuel administrateur (connexion, ajout résultats, appliquer pénalités).  
• Jeu de données de tests.  
• Présentation / slides + vidéo de démonstration.  
• GitHub public et historique des commits.  
• Code source complet (MVC PHP)  
• Données de la BDD via exportation (.sql / .csv)  

// Planning - Checklist : tâches avant / pendant / après développement  
- Avant  
• Récupérer toutes les sources de données et les analyser (images classements et fichiers Excel originaux).  
• Préparer wireframes + maquettes.  
• Choisir hébergement & nom de domaine.  
• Créer Git / GitHub.  
• Définir utilisateurs et permissions.  
• Valider spécifications du site et cas d’usage.  

- Pendant  
• Initialiser structure MVC.  
• Réaliser les schémas et mettre en place BDD.  
• Implémenter les tables de la BDD.  
• Pages publiques : accueil, classements, nous rejoindre.  
• Page connexion + Page profil / dashboard admin/modérateur.  
• Formulaire ajout résultat grand prix.  
• Algorithme calcul points + pénalités.  
• Tests unitaires.  
• Mise en place SEO (sitemap, meta).  
• Responsive & accessibilité.  
• Revue sécurité (faille CSRF, injection XSS, session).  

- Après  
• Migration complète des données historiques.  
• Documentation complète (admin/modérateur/développeur).  
• Démo vidéo + slides pour l’examen.  
• Plan de sauvegarde + procédure de restauration.  
• Ouverture GitHub en public et vérifier historique des commits.  