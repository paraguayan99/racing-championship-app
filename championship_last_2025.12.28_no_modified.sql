-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 28 déc. 2025 à 09:05
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `championship_last`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#E10600',
  `status` enum('active','desactive') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `color`, `status`) VALUES
(15, 'F1', '#e10600', 'active'),
(16, 'F2', '#366092', 'active'),
(17, 'F3', '#c904d7', 'active');

--
-- Déclencheurs `categories`
--
DROP TRIGGER IF EXISTS `trg_categories_before_delete`;
DELIMITER $$
CREATE TRIGGER `trg_categories_before_delete` BEFORE DELETE ON `categories` FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM seasons WHERE category_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : des saisons sont rattachées à cette catégorie.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `circuits`
--

DROP TABLE IF EXISTS `circuits`;
CREATE TABLE IF NOT EXISTS `circuits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `country_id` int NOT NULL,
  `status` enum('active','desactive') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `idx_circuit_status` (`status`),
  KEY `idx_circuit_country` (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `circuits`
--

INSERT INTO `circuits` (`id`, `name`, `country_id`, `status`) VALUES
(24, 'Sakhir', 19, 'active'),
(25, 'Sepang', 20, 'active'),
(26, 'Melbourne', 21, 'active'),
(27, 'Imola', 22, 'active'),
(28, 'Nurburgring', 23, 'active'),
(29, 'Barcelone', 24, 'active'),
(30, 'Monte-Carlo', 25, 'active'),
(31, 'Silverstone', 26, 'active'),
(32, 'Montreal', 27, 'active'),
(33, 'Indianapolis', 28, 'active'),
(34, 'Nevers Magny-Cours', 1, 'active'),
(35, 'Hockenheim', 23, 'active'),
(36, 'Hungaroring', 29, 'active'),
(37, 'Istanbul', 30, 'active'),
(38, 'Monza', 22, 'active'),
(39, 'Shanghai', 31, 'active'),
(40, 'Suzuka', 32, 'active'),
(41, 'Interlagos', 33, 'active');

--
-- Déclencheurs `circuits`
--
DROP TRIGGER IF EXISTS `trg_circuits_before_delete`;
DELIMITER $$
CREATE TRIGGER `trg_circuits_before_delete` BEFORE DELETE ON `circuits` FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM gp WHERE circuit_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : ce circuit est rattaché à un Grand Prix.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` char(3) DEFAULT NULL,
  `flag` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `unique_code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `flag`) VALUES
(1, 'France', 'FRA', 'img/flags/france.png'),
(19, 'Bahreïn', 'BAH', 'img/flags/bahrein.png'),
(20, 'Malaisie', 'MAL', 'img/flags/malaisie.png'),
(21, 'Australie', 'AUS', 'img/flags/australie.png'),
(22, 'Italie', 'ITA', 'img/flags/italie.png'),
(23, 'Allemagne', 'ALL', 'img/flags/allemagne.png'),
(24, 'Espagne', 'ESP', 'img/flags/espagne.png'),
(25, 'Monaco', 'MON', 'img/flags/monaco.png'),
(26, 'Grande-Bretagne', 'GBR', 'img/flags/grandebretagne.png'),
(27, 'Canada', 'CAN', 'img/flags/canada.png'),
(28, 'États-Unis', 'USA', 'img/flags/etatsunis.png'),
(29, 'Hongrie', 'HON', 'img/flags/hongrie.png'),
(30, 'Turquie', 'TUR', ''),
(31, 'Chine', 'CHI', 'img/flags/chine.png'),
(32, 'Japon', 'JAP', 'img/flags/japon.png'),
(33, 'Brésil', 'BRE', 'img/flags/bresil.png'),
(34, 'Suisse', 'SUI', 'img/flags/suisse.png'),
(35, 'Autriche', 'AUT', 'img/flags/autriche.png'),
(36, 'Pays-Bas', 'P-B', 'img/flags/paysbas.png');

--
-- Déclencheurs `countries`
--
DROP TRIGGER IF EXISTS `trg_countries_before_delete`;
DELIMITER $$
CREATE TRIGGER `trg_countries_before_delete` BEFORE DELETE ON `countries` FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM circuits WHERE country_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : ce pays est rattaché à un circuit.';
    ELSEIF EXISTS (SELECT 1 FROM teams WHERE country_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : ce pays est rattaché à une équipe.';
    ELSEIF EXISTS (SELECT 1 FROM drivers WHERE country_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : ce pays est rattaché à un pilote.';
    ELSEIF EXISTS (SELECT 1 FROM gp 
                   JOIN seasons ON gp.season_id = seasons.id 
                   JOIN categories ON seasons.category_id = categories.id
                   WHERE gp.circuit_id IN (SELECT id FROM circuits WHERE country_id = OLD.id)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : ce pays est rattaché à un GP.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `drivers`
--

DROP TABLE IF EXISTS `drivers`;
CREATE TABLE IF NOT EXISTS `drivers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nickname` varchar(100) NOT NULL,
  `country_id` int NOT NULL DEFAULT '1',
  `status` enum('active','desactive') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nickname` (`nickname`),
  KEY `idx_driver_status` (`status`),
  KEY `idx_driver_country` (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `drivers`
--

INSERT INTO `drivers` (`id`, `nickname`, `country_id`, `status`) VALUES
(1, '[Driver removed]', 1, 'active'),
(14, 'Jujubiker', 1, 'active'),
(15, 'Martlio', 1, 'active'),
(16, 'Guignol81', 1, 'active'),
(17, 'Jimboparisgo', 1, 'active'),
(18, 'Chapi-chapo', 1, 'active'),
(19, 'Didi511', 1, 'active'),
(20, 'Senna76', 1, 'active'),
(21, 'Fox', 1, 'active'),
(22, 'Nordschleife', 1, 'active'),
(23, 'Ludovico6', 1, 'active'),
(24, 'Gafit', 1, 'active');

--
-- Déclencheurs `drivers`
--
DROP TRIGGER IF EXISTS `trg_drivers_before_delete`;
DELIMITER $$
CREATE TRIGGER `trg_drivers_before_delete` BEFORE DELETE ON `drivers` FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM teams_drivers WHERE driver_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : ce pilote est rattaché à une équipe pour une saison.';
    ELSEIF EXISTS (SELECT 1 FROM gp_points WHERE driver_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : ce pilote est rattaché à des résultats de GP.';
    ELSEIF EXISTS (SELECT 1 FROM gp_stats WHERE pole_position_driver = OLD.id OR fastest_lap_driver = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : ce pilote est rattaché à des statistiques de GP.';
    ELSEIF EXISTS (SELECT 1 FROM penalties WHERE driver_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : ce pilote est rattaché à des pénalités.';
    ELSEIF EXISTS (SELECT 1 FROM manual_adjustments WHERE driver_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : ce pilote est rattaché à des ajustements manuels.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `drivers_palmares`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `drivers_palmares`;
CREATE TABLE IF NOT EXISTS `drivers_palmares` (
`category` varchar(50)
,`driver_id` int
,`nickname` varchar(100)
,`titles` decimal(23,0)
,`vice_titles` decimal(23,0)
,`third_places` decimal(23,0)
,`total_points` decimal(56,1)
,`wins` decimal(45,0)
,`podiums` decimal(45,0)
,`total_gp` bigint
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `drivers_standings`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `drivers_standings`;
CREATE TABLE IF NOT EXISTS `drivers_standings` (
`season_id` int
,`season_number` int
,`season_status` enum('active','desactive')
,`category` varchar(50)
,`driver_id` int
,`nickname` varchar(100)
,`team_name` varchar(100)
,`total_points` decimal(34,1)
,`wins` decimal(23,0)
,`podiums` decimal(23,0)
);

-- --------------------------------------------------------

--
-- Structure de la table `gp`
--

DROP TABLE IF EXISTS `gp`;
CREATE TABLE IF NOT EXISTS `gp` (
  `id` int NOT NULL AUTO_INCREMENT,
  `season_id` int NOT NULL,
  `circuit_id` int NOT NULL,
  `gp_ordre` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_gp_season` (`season_id`),
  KEY `idx_gp_circuit` (`circuit_id`),
  KEY `idx_gp_season_ordre` (`season_id`,`gp_ordre`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `gp`
--

INSERT INTO `gp` (`id`, `season_id`, `circuit_id`, `gp_ordre`) VALUES
(26, 20, 24, 1),
(27, 20, 25, 2),
(28, 20, 26, 3),
(29, 20, 27, 4),
(30, 20, 28, 5),
(31, 20, 29, 6),
(32, 20, 30, 7),
(33, 20, 31, 8),
(34, 20, 32, 9),
(35, 20, 33, 10),
(36, 20, 34, 11),
(37, 20, 35, 12),
(38, 20, 36, 13),
(39, 20, 37, 14),
(40, 20, 38, 15),
(41, 20, 39, 16),
(42, 20, 40, 17),
(43, 20, 41, 18);

--
-- Déclencheurs `gp`
--
DROP TRIGGER IF EXISTS `before_delete_gp`;
DELIMITER $$
CREATE TRIGGER `before_delete_gp` BEFORE DELETE ON `gp` FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM gp_points WHERE gp_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : des points existent pour ce GP.';
    END IF;

    IF EXISTS (SELECT 1 FROM gp_stats WHERE gp_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : des stats existent pour ce GP.';
    END IF;

    IF EXISTS (SELECT 1 FROM penalties WHERE gp_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : des penalties existent pour ce GP.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `gp_points`
--

DROP TABLE IF EXISTS `gp_points`;
CREATE TABLE IF NOT EXISTS `gp_points` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gp_id` int NOT NULL,
  `driver_id` int NOT NULL,
  `team_id` int NOT NULL,
  `position` int DEFAULT NULL,
  `points_numeric` decimal(4,1) NOT NULL DEFAULT '0.0',
  `points_text` varchar(3) DEFAULT NULL,
  `driver_unique_id` int GENERATED ALWAYS AS ((case when (`driver_id` = 1) then NULL else `driver_id` end)) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_gp_position` (`gp_id`,`position`),
  UNIQUE KEY `uq_gp_driver` (`gp_id`,`driver_unique_id`),
  KEY `idx_points_gp` (`gp_id`),
  KEY `idx_points_driver` (`driver_id`),
  KEY `idx_points_team` (`team_id`),
  KEY `idx_points_gp_driver` (`gp_id`,`driver_id`),
  KEY `idx_points_driver_team` (`driver_id`,`team_id`)
) ;

--
-- Déchargement des données de la table `gp_points`
--

INSERT INTO `gp_points` (`id`, `gp_id`, `driver_id`, `team_id`, `position`, `points_numeric`, `points_text`) VALUES
(36, 26, 16, 31, 3, 0.0, NULL),
(37, 26, 19, 34, 4, 0.0, NULL),
(38, 26, 24, 39, 5, 0.0, NULL),
(39, 26, 17, 32, 6, 0.0, NULL),
(43, 26, 1, 1, 1, 0.0, NULL),
(45, 26, 1, 1, 2, 0.0, NULL),
(49, 27, 19, 34, 2, 0.0, NULL),
(50, 27, 16, 31, 3, 0.0, NULL),
(52, 27, 17, 32, 5, 0.0, NULL),
(53, 27, 1, 1, 1, 0.0, NULL),
(54, 28, 14, 29, 2, 0.0, NULL),
(55, 28, 16, 31, 5, 0.0, NULL),
(56, 28, 20, 35, 7, 0.0, NULL),
(57, 28, 24, 39, 11, 0.0, NULL),
(58, 28, 1, 1, 1, 0.0, NULL),
(59, 28, 1, 1, 3, 0.0, NULL),
(60, 29, 14, 29, 2, 0.0, NULL),
(61, 29, 20, 35, 3, 0.0, NULL),
(62, 29, 1, 1, 1, 0.0, NULL),
(63, 29, 16, 31, 6, 0.0, NULL),
(64, 30, 14, 29, 1, 0.0, NULL),
(65, 30, 1, 1, 2, 0.0, NULL),
(66, 30, 1, 1, 3, 0.0, NULL),
(67, 30, 19, 34, 5, 0.0, NULL),
(68, 30, 16, 31, 7, 0.0, NULL),
(69, 30, 17, 32, 10, 0.0, NULL),
(70, 31, 14, 29, 1, 0.0, NULL),
(71, 31, 1, 1, 2, 0.0, NULL),
(72, 31, 17, 32, 3, 0.0, NULL),
(73, 31, 19, 34, 5, 0.0, NULL),
(74, 31, 16, 31, 8, 0.0, NULL),
(75, 31, 24, 39, 11, 0.0, NULL),
(76, 32, 14, 29, 1, 0.0, NULL),
(77, 32, 1, 1, 2, 0.0, NULL),
(78, 32, 1, 1, 3, 0.0, NULL),
(79, 32, 17, 32, 8, 0.0, NULL),
(80, 32, 24, 39, 10, 0.0, NULL),
(81, 32, 16, 31, 11, 0.0, NULL),
(82, 33, 15, 30, 1, 0.0, NULL),
(83, 33, 14, 29, 2, 0.0, NULL),
(84, 33, 18, 33, 3, 0.0, NULL),
(85, 34, 14, 29, 1, 0.0, NULL),
(86, 34, 1, 1, 2, 0.0, NULL),
(87, 34, 17, 32, 3, 0.0, NULL),
(88, 34, 16, 31, 6, 0.0, NULL),
(89, 34, 20, 35, 8, 0.0, NULL),
(90, 35, 14, 29, 1, 0.0, NULL),
(91, 35, 1, 1, 2, 0.0, NULL),
(92, 35, 16, 31, 3, 0.0, NULL),
(93, 35, 17, 32, 8, 0.0, NULL),
(94, 35, 18, 33, 6, 0.0, NULL),
(95, 35, 19, 34, 5, 0.0, NULL),
(96, 35, 24, 39, 11, 0.0, NULL),
(97, 36, 15, 30, 1, 0.0, NULL),
(98, 36, 14, 29, 2, 0.0, NULL),
(99, 36, 16, 31, 3, 0.0, NULL),
(100, 36, 17, 32, 5, 0.0, NULL),
(101, 36, 18, 33, 8, 0.0, NULL),
(102, 36, 19, 34, 9, 0.0, NULL),
(103, 36, 20, 35, 4, 0.0, NULL),
(104, 36, 21, 36, 7, 0.0, NULL),
(105, 36, 23, 38, 6, 0.0, NULL),
(106, 36, 24, 39, 10, 0.0, NULL),
(107, 37, 14, 29, 1, 0.0, NULL),
(108, 37, 15, 30, 2, 0.0, NULL),
(109, 37, 1, 1, 3, 0.0, NULL),
(110, 37, 16, 31, 4, 0.0, NULL),
(111, 37, 17, 32, 6, 0.0, NULL),
(112, 37, 23, 38, 8, 0.0, NULL),
(113, 38, 15, 30, 1, 0.0, NULL),
(114, 38, 14, 29, 2, 0.0, NULL),
(115, 38, 21, 36, 3, 0.0, NULL),
(116, 38, 22, 37, 4, 0.0, NULL),
(117, 38, 17, 32, 5, 0.0, NULL),
(118, 38, 18, 33, 6, 0.0, NULL),
(119, 38, 23, 38, 8, 0.0, NULL),
(120, 38, 16, 31, 9, 0.0, NULL),
(121, 38, 24, 39, 11, 0.0, NULL),
(122, 39, 14, 29, 4, 0.0, NULL),
(123, 39, 15, 30, 1, 0.0, NULL),
(124, 39, 16, 31, 2, 0.0, NULL),
(125, 39, 17, 32, 7, 0.0, NULL),
(128, 39, 18, 33, 5, 0.0, NULL),
(129, 39, 22, 37, 3, 0.0, NULL),
(130, 39, 23, 38, 9, 0.0, NULL),
(131, 39, 24, 39, 11, 0.0, NULL),
(132, 40, 14, 29, 2, 0.0, NULL),
(133, 40, 15, 30, 1, 0.0, NULL),
(134, 40, 16, 31, 10, 0.0, NULL),
(135, 40, 17, 32, 3, 0.0, NULL),
(136, 40, 18, 33, 4, 0.0, NULL),
(137, 40, 19, 34, 8, 0.0, NULL),
(138, 40, 20, 35, 7, 0.0, NULL),
(139, 40, 22, 37, 9, 0.0, NULL),
(140, 40, 24, 39, 11, 0.0, NULL),
(141, 41, 15, 30, 1, 0.0, NULL),
(142, 41, 14, 29, 2, 0.0, NULL),
(143, 41, 16, 31, 5, 0.0, NULL),
(144, 41, 18, 33, 3, 0.0, NULL),
(145, 41, 21, 36, 4, 0.0, NULL),
(146, 42, 14, 29, 1, 0.0, NULL),
(147, 42, 1, 1, 2, 0.0, NULL),
(148, 42, 1, 1, 3, 0.0, NULL),
(149, 42, 17, 32, 4, 0.0, NULL),
(150, 42, 18, 33, 5, 0.0, NULL),
(151, 42, 20, 35, 6, 0.0, NULL),
(152, 42, 16, 31, 7, 0.0, NULL),
(153, 42, 19, 34, 8, 0.0, NULL),
(154, 42, 22, 37, 9, 0.0, NULL),
(155, 43, 14, 29, 1, 0.0, NULL),
(156, 43, 1, 1, 2, 0.0, NULL),
(157, 43, 16, 31, 8, 0.0, NULL),
(158, 43, 17, 32, 3, 0.0, NULL),
(159, 43, 18, 33, 4, 0.0, NULL),
(160, 43, 19, 34, 7, 0.0, NULL),
(161, 43, 20, 35, 5, 0.0, NULL),
(162, 43, 21, 36, 10, 0.0, NULL),
(163, 43, 22, 37, 9, 0.0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `gp_stats`
--

DROP TABLE IF EXISTS `gp_stats`;
CREATE TABLE IF NOT EXISTS `gp_stats` (
  `gp_id` int NOT NULL,
  `pole_position_driver` int DEFAULT NULL,
  `pole_position_time` varchar(50) DEFAULT NULL,
  `fastest_lap_driver` int DEFAULT NULL,
  `fastest_lap_time` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`gp_id`),
  KEY `idx_stats_pole_driver` (`pole_position_driver`),
  KEY `idx_stats_fastest_driver` (`fastest_lap_driver`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `manual_adjustments`
--

DROP TABLE IF EXISTS `manual_adjustments`;
CREATE TABLE IF NOT EXISTS `manual_adjustments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `season_id` int NOT NULL,
  `driver_id` int DEFAULT NULL,
  `team_id` int DEFAULT NULL,
  `points` decimal(4,1) NOT NULL,
  `comment` text,
  PRIMARY KEY (`id`),
  KEY `idx_ma_season` (`season_id`),
  KEY `idx_ma_driver` (`driver_id`),
  KEY `idx_ma_team` (`team_id`),
  KEY `idx_ma_driver_team` (`driver_id`,`team_id`)
) ;

--
-- Déchargement des données de la table `manual_adjustments`
--

INSERT INTO `manual_adjustments` (`id`, `season_id`, `driver_id`, `team_id`, `points`, `comment`) VALUES
(45, 20, 14, NULL, 141.0, NULL),
(46, 20, 15, NULL, 68.0, NULL),
(47, 20, 16, NULL, 57.0, NULL),
(48, 20, 17, NULL, 51.0, NULL),
(49, 20, 18, NULL, 38.0, NULL),
(50, 20, 19, NULL, 29.0, NULL),
(51, 20, 20, NULL, 23.0, NULL),
(52, 20, 21, NULL, 13.0, ''),
(53, 20, 22, NULL, 11.0, NULL),
(54, 20, 23, NULL, 5.0, NULL),
(55, 20, 24, NULL, 4.0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `penalties`
--

DROP TABLE IF EXISTS `penalties`;
CREATE TABLE IF NOT EXISTS `penalties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gp_id` int NOT NULL,
  `driver_id` int DEFAULT NULL,
  `team_id` int DEFAULT NULL,
  `points_removed` int NOT NULL,
  `comment` text,
  PRIMARY KEY (`id`),
  KEY `gp_id` (`gp_id`),
  KEY `driver_id` (`driver_id`),
  KEY `team_id` (`team_id`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

DROP TABLE IF EXISTS `seasons`;
CREATE TABLE IF NOT EXISTS `seasons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `season_number` int NOT NULL,
  `category_id` int NOT NULL,
  `videogame` varchar(100) NOT NULL,
  `platform` varchar(100) NOT NULL,
  `status` enum('active','desactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_season_category` (`season_number`,`category_id`),
  KEY `idx_fk_category` (`category_id`),
  KEY `idx_season_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `seasons`
--

INSERT INTO `seasons` (`id`, `season_number`, `category_id`, `videogame`, `platform`, `status`) VALUES
(20, 1, 15, 'F1 Championship Edition', 'PS3', 'active'),
(22, 1, 16, 'F1 Championship Edition', 'PS3', 'active');

--
-- Déclencheurs `seasons`
--
DROP TRIGGER IF EXISTS `before_delete_season`;
DELIMITER $$
CREATE TRIGGER `before_delete_season` BEFORE DELETE ON `seasons` FOR EACH ROW BEGIN
    IF EXISTS (SELECT 1 FROM gp WHERE season_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : des GP existent pour cette Saison.';
    END IF;

    IF EXISTS (SELECT 1 FROM manual_adjustments WHERE season_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : des ajustements manuels existent pour cette Saison.';
    END IF;

    IF EXISTS (SELECT 1 FROM teams_drivers WHERE season_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Impossible de supprimer : des teams_drivers existent pour cette Saison.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--

DROP TABLE IF EXISTS `teams`;
CREATE TABLE IF NOT EXISTS `teams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `country_id` int NOT NULL,
  `status` enum('active','desactive') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `idx_team_status` (`status`),
  KEY `idx_team_country` (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `teams`
--

INSERT INTO `teams` (`id`, `name`, `logo`, `color`, `country_id`, `status`) VALUES
(1, '[Team removed]', '', NULL, 1, 'active'),
(29, 'Toyota', '', '#f90606', 32, 'active'),
(30, 'Williams', 'img/teams/williams.png', '#03A8EA', 26, 'active'),
(31, 'Ferrari', 'img/teams/ferrari.png', '#FE0000', 22, 'active'),
(32, 'Renault', '', '#8e9018', 1, 'active'),
(33, 'Super Aguri', '', '#ff0000', 32, 'active'),
(34, 'Honda', '', '#000000', 32, 'active'),
(35, 'BMW Sauber', '', '#139fcd', 34, 'active'),
(36, 'McLaren', 'img/teams/mclaren.png', '#FF8500', 26, 'active'),
(37, 'Red Bull', 'img/teams/redbull.png', '#15185E', 35, 'active'),
(38, 'Toro Rosso', '', '#2f2cf2', 22, 'active'),
(39, 'Spyker', '', NULL, 36, 'active');

--
-- Déclencheurs `teams`
--
DROP TRIGGER IF EXISTS `trg_teams_before_delete`;
DELIMITER $$
CREATE TRIGGER `trg_teams_before_delete` BEFORE DELETE ON `teams` FOR EACH ROW BEGIN
    -- Vérifier teams_drivers
    IF EXISTS (SELECT 1 FROM teams_drivers WHERE team_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'Impossible de supprimer : cette équipe est rattachée à un pilote pour une saison.';

    -- Vérifier gp_points
    ELSEIF EXISTS (SELECT 1 FROM gp_points WHERE team_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'Impossible de supprimer : cette équipe est rattachée à des résultats de GP.';

    -- Vérifier gp_stats via teams_drivers
    ELSEIF EXISTS (
        SELECT 1
        FROM gp_stats gs
        JOIN teams_drivers td ON td.driver_id = gs.pole_position_driver OR td.driver_id = gs.fastest_lap_driver
        WHERE td.team_id = OLD.id
    ) THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'Impossible de supprimer : cette équipe est rattachée à des statistiques de GP.';

    -- Vérifier penalties via teams_drivers
    ELSEIF EXISTS (
        SELECT 1
        FROM penalties p
        JOIN teams_drivers td ON td.driver_id = p.driver_id
        WHERE td.team_id = OLD.id
    ) THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'Impossible de supprimer : cette équipe est rattachée à des pénalités.';

    -- Vérifier manual_adjustments
    ELSEIF EXISTS (SELECT 1 FROM manual_adjustments WHERE team_id = OLD.id) THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'Impossible de supprimer : cette équipe est rattachée à des ajustements manuels.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `teams_drivers`
--

DROP TABLE IF EXISTS `teams_drivers`;
CREATE TABLE IF NOT EXISTS `teams_drivers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `season_id` int NOT NULL,
  `driver_id` int NOT NULL,
  `team_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_td_unique` (`season_id`,`driver_id`,`team_id`),
  UNIQUE KEY `uniq_driver_per_season` (`season_id`,`driver_id`),
  KEY `idx_td_season` (`season_id`),
  KEY `idx_td_driver` (`driver_id`),
  KEY `idx_td_team` (`team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `teams_drivers`
--

INSERT INTO `teams_drivers` (`id`, `season_id`, `driver_id`, `team_id`) VALUES
(17, 20, 14, 29),
(18, 20, 15, 30),
(19, 20, 16, 31),
(20, 20, 17, 32),
(22, 20, 18, 33),
(23, 20, 19, 34),
(24, 20, 20, 35),
(25, 20, 21, 36),
(26, 20, 22, 37),
(27, 20, 23, 38),
(28, 20, 24, 39);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `teams_palmares`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `teams_palmares`;
CREATE TABLE IF NOT EXISTS `teams_palmares` (
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
DROP VIEW IF EXISTS `teams_standings`;
CREATE TABLE IF NOT EXISTS `teams_standings` (
`season_id` int
,`season_number` int
,`category` varchar(50)
,`team_id` int
,`team_name` varchar(100)
,`total_points` decimal(34,1)
);

-- --------------------------------------------------------

--
-- Structure de la table `updates_log`
--

DROP TABLE IF EXISTS `updates_log`;
CREATE TABLE IF NOT EXISTS `updates_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `season_id` int DEFAULT NULL,
  `gp_id` int DEFAULT NULL,
  `table_name` varchar(50) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int DEFAULT NULL,
  `action` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `fk_updates_season` (`season_id`),
  KEY `fk_updates_gp` (`gp_id`),
  KEY `fk_updates_user` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `updates_log`
--

INSERT INTO `updates_log` (`id`, `season_id`, `gp_id`, `table_name`, `updated_at`, `updated_by`, `action`) VALUES
(1, 22, NULL, 'manual_adjustments', '2025-12-15 13:14:31', 1, 'update'),
(2, NULL, NULL, 'penalties', '2025-12-15 13:14:41', 1, 'update'),
(3, NULL, NULL, 'gp_points', '2025-12-15 13:25:45', 1, 'update'),
(4, NULL, NULL, 'gp_points', '2025-12-15 13:39:50', 1, 'update'),
(5, NULL, 37, 'gp_stats', '2025-12-16 11:59:50', 1, 'create'),
(6, NULL, NULL, 'penalties', '2025-12-23 10:11:30', 1, 'create'),
(7, NULL, NULL, 'penalties', '2025-12-23 10:11:41', 1, 'update'),
(8, NULL, NULL, 'penalties', '2025-12-23 10:22:43', 1, 'update'),
(9, NULL, NULL, 'penalties', '2025-12-23 10:22:56', 1, 'update'),
(10, NULL, NULL, 'penalties', '2025-12-23 10:41:02', 1, 'update'),
(11, NULL, 43, 'penalties', '2025-12-23 11:26:04', 1, 'create'),
(12, NULL, NULL, 'gp_points', '2025-12-26 15:39:13', 1, 'update'),
(13, NULL, NULL, 'gp_points', '2025-12-26 15:39:18', 1, 'update'),
(14, NULL, NULL, 'gp_points', '2025-12-26 15:40:21', 1, 'delete'),
(15, NULL, 37, 'gp_stats', '2025-12-26 17:09:57', 1, 'update'),
(16, NULL, 37, 'gp_stats', '2025-12-26 17:13:42', 1, 'update'),
(17, 22, NULL, 'manual_adjustments', '2025-12-28 00:24:40', 1, 'create'),
(18, 22, NULL, 'manual_adjustments', '2025-12-28 00:25:08', 1, 'create'),
(19, 22, NULL, 'manual_adjustments', '2025-12-28 00:25:22', 1, 'delete'),
(20, 22, NULL, 'manual_adjustments', '2025-12-28 09:12:43', 1, 'delete'),
(21, 22, NULL, 'manual_adjustments', '2025-12-28 09:12:48', 1, 'delete'),
(22, 22, NULL, 'manual_adjustments', '2025-12-28 09:12:52', 1, 'delete'),
(23, NULL, NULL, 'gp_points', '2025-12-28 09:13:21', 1, 'delete'),
(24, NULL, NULL, 'gp_points', '2025-12-28 09:13:25', 1, 'delete'),
(25, NULL, NULL, 'gp_points', '2025-12-28 09:13:29', 1, 'delete'),
(26, NULL, NULL, 'gp_points', '2025-12-28 09:13:33', 1, 'delete'),
(27, NULL, NULL, 'gp_points', '2025-12-28 09:13:37', 1, 'delete'),
(28, NULL, NULL, 'penalties', '2025-12-28 09:16:03', 1, 'delete'),
(29, NULL, NULL, 'penalties', '2025-12-28 09:16:10', 1, 'delete'),
(30, NULL, 43, 'penalties', '2025-12-28 09:16:15', 1, 'delete'),
(31, NULL, NULL, 'gp_stats', '2025-12-28 09:22:48', 1, 'update'),
(32, NULL, NULL, 'gp_stats', '2025-12-28 09:22:52', 1, 'delete'),
(33, NULL, 31, 'gp_stats', '2025-12-28 09:22:55', 1, 'delete'),
(34, NULL, 37, 'gp_stats', '2025-12-28 09:22:58', 1, 'delete'),
(35, NULL, NULL, 'gp_points', '2025-12-28 09:24:13', 1, 'delete'),
(36, NULL, NULL, 'penalties', '2025-12-28 09:24:32', 1, 'delete'),
(37, NULL, 26, 'gp_points', '2025-12-28 09:35:52', 1, 'create'),
(38, NULL, 26, 'gp_points', '2025-12-28 09:36:20', 1, 'delete'),
(39, NULL, 26, 'gp_stats', '2025-12-28 09:57:33', 1, 'create'),
(40, NULL, NULL, 'gp_stats', '2025-12-28 09:58:12', 1, 'update'),
(41, NULL, NULL, 'gp_stats', '2025-12-28 09:59:12', 1, 'update'),
(42, NULL, NULL, 'gp_stats', '2025-12-28 09:59:36', 1, 'delete');

--
-- Déclencheurs `updates_log`
--
DROP TRIGGER IF EXISTS `trg_updates_log_check`;
DELIMITER $$
CREATE TRIGGER `trg_updates_log_check` BEFORE INSERT ON `updates_log` FOR EACH ROW BEGIN
IF NEW.season_id IS NULL AND NEW.gp_id IS NULL THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Saison ou GP doit être renseigné.';
END IF;
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `trg_updates_log_check_update`;
DELIMITER $$
CREATE TRIGGER `trg_updates_log_check_update` BEFORE UPDATE ON `updates_log` FOR EACH ROW BEGIN
IF NEW.season_id IS NULL AND NEW.gp_id IS NULL THEN
SIGNAL SQLSTATE '45000'
SET MESSAGE_TEXT = 'Saison ou GP doit être renseigné.';
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `role_id`) VALUES
(1, 'paraguayan99@laposte.net', '$2y$10$jI3l3bZc92tGCe.OqMbVWuu/diVN7AlMHhxkEWUKR8BxgY3ardwYC', 1),
(2, 'moderateur@gmail.com', '$2y$10$7YqgqnOXLMpR7GxIkDSb/OWnUGtFIa8Ip6yXgOPyNW0GZ5dkmLQt6', 2),
(3, 'utilisateur@gmail.com', '$2y$10$S6lHWQKDdWupIJfwkRgnzOYeNhRGsTOqv8jlInjBV7SYwjNIcDjT2', 3);

-- --------------------------------------------------------

--
-- Structure de la vue `drivers_palmares`
--
DROP TABLE IF EXISTS `drivers_palmares`;

DROP VIEW IF EXISTS `drivers_palmares`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `drivers_palmares`  AS SELECT `ds`.`category` AS `category`, `d`.`id` AS `driver_id`, `d`.`nickname` AS `nickname`, sum((case when ((`ds`.`season_status` = 'desactive') and (`ds`.`total_points` = (select max(`ds2`.`total_points`) from `drivers_standings` `ds2` where ((`ds2`.`season_id` = `ds`.`season_id`) and (`ds2`.`category` = `ds`.`category`))))) then 1 else 0 end)) AS `titles`, sum((case when ((`ds`.`season_status` = 'desactive') and (`ds`.`total_points` = (select max(`ds2`.`total_points`) from `drivers_standings` `ds2` where ((`ds2`.`season_id` = `ds`.`season_id`) and (`ds2`.`category` = `ds`.`category`) and (`ds2`.`total_points` < (select max(`ds3`.`total_points`) from `drivers_standings` `ds3` where ((`ds3`.`season_id` = `ds`.`season_id`) and (`ds3`.`category` = `ds`.`category`)))))))) then 1 else 0 end)) AS `vice_titles`, sum((case when ((`ds`.`season_status` = 'desactive') and (`ds`.`total_points` = (select distinct `ds2`.`total_points` from `drivers_standings` `ds2` where ((`ds2`.`season_id` = `ds`.`season_id`) and (`ds2`.`category` = `ds`.`category`)) order by `ds2`.`total_points` desc limit 2,1))) then 1 else 0 end)) AS `third_places`, sum(`ds`.`total_points`) AS `total_points`, sum(`ds`.`wins`) AS `wins`, sum(`ds`.`podiums`) AS `podiums`, (select count(distinct `gp_pts`.`gp_id`) from ((`gp_points` `gp_pts` join `gp` `g` on((`g`.`id` = `gp_pts`.`gp_id`))) join `drivers_standings` `dsx` on(((`dsx`.`driver_id` = `gp_pts`.`driver_id`) and (`dsx`.`season_id` = `g`.`season_id`) and (`dsx`.`category` = `ds`.`category`)))) where (`gp_pts`.`driver_id` = `d`.`id`)) AS `total_gp` FROM (`drivers_standings` `ds` join `drivers` `d` on((`d`.`id` = `ds`.`driver_id`))) GROUP BY `ds`.`category`, `d`.`id`, `d`.`nickname` ;

-- --------------------------------------------------------

--
-- Structure de la vue `drivers_standings`
--
DROP TABLE IF EXISTS `drivers_standings`;

DROP VIEW IF EXISTS `drivers_standings`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `drivers_standings`  AS SELECT `s`.`id` AS `season_id`, `s`.`season_number` AS `season_number`, `s`.`status` AS `season_status`, `c`.`name` AS `category`, `d`.`id` AS `driver_id`, `d`.`nickname` AS `nickname`, `t`.`name` AS `team_name`, ((coalesce(sum(`gp_pts`.`points_numeric`),0) + coalesce(`ma`.`total_points`,0)) - coalesce(sum(`p`.`points_removed`),0)) AS `total_points`, sum((case when (`gp_pts`.`position` = 1) then 1 else 0 end)) AS `wins`, sum((case when (`gp_pts`.`position` in (1,2,3)) then 1 else 0 end)) AS `podiums` FROM ((((((((`seasons` `s` join `categories` `c` on((`c`.`id` = `s`.`category_id`))) join `gp` `g` on((`g`.`season_id` = `s`.`id`))) join `gp_points` `gp_pts` on((`gp_pts`.`gp_id` = `g`.`id`))) join `drivers` `d` on((`d`.`id` = `gp_pts`.`driver_id`))) left join `teams_drivers` `td` on(((`td`.`driver_id` = `d`.`id`) and (`td`.`season_id` = `s`.`id`)))) left join `teams` `t` on((`t`.`id` = `td`.`team_id`))) left join (select `manual_adjustments`.`season_id` AS `season_id`,`manual_adjustments`.`driver_id` AS `driver_id`,sum(`manual_adjustments`.`points`) AS `total_points` from `manual_adjustments` group by `manual_adjustments`.`season_id`,`manual_adjustments`.`driver_id`) `ma` on(((`ma`.`season_id` = `s`.`id`) and (`ma`.`driver_id` = `d`.`id`)))) left join `penalties` `p` on(((`p`.`driver_id` = `d`.`id`) and (`p`.`gp_id` = `g`.`id`)))) GROUP BY `s`.`id`, `s`.`season_number`, `s`.`status`, `c`.`name`, `d`.`id`, `d`.`nickname`, `t`.`name` ;

-- --------------------------------------------------------

--
-- Structure de la vue `teams_palmares`
--
DROP TABLE IF EXISTS `teams_palmares`;

DROP VIEW IF EXISTS `teams_palmares`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `teams_palmares`  AS SELECT `ts`.`category` AS `category`, `t`.`id` AS `team_id`, `t`.`name` AS `team_name`, sum((case when ((`s`.`status` = 'desactive') and (`ts`.`total_points` = (select max(`ts2`.`total_points`) from `teams_standings` `ts2` where (`ts2`.`season_id` = `ts`.`season_id`)))) then 1 else 0 end)) AS `titles`, sum(`ts`.`total_points`) AS `total_points` FROM ((`teams_standings` `ts` join `teams` `t` on((`t`.`id` = `ts`.`team_id`))) join `seasons` `s` on((`s`.`id` = `ts`.`season_id`))) GROUP BY `ts`.`category`, `t`.`id`, `t`.`name` ;

-- --------------------------------------------------------

--
-- Structure de la vue `teams_standings`
--
DROP TABLE IF EXISTS `teams_standings`;

DROP VIEW IF EXISTS `teams_standings`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `teams_standings`  AS SELECT `s`.`id` AS `season_id`, `s`.`season_number` AS `season_number`, `c`.`name` AS `category`, `t`.`id` AS `team_id`, `t`.`name` AS `team_name`, ((coalesce(sum(`gp_pts`.`points_numeric`),0) + coalesce(`ma`.`total_points`,0)) - coalesce(sum(`p`.`points_removed`),0)) AS `total_points` FROM ((((((`seasons` `s` join `categories` `c` on((`c`.`id` = `s`.`category_id`))) join `teams` `t`) left join `gp` `g` on((`g`.`season_id` = `s`.`id`))) join `gp_points` `gp_pts` on(((`gp_pts`.`gp_id` = `g`.`id`) and (`gp_pts`.`team_id` = `t`.`id`)))) left join (select `manual_adjustments`.`season_id` AS `season_id`,`manual_adjustments`.`team_id` AS `team_id`,sum(`manual_adjustments`.`points`) AS `total_points` from `manual_adjustments` group by `manual_adjustments`.`season_id`,`manual_adjustments`.`team_id`) `ma` on(((`ma`.`season_id` = `s`.`id`) and (`ma`.`team_id` = `t`.`id`)))) left join `penalties` `p` on(((`p`.`gp_id` = `g`.`id`) and (`p`.`team_id` = `t`.`id`)))) GROUP BY `s`.`id`, `s`.`season_number`, `c`.`name`, `t`.`id`, `t`.`name` ;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `circuits`
--
ALTER TABLE `circuits`
  ADD CONSTRAINT `circuits_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

--
-- Contraintes pour la table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

--
-- Contraintes pour la table `gp`
--
ALTER TABLE `gp`
  ADD CONSTRAINT `gp_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gp_ibfk_2` FOREIGN KEY (`circuit_id`) REFERENCES `circuits` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `gp_points`
--
ALTER TABLE `gp_points`
  ADD CONSTRAINT `gp_points_ibfk_1` FOREIGN KEY (`gp_id`) REFERENCES `gp` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gp_points_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`),
  ADD CONSTRAINT `gp_points_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`);

--
-- Contraintes pour la table `gp_stats`
--
ALTER TABLE `gp_stats`
  ADD CONSTRAINT `gp_stats_ibfk_1` FOREIGN KEY (`gp_id`) REFERENCES `gp` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gp_stats_ibfk_2` FOREIGN KEY (`pole_position_driver`) REFERENCES `drivers` (`id`),
  ADD CONSTRAINT `gp_stats_ibfk_3` FOREIGN KEY (`fastest_lap_driver`) REFERENCES `drivers` (`id`);

--
-- Contraintes pour la table `manual_adjustments`
--
ALTER TABLE `manual_adjustments`
  ADD CONSTRAINT `manual_adjustments_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `manual_adjustments_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`),
  ADD CONSTRAINT `manual_adjustments_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`);

--
-- Contraintes pour la table `penalties`
--
ALTER TABLE `penalties`
  ADD CONSTRAINT `penalties_ibfk_1` FOREIGN KEY (`gp_id`) REFERENCES `gp` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penalties_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`),
  ADD CONSTRAINT `penalties_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`);

--
-- Contraintes pour la table `seasons`
--
ALTER TABLE `seasons`
  ADD CONSTRAINT `seasons_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

--
-- Contraintes pour la table `teams_drivers`
--
ALTER TABLE `teams_drivers`
  ADD CONSTRAINT `teams_drivers_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teams_drivers_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`),
  ADD CONSTRAINT `teams_drivers_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`);

--
-- Contraintes pour la table `updates_log`
--
ALTER TABLE `updates_log`
  ADD CONSTRAINT `fk_updates_gp` FOREIGN KEY (`gp_id`) REFERENCES `gp` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_updates_season` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_updates_user` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
