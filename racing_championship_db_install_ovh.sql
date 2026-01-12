-- RACING CHAMPIONSHIP APP
-- SCRIPT SQL pour installer la BDD
--
-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Généré le : lun. 12 jan. 2026 à 13:18
-- Version du serveur : 8.0.44-35
-- Version de PHP : 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#E10600',
  `status` enum('active','desactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `color`, `status`) VALUES
(1, 'F1', '#e10600', 'active'),
(2, 'F2', '#366092', 'active');

-- --------------------------------------------------------

--
-- Structure de la table `circuits`
--

CREATE TABLE `circuits` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `country_id` int NOT NULL,
  `status` enum('active','desactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `circuits`
--

INSERT INTO `circuits` (`id`, `name`, `country_id`, `status`) VALUES
(1, 'Sakhir', 2, 'active'),
(2, 'Sepang', 3, 'active'),
(3, 'Melbourne', 4, 'active'),
(4, 'Imola', 5, 'active'),
(5, 'Nurburgring', 6, 'active'),
(6, 'Barcelone', 7, 'active'),
(7, 'Monte-Carlo', 8, 'active'),
(8, 'Silverstone', 9, 'active'),
(9, 'Montreal', 10, 'active'),
(10, 'Indianapolis', 11, 'active'),
(11, 'Nevers Magny-Cours', 1, 'active'),
(12, 'Hockenheim', 6, 'active'),
(13, 'Hungaroring', 12, 'active'),
(14, 'Istanbul', 13, 'active'),
(15, 'Monza', 5, 'active'),
(16, 'Shanghai', 14, 'active'),
(17, 'Suzuka', 15, 'active'),
(18, 'Interlagos', 16, 'active');

-- --------------------------------------------------------

--
-- Structure de la table `countries`
--

CREATE TABLE `countries` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` char(3) DEFAULT NULL,
  `flag` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `flag`) VALUES
(1, 'France', 'FRA', 'img/flags/france.png'),
(2, 'Bahreïn', 'BAH', 'img/flags/bahrein.png'),
(3, 'Malaisie', 'MAL', 'img/flags/malaisie.png'),
(4, 'Australie', 'AUS', 'img/flags/australie.png'),
(5, 'Italie', 'ITA', 'img/flags/italie.png'),
(6, 'Allemagne', 'ALL', 'img/flags/allemagne.png'),
(7, 'Espagne', 'ESP', 'img/flags/espagne.png'),
(8, 'Monaco', 'MON', 'img/flags/monaco.png'),
(9, 'Grande-Bretagne', 'GBR', 'img/flags/grandebretagne.png'),
(10, 'Canada', 'CAN', 'img/flags/canada.png'),
(11, 'États-Unis', 'USA', 'img/flags/etatsunis.png'),
(12, 'Hongrie', 'HON', 'img/flags/hongrie.png'),
(13, 'Turquie', 'TUR', 'img/flags/turquie.png'),
(14, 'Chine', 'CHI', 'img/flags/chine.png'),
(15, 'Japon', 'JAP', 'img/flags/japon.png'),
(16, 'Brésil', 'BRE', 'img/flags/bresil.png'),
(17, 'Suisse', 'SUI', 'img/flags/suisse.png'),
(18, 'Autriche', 'AUT', 'img/flags/autriche.png'),
(19, 'Pays-Bas', 'P-B', 'img/flags/paysbas.png');

-- --------------------------------------------------------

--
-- Structure de la table `drivers`
--

CREATE TABLE `drivers` (
  `id` int NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `country_id` int NOT NULL DEFAULT '1',
  `status` enum('active','desactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `drivers`
--

INSERT INTO `drivers` (`id`, `nickname`, `country_id`, `status`) VALUES
(1, '[Driver removed]', 1, 'active'),
(2, 'Jujubiker', 1, 'active'),
(3, 'Martlio', 1, 'active'),
(4, 'Guignol81', 1, 'active'),
(5, 'Jimboparisgo', 1, 'active'),
(6, 'Chapi-chapo', 1, 'active'),
(7, 'Didi511', 1, 'active'),
(8, 'Senna76', 1, 'active'),
(9, 'Fox', 1, 'active'),
(10, 'Nordschleife', 1, 'active'),
(11, 'Ludovico6', 1, 'active'),
(12, 'Gafit', 1, 'active');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `drivers_palmares`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `drivers_palmares` (
`category` varchar(50)
,`driver_id` int
,`nickname` varchar(100)
,`podiums` decimal(45,0)
,`third_places` decimal(23,0)
,`titles` decimal(23,0)
,`total_gp` bigint
,`total_points` decimal(56,1)
,`vice_titles` decimal(23,0)
,`wins` decimal(45,0)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `drivers_standings`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `drivers_standings` (
`category` varchar(50)
,`driver_id` int
,`nickname` varchar(100)
,`podiums` decimal(23,0)
,`season_id` int
,`season_number` int
,`season_status` enum('active','desactive')
,`team_name` varchar(100)
,`total_points` decimal(34,1)
,`wins` decimal(23,0)
);

-- --------------------------------------------------------

--
-- Structure de la table `gp`
--

CREATE TABLE `gp` (
  `id` int NOT NULL,
  `season_id` int NOT NULL,
  `circuit_id` int NOT NULL,
  `gp_ordre` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `gp`
--

INSERT INTO `gp` (`id`, `season_id`, `circuit_id`, `gp_ordre`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 2),
(3, 1, 3, 3),
(4, 1, 4, 4),
(5, 1, 5, 5),
(6, 1, 6, 6),
(7, 1, 7, 7),
(8, 1, 8, 8),
(9, 1, 9, 9),
(10, 1, 10, 10),
(11, 1, 11, 11),
(12, 1, 12, 12),
(13, 1, 13, 13),
(14, 1, 14, 14),
(15, 1, 15, 15),
(16, 1, 16, 16),
(17, 1, 17, 17),
(18, 1, 18, 18);

-- --------------------------------------------------------

--
-- Structure de la table `gp_points`
--

CREATE TABLE `gp_points` (
  `id` int NOT NULL,
  `gp_id` int NOT NULL,
  `driver_id` int NOT NULL,
  `team_id` int NOT NULL,
  `position` int DEFAULT NULL,
  `points_numeric` decimal(4,1) NOT NULL DEFAULT '0.0',
  `points_text` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `gp_points`
--

INSERT INTO `gp_points` (`id`, `gp_id`, `driver_id`, `team_id`, `position`, `points_numeric`, `points_text`) VALUES
(3, 1, 1, 1, 1, 0.0, NULL),
(4, 1, 1, 1, 2, 0.0, NULL),
(5, 1, 4, 4, 3, 0.0, NULL),
(6, 1, 5, 5, 6, 0.0, NULL),
(7, 1, 7, 7, 4, 0.0, NULL),
(8, 1, 12, 12, 5, 0.0, NULL),
(9, 2, 1, 1, 1, 0.0, NULL),
(10, 2, 4, 4, 3, 0.0, NULL),
(11, 2, 5, 5, 5, 0.0, NULL),
(12, 2, 7, 7, 2, 0.0, NULL),
(13, 3, 2, 2, 2, 0.0, NULL),
(14, 3, 1, 1, 1, 0.0, NULL),
(15, 3, 1, 1, 3, 0.0, NULL),
(16, 3, 4, 4, 5, 0.0, NULL),
(17, 3, 8, 8, 7, 0.0, NULL),
(18, 3, 12, 12, 11, 0.0, NULL),
(19, 4, 1, 1, 1, 0.0, NULL),
(20, 4, 2, 2, 2, 0.0, NULL),
(21, 4, 4, 4, 6, 0.0, NULL),
(22, 4, 8, 8, 3, 0.0, NULL),
(23, 5, 2, 2, 1, 0.0, NULL),
(24, 5, 1, 1, 2, 0.0, NULL),
(25, 5, 1, 1, 3, 0.0, NULL),
(26, 5, 4, 4, 7, 0.0, NULL),
(27, 5, 5, 5, 10, 0.0, NULL),
(28, 5, 7, 7, 5, 0.0, NULL),
(29, 6, 2, 2, 1, 0.0, NULL),
(30, 6, 1, 1, 2, 0.0, NULL),
(31, 6, 5, 5, 3, 0.0, NULL),
(32, 6, 7, 7, 5, 0.0, NULL),
(33, 6, 4, 4, 8, 0.0, NULL),
(34, 6, 12, 12, 11, 0.0, NULL),
(35, 7, 2, 2, 1, 0.0, NULL),
(36, 7, 1, 1, 2, 0.0, NULL),
(37, 7, 1, 1, 3, 0.0, NULL),
(38, 7, 4, 4, 11, 0.0, NULL),
(39, 7, 5, 5, 8, 0.0, NULL),
(40, 7, 12, 12, 10, 0.0, NULL),
(41, 8, 2, 2, 2, 0.0, NULL),
(42, 8, 3, 3, 1, 0.0, NULL),
(43, 8, 6, 6, 3, 0.0, NULL),
(44, 9, 2, 2, 1, 0.0, NULL),
(45, 9, 4, 4, 6, 0.0, NULL),
(46, 9, 5, 5, 3, 0.0, NULL),
(47, 9, 8, 8, 8, 0.0, NULL),
(48, 9, 1, 1, 2, 0.0, NULL),
(49, 10, 2, 2, 1, 0.0, NULL),
(50, 10, 1, 1, 2, 0.0, NULL),
(51, 10, 4, 4, 3, 0.0, NULL),
(52, 10, 5, 5, 8, 0.0, NULL),
(53, 10, 6, 6, 6, 0.0, NULL),
(54, 10, 7, 7, 5, 0.0, NULL),
(55, 10, 12, 12, 11, 0.0, NULL),
(56, 11, 2, 2, 2, 0.0, NULL),
(57, 11, 3, 3, 1, 0.0, NULL),
(58, 11, 4, 4, 3, 0.0, NULL),
(59, 11, 5, 5, 5, 0.0, NULL),
(60, 11, 6, 6, 8, 0.0, NULL),
(61, 11, 7, 7, 9, 0.0, NULL),
(62, 11, 8, 8, 4, 0.0, NULL),
(63, 11, 9, 9, 7, 0.0, NULL),
(64, 11, 11, 11, 6, 0.0, NULL),
(65, 11, 12, 12, 10, 0.0, NULL),
(66, 12, 2, 2, 1, 0.0, NULL),
(67, 12, 3, 3, 2, 0.0, NULL),
(68, 12, 4, 4, 4, 0.0, NULL),
(69, 12, 5, 5, 6, 0.0, NULL),
(70, 12, 11, 11, 8, 0.0, NULL),
(71, 12, 1, 1, 3, 0.0, NULL),
(72, 13, 2, 2, 2, 0.0, NULL),
(73, 13, 3, 3, 1, 0.0, NULL),
(74, 13, 4, 4, 9, 0.0, NULL),
(75, 13, 5, 5, 5, 0.0, NULL),
(76, 13, 6, 6, 6, 0.0, NULL),
(77, 13, 9, 9, 3, 0.0, NULL),
(78, 13, 10, 10, 4, 0.0, NULL),
(79, 13, 11, 11, 8, 0.0, NULL),
(80, 13, 12, 12, 11, 0.0, NULL),
(81, 14, 2, 2, 4, 0.0, NULL),
(82, 14, 3, 3, 1, 0.0, NULL),
(83, 14, 4, 4, 2, 0.0, NULL),
(84, 14, 5, 5, 7, 0.0, NULL),
(85, 14, 6, 6, 5, 0.0, NULL),
(86, 14, 10, 10, 3, 0.0, NULL),
(87, 14, 11, 11, 9, 0.0, NULL),
(88, 14, 12, 12, 11, 0.0, NULL),
(89, 15, 2, 2, 2, 0.0, NULL),
(90, 15, 3, 3, 1, 0.0, NULL),
(91, 15, 4, 4, 10, 0.0, NULL),
(92, 15, 5, 5, 3, 0.0, NULL),
(93, 15, 6, 6, 4, 0.0, NULL),
(94, 15, 7, 7, 8, 0.0, NULL),
(95, 15, 8, 8, 7, 0.0, NULL),
(96, 15, 10, 10, 9, 0.0, NULL),
(97, 15, 12, 12, 11, 0.0, NULL),
(98, 16, 2, 2, 2, 0.0, NULL),
(99, 16, 3, 3, 1, 0.0, NULL),
(100, 16, 4, 4, 5, 0.0, NULL),
(101, 16, 6, 6, 3, 0.0, NULL),
(102, 16, 9, 9, 4, 0.0, NULL),
(103, 17, 2, 2, 1, 0.0, NULL),
(104, 17, 4, 4, 7, 0.0, NULL),
(105, 17, 5, 5, 4, 0.0, NULL),
(106, 17, 6, 6, 5, 0.0, NULL),
(107, 17, 7, 7, 8, 0.0, NULL),
(108, 17, 8, 8, 6, 0.0, NULL),
(109, 17, 10, 10, 9, 0.0, NULL),
(110, 17, 1, 1, 2, 0.0, NULL),
(111, 17, 1, 1, 3, 0.0, NULL),
(112, 18, 2, 2, 1, 0.0, NULL),
(113, 18, 1, 1, 2, 0.0, NULL),
(114, 18, 4, 4, 8, 0.0, NULL),
(115, 18, 5, 5, 3, 0.0, NULL),
(116, 18, 6, 6, 4, 0.0, NULL),
(117, 18, 7, 7, 7, 0.0, NULL),
(118, 18, 8, 8, 5, 0.0, NULL),
(119, 18, 9, 9, 10, 0.0, NULL),
(120, 18, 10, 10, 9, 0.0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `gp_stats`
--

CREATE TABLE `gp_stats` (
  `gp_id` int NOT NULL,
  `pole_position_driver` int DEFAULT NULL,
  `pole_position_time` varchar(50) DEFAULT NULL,
  `fastest_lap_driver` int DEFAULT NULL,
  `fastest_lap_time` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `manual_adjustments`
--

CREATE TABLE `manual_adjustments` (
  `id` int NOT NULL,
  `season_id` int NOT NULL,
  `driver_id` int DEFAULT NULL,
  `team_id` int DEFAULT NULL,
  `points` decimal(4,1) NOT NULL,
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `manual_adjustments`
--

INSERT INTO `manual_adjustments` (`id`, `season_id`, `driver_id`, `team_id`, `points`, `comment`) VALUES
(1, 1, 2, 2, 141.0, NULL),
(2, 1, 3, 3, 68.0, NULL),
(3, 1, 4, 4, 57.0, NULL),
(4, 1, 5, 5, 51.0, NULL),
(5, 1, 6, 6, 38.0, NULL),
(6, 1, 7, 7, 29.0, NULL),
(7, 1, 8, 8, 23.0, NULL),
(8, 1, 9, 9, 13.0, NULL),
(9, 1, 10, 10, 11.0, NULL),
(10, 1, 11, 11, 5.0, NULL),
(11, 1, 12, 12, 4.0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `penalties`
--

CREATE TABLE `penalties` (
  `id` int NOT NULL,
  `gp_id` int NOT NULL,
  `driver_id` int DEFAULT NULL,
  `team_id` int DEFAULT NULL,
  `points_removed` int NOT NULL,
  `comment` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Administrateur'),
(2, 'Moderateur'),
(3, 'Utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `seasons`
--

CREATE TABLE `seasons` (
  `id` int NOT NULL,
  `season_number` int NOT NULL,
  `category_id` int NOT NULL,
  `videogame` varchar(100) NOT NULL,
  `platform` varchar(100) NOT NULL,
  `status` enum('active','desactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `seasons`
--

INSERT INTO `seasons` (`id`, `season_number`, `category_id`, `videogame`, `platform`, `status`) VALUES
(1, 1, 1, 'F1 Championship Edition', 'PS3', 'desactive'),
(2, 1, 2, 'F1 Championship Edition', 'PS3', 'active');

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--

CREATE TABLE `teams` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `country_id` int NOT NULL,
  `status` enum('active','desactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `teams`
--

INSERT INTO `teams` (`id`, `name`, `logo`, `color`, `country_id`, `status`) VALUES
(1, '[Team removed]', '', NULL, 1, 'active'),
(2, 'Toyota', 'img/teams/toyota.png', '#c9c9c9', 15, 'active'),
(3, 'Williams', 'img/teams/williams.png', '#03a8ea', 9, 'active'),
(4, 'Ferrari', 'img/teams/ferrari.png', '#fe0000', 5, 'active'),
(5, 'Renault', 'img/teams/renault.png', '#ffcd00', 1, 'active'),
(6, 'Super Aguri', 'img/teams/super_aguri.png', '#ffffff', 15, 'active'),
(7, 'Honda', 'img/teams/honda.png', '#48bb90', 15, 'active'),
(8, 'BMW Sauber', 'img/teams/bmw.png', '#3290bf', 17, 'active'),
(9, 'McLaren', 'img/teams/mclaren.png', '#ff8500', 9, 'active'),
(10, 'Red Bull', 'img/teams/redbull.png', '#15185e', 18, 'active'),
(11, 'Toro Rosso', 'img/teams/toro_rosso.png', '#11109a', 5, 'active'),
(12, 'Spyker', 'img/teams/spyker.png', '#f79246', 19, 'active');

-- --------------------------------------------------------

--
-- Structure de la table `teams_drivers`
--

CREATE TABLE `teams_drivers` (
  `id` int NOT NULL,
  `season_id` int NOT NULL,
  `driver_id` int NOT NULL,
  `team_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `teams_drivers`
--

INSERT INTO `teams_drivers` (`id`, `season_id`, `driver_id`, `team_id`) VALUES
(1, 1, 2, 2),
(2, 1, 3, 3),
(3, 1, 4, 4),
(4, 1, 5, 5),
(5, 1, 6, 6),
(6, 1, 7, 7),
(7, 1, 8, 8),
(8, 1, 9, 9),
(9, 1, 10, 10),
(10, 1, 11, 11),
(11, 1, 12, 12);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `teams_palmares`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `teams_palmares` (
`category` varchar(50)
,`team_id` int
,`team_name` varchar(100)
,`titles` decimal(23,0)
,`total_points` decimal(56,1)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `teams_standings`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `teams_standings` (
`category` varchar(50)
,`season_id` int
,`season_number` int
,`team_id` int
,`team_name` varchar(100)
,`total_points` decimal(34,1)
);

-- --------------------------------------------------------

--
-- Structure de la table `updates_log`
--

CREATE TABLE `updates_log` (
  `id` int NOT NULL,
  `season_id` int DEFAULT NULL,
  `gp_id` int DEFAULT NULL,
  `table_name` varchar(50) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int DEFAULT NULL,
  `action` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `role_id`) VALUES
(1, 'administrateur@team-eracing.fr', '$2y$10$TcnrGUJJ1RxNH4tyclcLvOKn1hgo.wQuODPkaH/TYTav5IKPAl2bS', 1),
(2, 'moderateur@team-eracing.fr', '$2y$10$D1vHJgJvBxWoZIUyykQN2u9QTP6npKSejgtUfcKdR0jLYGRdfhcE.', 2),
(3, 'utilisateur@team-eracing.fr', '$2y$10$oyIHP3MLEYtR61F2xJlseOpGrkcUiTelkZo4QyQ6VOCU6b8wOyYTK', 3);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `circuits`
--
ALTER TABLE `circuits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_circuit_status` (`status`),
  ADD KEY `idx_circuit_country` (`country_id`);

--
-- Index pour la table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `unique_code` (`code`);

--
-- Index pour la table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nickname` (`nickname`),
  ADD KEY `idx_driver_status` (`status`),
  ADD KEY `idx_driver_country` (`country_id`);

--
-- Index pour la table `gp`
--
ALTER TABLE `gp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_gp_season` (`season_id`),
  ADD KEY `idx_gp_circuit` (`circuit_id`),
  ADD KEY `idx_gp_season_ordre` (`season_id`,`gp_ordre`);

--
-- Index pour la table `gp_points`
--
ALTER TABLE `gp_points`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_gp_position` (`gp_id`,`position`),
  ADD KEY `idx_points_gp` (`gp_id`),
  ADD KEY `idx_points_driver` (`driver_id`),
  ADD KEY `idx_points_team` (`team_id`),
  ADD KEY `idx_points_gp_driver` (`gp_id`,`driver_id`),
  ADD KEY `idx_points_driver_team` (`driver_id`,`team_id`),
  ADD KEY `idx_fk_gp_points_driver` (`driver_id`);

--
-- Index pour la table `gp_stats`
--
ALTER TABLE `gp_stats`
  ADD PRIMARY KEY (`gp_id`),
  ADD KEY `idx_stats_pole_driver` (`pole_position_driver`),
  ADD KEY `idx_stats_fastest_driver` (`fastest_lap_driver`);

--
-- Index pour la table `manual_adjustments`
--
ALTER TABLE `manual_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ma_season` (`season_id`),
  ADD KEY `idx_ma_driver` (`driver_id`),
  ADD KEY `idx_ma_team` (`team_id`),
  ADD KEY `idx_ma_driver_team` (`driver_id`,`team_id`);

--
-- Index pour la table `penalties`
--
ALTER TABLE `penalties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gp_id` (`gp_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `seasons`
--
ALTER TABLE `seasons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_season_category` (`season_number`,`category_id`),
  ADD KEY `idx_fk_category` (`category_id`),
  ADD KEY `idx_season_status` (`status`);

--
-- Index pour la table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_team_status` (`status`),
  ADD KEY `idx_team_country` (`country_id`);

--
-- Index pour la table `teams_drivers`
--
ALTER TABLE `teams_drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_td_unique` (`season_id`,`driver_id`,`team_id`),
  ADD UNIQUE KEY `uniq_driver_per_season` (`season_id`,`driver_id`),
  ADD KEY `idx_td_season` (`season_id`),
  ADD KEY `idx_td_driver` (`driver_id`),
  ADD KEY `idx_td_team` (`team_id`);

--
-- Index pour la table `updates_log`
--
ALTER TABLE `updates_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_updates_season` (`season_id`),
  ADD KEY `fk_updates_gp` (`gp_id`),
  ADD KEY `fk_updates_user` (`updated_by`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `circuits`
--
ALTER TABLE `circuits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gp`
--
ALTER TABLE `gp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gp_points`
--
ALTER TABLE `gp_points`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `manual_adjustments`
--
ALTER TABLE `manual_adjustments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `penalties`
--
ALTER TABLE `penalties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `seasons`
--
ALTER TABLE `seasons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `teams_drivers`
--
ALTER TABLE `teams_drivers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `updates_log`
--
ALTER TABLE `updates_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Structure de la vue `drivers_palmares`
--
DROP TABLE IF EXISTS `drivers_palmares`;

CREATE ALGORITHM=UNDEFINED DEFINER=`cefiidev1493`@`%` SQL SECURITY DEFINER VIEW `drivers_palmares`  AS SELECT `ds`.`category` AS `category`, `d`.`id` AS `driver_id`, `d`.`nickname` AS `nickname`, sum((case when ((`ds`.`season_status` = 'desactive') and (`ds`.`total_points` = (select max(`ds2`.`total_points`) from `drivers_standings` `ds2` where ((`ds2`.`season_id` = `ds`.`season_id`) and (`ds2`.`category` = `ds`.`category`))))) then 1 else 0 end)) AS `titles`, sum((case when ((`ds`.`season_status` = 'desactive') and (`ds`.`total_points` = (select max(`ds2`.`total_points`) from `drivers_standings` `ds2` where ((`ds2`.`season_id` = `ds`.`season_id`) and (`ds2`.`category` = `ds`.`category`) and (`ds2`.`total_points` < (select max(`ds3`.`total_points`) from `drivers_standings` `ds3` where ((`ds3`.`season_id` = `ds`.`season_id`) and (`ds3`.`category` = `ds`.`category`)))))))) then 1 else 0 end)) AS `vice_titles`, sum((case when ((`ds`.`season_status` = 'desactive') and (`ds`.`total_points` = (select distinct `ds2`.`total_points` from `drivers_standings` `ds2` where ((`ds2`.`season_id` = `ds`.`season_id`) and (`ds2`.`category` = `ds`.`category`)) order by `ds2`.`total_points` desc limit 2,1))) then 1 else 0 end)) AS `third_places`, sum(`ds`.`total_points`) AS `total_points`, sum(`ds`.`wins`) AS `wins`, sum(`ds`.`podiums`) AS `podiums`, (select count(distinct `gp_pts`.`gp_id`) from ((`gp_points` `gp_pts` join `gp` `g` on((`g`.`id` = `gp_pts`.`gp_id`))) join `drivers_standings` `dsx` on(((`dsx`.`driver_id` = `gp_pts`.`driver_id`) and (`dsx`.`season_id` = `g`.`season_id`) and (`dsx`.`category` = `ds`.`category`)))) where (`gp_pts`.`driver_id` = `d`.`id`)) AS `total_gp` FROM (`drivers_standings` `ds` join `drivers` `d` on((`d`.`id` = `ds`.`driver_id`))) GROUP BY `ds`.`category`, `d`.`id`, `d`.`nickname` ;

-- --------------------------------------------------------

--
-- Structure de la vue `drivers_standings`
--
DROP TABLE IF EXISTS `drivers_standings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`cefiidev1493`@`%` SQL SECURITY DEFINER VIEW `drivers_standings`  AS SELECT `s`.`id` AS `season_id`, `s`.`season_number` AS `season_number`, `s`.`status` AS `season_status`, `c`.`name` AS `category`, `d`.`id` AS `driver_id`, `d`.`nickname` AS `nickname`, `t`.`name` AS `team_name`, ((coalesce(sum(`gp_pts`.`points_numeric`),0) + coalesce(`ma`.`total_points`,0)) - coalesce(sum(`p`.`points_removed`),0)) AS `total_points`, sum((case when (`gp_pts`.`position` = 1) then 1 else 0 end)) AS `wins`, sum((case when (`gp_pts`.`position` in (1,2,3)) then 1 else 0 end)) AS `podiums` FROM ((((((((`seasons` `s` join `categories` `c` on((`c`.`id` = `s`.`category_id`))) join `gp` `g` on((`g`.`season_id` = `s`.`id`))) join `gp_points` `gp_pts` on((`gp_pts`.`gp_id` = `g`.`id`))) join `drivers` `d` on((`d`.`id` = `gp_pts`.`driver_id`))) left join `teams_drivers` `td` on(((`td`.`driver_id` = `d`.`id`) and (`td`.`season_id` = `s`.`id`)))) left join `teams` `t` on((`t`.`id` = `td`.`team_id`))) left join (select `manual_adjustments`.`season_id` AS `season_id`,`manual_adjustments`.`driver_id` AS `driver_id`,sum(`manual_adjustments`.`points`) AS `total_points` from `manual_adjustments` group by `manual_adjustments`.`season_id`,`manual_adjustments`.`driver_id`) `ma` on(((`ma`.`season_id` = `s`.`id`) and (`ma`.`driver_id` = `d`.`id`)))) left join `penalties` `p` on(((`p`.`driver_id` = `d`.`id`) and (`p`.`gp_id` = `g`.`id`)))) GROUP BY `s`.`id`, `s`.`season_number`, `s`.`status`, `c`.`name`, `d`.`id`, `d`.`nickname`, `t`.`name` ;

-- --------------------------------------------------------

--
-- Structure de la vue `teams_palmares`
--
DROP TABLE IF EXISTS `teams_palmares`;

CREATE ALGORITHM=UNDEFINED DEFINER=`cefiidev1493`@`%` SQL SECURITY DEFINER VIEW `teams_palmares`  AS SELECT `ts`.`category` AS `category`, `t`.`id` AS `team_id`, `t`.`name` AS `team_name`, sum((case when ((`s`.`status` = 'desactive') and (`ts`.`total_points` = (select max(`ts2`.`total_points`) from `teams_standings` `ts2` where (`ts2`.`season_id` = `ts`.`season_id`)))) then 1 else 0 end)) AS `titles`, sum(`ts`.`total_points`) AS `total_points` FROM ((`teams_standings` `ts` join `teams` `t` on((`t`.`id` = `ts`.`team_id`))) join `seasons` `s` on((`s`.`id` = `ts`.`season_id`))) GROUP BY `ts`.`category`, `t`.`id`, `t`.`name` ;

-- --------------------------------------------------------

--
-- Structure de la vue `teams_standings`
--
DROP TABLE IF EXISTS `teams_standings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`cefiidev1493`@`%` SQL SECURITY DEFINER VIEW `teams_standings`  AS SELECT `s`.`id` AS `season_id`, `s`.`season_number` AS `season_number`, `c`.`name` AS `category`, `t`.`id` AS `team_id`, `t`.`name` AS `team_name`, ((coalesce(sum(`gp_pts`.`points_numeric`),0) + coalesce(`ma`.`total_points`,0)) - coalesce(sum(`p`.`points_removed`),0)) AS `total_points` FROM ((((((`seasons` `s` join `categories` `c` on((`c`.`id` = `s`.`category_id`))) join `teams` `t`) left join `gp` `g` on((`g`.`season_id` = `s`.`id`))) join `gp_points` `gp_pts` on(((`gp_pts`.`gp_id` = `g`.`id`) and (`gp_pts`.`team_id` = `t`.`id`)))) left join (select `manual_adjustments`.`season_id` AS `season_id`,`manual_adjustments`.`team_id` AS `team_id`,sum(`manual_adjustments`.`points`) AS `total_points` from `manual_adjustments` group by `manual_adjustments`.`season_id`,`manual_adjustments`.`team_id`) `ma` on(((`ma`.`season_id` = `s`.`id`) and (`ma`.`team_id` = `t`.`id`)))) left join `penalties` `p` on(((`p`.`gp_id` = `g`.`id`) and (`p`.`team_id` = `t`.`id`)))) GROUP BY `s`.`id`, `s`.`season_number`, `c`.`name`, `t`.`id`, `t`.`name` ;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `circuits`
--
ALTER TABLE `circuits`
  ADD CONSTRAINT `fk_circuits_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `fk_drivers_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `gp`
--
ALTER TABLE `gp`
  ADD CONSTRAINT `fk_gp_circuit` FOREIGN KEY (`circuit_id`) REFERENCES `circuits` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_gp_season` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `gp_points`
--
ALTER TABLE `gp_points`
  ADD CONSTRAINT `fk_gp_points_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_gp_points_gp` FOREIGN KEY (`gp_id`) REFERENCES `gp` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_gp_points_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `gp_stats`
--
ALTER TABLE `gp_stats`
  ADD CONSTRAINT `fk_gp_stats_fastest_driver` FOREIGN KEY (`fastest_lap_driver`) REFERENCES `drivers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_gp_stats_gp` FOREIGN KEY (`gp_id`) REFERENCES `gp` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_gp_stats_pole_driver` FOREIGN KEY (`pole_position_driver`) REFERENCES `drivers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `manual_adjustments`
--
ALTER TABLE `manual_adjustments`
  ADD CONSTRAINT `fk_manual_adjustments_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_manual_adjustments_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `penalties`
--
ALTER TABLE `penalties`
  ADD CONSTRAINT `fk_penalties_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penalties_gp` FOREIGN KEY (`gp_id`) REFERENCES `gp` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penalties_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `seasons`
--
ALTER TABLE `seasons`
  ADD CONSTRAINT `fk_seasons_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `fk_teams_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `teams_drivers`
--
ALTER TABLE `teams_drivers`
  ADD CONSTRAINT `fk_teams_drivers_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teams_drivers_season` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teams_drivers_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `updates_log`
--
ALTER TABLE `updates_log`
  ADD CONSTRAINT `fk_updates_log_gp` FOREIGN KEY (`gp_id`) REFERENCES `gp` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_updates_log_season` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_updates_log_user` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
