-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 15 déc. 2025 à 11:51
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `color`, `status`) VALUES
(15, 'F1', '#E10600', 'active'),
(16, 'F2', '#366092', 'active'),
(17, 'F3', '#a22a98', 'active');

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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(24, 'Gafit', 1, 'active'),
(26, 'Pilote F2', 1, 'active'),
(27, 'Pilote F2 bis', 1, 'active');

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
-- Doublure de structure pour la vue `drivers_standings`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `drivers_standings`;
CREATE TABLE IF NOT EXISTS `drivers_standings` (
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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(43, 20, 41, 18),
(44, 22, 35, 1),
(45, 23, 33, 1),
(46, 22, 29, 2);

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
(163, 43, 22, 37, 9, 0.0, NULL),
(164, 44, 26, 31, 3, 0.0, NULL),
(165, 44, 27, 1, 2, 0.0, NULL),
(167, 45, 1, 37, 1, 900.0, NULL),
(168, 44, 21, 34, 1, 25.0, NULL),
(169, 44, 22, 1, 11, 0.0, 'DNF'),
(170, 44, 1, 1, 12, 0.0, 'DNF'),
(171, 46, 21, 37, 1, 25.0, NULL);

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

--
-- Déchargement des données de la table `gp_stats`
--

INSERT INTO `gp_stats` (`gp_id`, `pole_position_driver`, `pole_position_time`, `fastest_lap_driver`, `fastest_lap_time`) VALUES
(31, 14, '1:10.556', 22, '1:14.668'),
(44, 27, '1:14.145', 21, '1:25.652');

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
(55, 20, 24, NULL, 4.0, NULL),
(56, 22, 26, NULL, 25.0, NULL),
(57, 22, 27, NULL, 18.0, NULL);

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

--
-- Déchargement des données de la table `penalties`
--

INSERT INTO `penalties` (`id`, `gp_id`, `driver_id`, `team_id`, `points_removed`, `comment`) VALUES
(15, 45, 1, 37, 4, NULL),
(17, 44, 21, 34, 35, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `seasons`
--

INSERT INTO `seasons` (`id`, `season_number`, `category_id`, `videogame`, `platform`, `status`) VALUES
(20, 1, 15, 'F1 Championship Edition', 'PS3', 'active'),
(22, 1, 16, 'F1 Championship Edition', 'PS3', 'active'),
(23, 1, 17, 'F1 Championship Edition', 'PS3', 'desactive');

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
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `teams`
--

INSERT INTO `teams` (`id`, `name`, `logo`, `color`, `country_id`, `status`) VALUES
(1, '[Team removed]', '', '', 1, 'active'),
(29, 'Toyota', '', '', 32, 'active'),
(30, 'Williams', 'img/teams/williams.png', '#03A8EA', 26, 'active'),
(31, 'Ferrari', 'img/teams/ferrari.png', '#FE0000', 22, 'active'),
(32, 'Renault', '', '', 1, 'active'),
(33, 'Super Aguri', '', '', 32, 'active'),
(34, 'Honda', '', '', 32, 'active'),
(35, 'BMW Sauber', '', '', 34, 'active'),
(36, 'McLaren', 'img/teams/mclaren.png', '#FF8500', 26, 'active'),
(37, 'Red Bull', 'img/teams/redbull.png', '#15185E', 35, 'active'),
(38, 'Toro Rosso', '', '', 22, 'active'),
(39, 'Spyker', '', '', 36, 'active');

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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(28, 20, 24, 39),
(37, 22, 21, 37),
(36, 23, 1, 37);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `teams_standings`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `teams_standings`;
CREATE TABLE IF NOT EXISTS `teams_standings` (
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
) ENGINE=InnoDB AUTO_INCREMENT=370 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `updates_log`
--

INSERT INTO `updates_log` (`id`, `season_id`, `gp_id`, `table_name`, `updated_at`, `updated_by`, `action`) VALUES
(135, NULL, NULL, 'manual_adjustments', '2025-12-09 14:07:17', 1, 'create'),
(136, NULL, NULL, 'manual_adjustments', '2025-12-09 14:07:35', 1, 'update'),
(137, NULL, NULL, 'manual_adjustments', '2025-12-09 14:07:41', 1, 'update'),
(138, NULL, NULL, 'manual_adjustments', '2025-12-09 14:10:00', 1, 'delete'),
(139, NULL, NULL, 'manual_adjustments', '2025-12-09 14:10:12', 1, 'create'),
(140, NULL, NULL, 'manual_adjustments', '2025-12-09 14:10:34', 1, 'delete'),
(141, NULL, NULL, 'manual_adjustments', '2025-12-09 14:10:37', 1, 'delete'),
(142, NULL, 26, 'gp_points', '2025-12-09 15:11:20', 1, 'create'),
(143, NULL, 26, 'gp_points', '2025-12-09 15:11:34', 1, 'create'),
(144, NULL, 26, 'gp_points', '2025-12-09 15:11:49', 1, 'create'),
(145, NULL, 26, 'gp_points', '2025-12-09 15:13:47', 1, 'create'),
(146, NULL, 26, 'gp_points', '2025-12-09 15:17:02', 1, 'create'),
(147, NULL, 26, 'gp_points', '2025-12-09 15:32:21', 1, 'delete'),
(148, NULL, 26, 'gp_points', '2025-12-09 15:36:23', 1, 'create'),
(149, NULL, 26, 'gp_points', '2025-12-09 15:39:31', 1, 'create'),
(150, NULL, 26, 'gp_points', '2025-12-09 15:58:54', 1, 'create'),
(151, NULL, 26, 'gp_points', '2025-12-09 15:59:05', 1, 'delete'),
(152, NULL, 27, 'gp_points', '2025-12-10 09:25:36', 1, 'create'),
(153, NULL, 27, 'gp_points', '2025-12-10 09:25:53', 1, 'create'),
(154, NULL, 27, 'gp_points', '2025-12-10 09:26:34', 1, 'create'),
(155, NULL, 27, 'gp_points', '2025-12-10 09:26:47', 1, 'create'),
(156, NULL, 28, 'gp_points', '2025-12-10 09:27:39', 1, 'create'),
(157, NULL, 28, 'gp_points', '2025-12-10 09:28:05', 1, 'create'),
(158, NULL, 28, 'gp_points', '2025-12-10 09:28:24', 1, 'create'),
(159, NULL, 28, 'gp_points', '2025-12-10 09:29:08', 1, 'create'),
(160, NULL, 28, 'gp_points', '2025-12-10 09:29:17', 1, 'create'),
(161, NULL, 28, 'gp_points', '2025-12-10 09:29:23', 1, 'create'),
(162, NULL, 29, 'gp_points', '2025-12-10 09:31:32', 1, 'create'),
(163, NULL, 29, 'gp_points', '2025-12-10 09:31:53', 1, 'create'),
(164, NULL, 29, 'gp_points', '2025-12-10 09:32:00', 1, 'create'),
(165, NULL, 29, 'gp_points', '2025-12-10 09:32:57', 1, 'create'),
(166, NULL, 30, 'gp_points', '2025-12-10 09:33:59', 1, 'create'),
(167, NULL, 30, 'gp_points', '2025-12-10 09:35:23', 1, 'create'),
(168, NULL, 30, 'gp_points', '2025-12-10 09:35:31', 1, 'create'),
(169, NULL, 30, 'gp_points', '2025-12-10 09:36:06', 1, 'create'),
(170, NULL, 30, 'gp_points', '2025-12-10 09:36:19', 1, 'create'),
(171, NULL, 30, 'gp_points', '2025-12-10 09:36:36', 1, 'create'),
(172, NULL, 31, 'gp_points', '2025-12-10 09:37:40', 1, 'create'),
(173, NULL, 30, 'gp_points', '2025-12-10 09:37:46', 1, 'update'),
(174, NULL, 31, 'gp_points', '2025-12-10 09:42:15', 1, 'create'),
(175, NULL, 31, 'gp_points', '2025-12-10 09:42:31', 1, 'create'),
(176, NULL, 31, 'gp_points', '2025-12-10 09:42:45', 1, 'create'),
(177, NULL, 31, 'gp_points', '2025-12-10 09:42:56', 1, 'create'),
(178, NULL, 31, 'gp_points', '2025-12-10 09:43:12', 1, 'create'),
(179, NULL, 32, 'gp_points', '2025-12-10 09:44:34', 1, 'create'),
(180, NULL, 32, 'gp_points', '2025-12-10 09:44:44', 1, 'create'),
(181, NULL, 32, 'gp_points', '2025-12-10 09:44:49', 1, 'create'),
(182, NULL, 32, 'gp_points', '2025-12-10 09:45:07', 1, 'create'),
(183, NULL, 32, 'gp_points', '2025-12-10 09:45:27', 1, 'create'),
(184, NULL, 32, 'gp_points', '2025-12-10 09:45:35', 1, 'create'),
(185, NULL, 33, 'gp_points', '2025-12-10 09:46:38', 1, 'create'),
(186, NULL, 33, 'gp_points', '2025-12-10 09:46:49', 1, 'create'),
(187, NULL, 33, 'gp_points', '2025-12-10 09:47:01', 1, 'create'),
(188, NULL, 34, 'gp_points', '2025-12-10 09:48:28', 1, 'create'),
(189, NULL, 34, 'gp_points', '2025-12-10 09:48:39', 1, 'create'),
(190, NULL, 34, 'gp_points', '2025-12-10 09:49:24', 1, 'create'),
(191, NULL, 34, 'gp_points', '2025-12-10 09:49:41', 1, 'create'),
(192, NULL, 34, 'gp_points', '2025-12-10 09:49:53', 1, 'create'),
(193, NULL, 35, 'gp_points', '2025-12-10 09:50:57', 1, 'create'),
(194, NULL, 35, 'gp_points', '2025-12-10 09:51:03', 1, 'create'),
(195, NULL, 35, 'gp_points', '2025-12-10 09:51:15', 1, 'create'),
(196, NULL, 35, 'gp_points', '2025-12-10 09:51:28', 1, 'create'),
(197, NULL, 35, 'gp_points', '2025-12-10 09:51:43', 1, 'create'),
(198, NULL, 35, 'gp_points', '2025-12-10 09:51:55', 1, 'create'),
(199, NULL, 35, 'gp_points', '2025-12-10 09:52:09', 1, 'create'),
(200, NULL, 36, 'gp_points', '2025-12-10 09:52:55', 1, 'create'),
(201, NULL, 36, 'gp_points', '2025-12-10 09:53:10', 1, 'create'),
(202, NULL, 36, 'gp_points', '2025-12-10 09:53:19', 1, 'create'),
(203, NULL, 36, 'gp_points', '2025-12-10 09:53:35', 1, 'create'),
(204, NULL, 36, 'gp_points', '2025-12-10 09:53:50', 1, 'create'),
(205, NULL, 36, 'gp_points', '2025-12-10 09:54:25', 1, 'create'),
(206, NULL, 36, 'gp_points', '2025-12-10 09:54:37', 1, 'create'),
(207, NULL, 36, 'gp_points', '2025-12-10 09:54:59', 1, 'create'),
(208, NULL, 36, 'gp_points', '2025-12-10 09:55:12', 1, 'create'),
(209, NULL, 36, 'gp_points', '2025-12-10 09:55:46', 1, 'create'),
(210, NULL, 37, 'gp_points', '2025-12-10 09:56:57', 1, 'create'),
(211, NULL, 37, 'gp_points', '2025-12-10 09:57:15', 1, 'create'),
(212, NULL, 37, 'gp_points', '2025-12-10 09:57:26', 1, 'create'),
(213, NULL, 37, 'gp_points', '2025-12-10 09:57:49', 1, 'create'),
(214, NULL, 37, 'gp_points', '2025-12-10 09:58:16', 1, 'create'),
(215, NULL, 37, 'gp_points', '2025-12-10 09:58:30', 1, 'create'),
(216, NULL, 38, 'gp_points', '2025-12-10 09:59:25', 1, 'create'),
(217, NULL, 38, 'gp_points', '2025-12-10 09:59:35', 1, 'create'),
(218, NULL, 38, 'gp_points', '2025-12-10 09:59:56', 1, 'create'),
(219, NULL, 38, 'gp_points', '2025-12-10 10:00:15', 1, 'create'),
(220, NULL, 38, 'gp_points', '2025-12-10 10:00:28', 1, 'create'),
(221, NULL, 38, 'gp_points', '2025-12-10 10:00:40', 1, 'create'),
(222, NULL, 38, 'gp_points', '2025-12-10 10:01:36', 1, 'create'),
(223, NULL, 38, 'gp_points', '2025-12-10 10:01:56', 1, 'create'),
(224, NULL, 38, 'gp_points', '2025-12-10 10:02:11', 1, 'create'),
(225, NULL, 39, 'gp_points', '2025-12-10 10:03:42', 1, 'create'),
(226, NULL, 39, 'gp_points', '2025-12-10 10:03:53', 1, 'create'),
(227, NULL, 39, 'gp_points', '2025-12-10 10:04:06', 1, 'create'),
(228, NULL, 39, 'gp_points', '2025-12-10 10:04:17', 1, 'create'),
(229, NULL, 39, 'gp_points', '2025-12-10 10:07:37', 1, 'create'),
(230, NULL, 39, 'gp_points', '2025-12-10 10:08:02', 1, 'create'),
(231, NULL, 39, 'gp_points', '2025-12-10 10:08:17', 1, 'create'),
(232, NULL, 39, 'gp_points', '2025-12-10 10:08:36', 1, 'create'),
(233, NULL, 40, 'gp_points', '2025-12-10 10:10:43', 1, 'create'),
(234, NULL, 40, 'gp_points', '2025-12-10 10:10:52', 1, 'create'),
(235, NULL, 40, 'gp_points', '2025-12-10 10:11:05', 1, 'create'),
(236, NULL, 40, 'gp_points', '2025-12-10 10:11:17', 1, 'create'),
(237, NULL, 40, 'gp_points', '2025-12-10 10:11:34', 1, 'create'),
(238, NULL, 40, 'gp_points', '2025-12-10 10:12:08', 1, 'create'),
(239, NULL, 40, 'gp_points', '2025-12-10 10:12:21', 1, 'create'),
(240, NULL, 40, 'gp_points', '2025-12-10 10:12:34', 1, 'create'),
(241, NULL, 40, 'gp_points', '2025-12-10 10:12:49', 1, 'create'),
(242, NULL, 41, 'gp_points', '2025-12-10 10:15:11', 1, 'create'),
(243, NULL, 41, 'gp_points', '2025-12-10 10:15:25', 1, 'create'),
(244, NULL, 41, 'gp_points', '2025-12-10 10:15:36', 1, 'create'),
(245, NULL, 41, 'gp_points', '2025-12-10 10:15:48', 1, 'create'),
(246, NULL, 41, 'gp_points', '2025-12-10 10:16:18', 1, 'create'),
(247, NULL, 42, 'gp_points', '2025-12-10 10:16:58', 1, 'create'),
(248, NULL, 42, 'gp_points', '2025-12-10 10:17:09', 1, 'create'),
(249, NULL, 42, 'gp_points', '2025-12-10 10:17:12', 1, 'create'),
(250, NULL, 42, 'gp_points', '2025-12-10 10:17:24', 1, 'create'),
(251, NULL, 42, 'gp_points', '2025-12-10 10:17:36', 1, 'create'),
(252, NULL, 42, 'gp_points', '2025-12-10 10:17:55', 1, 'create'),
(253, NULL, 42, 'gp_points', '2025-12-10 10:18:19', 1, 'create'),
(254, NULL, 42, 'gp_points', '2025-12-10 10:18:36', 1, 'create'),
(255, NULL, 42, 'gp_points', '2025-12-10 10:19:25', 1, 'create'),
(256, NULL, 43, 'gp_points', '2025-12-10 10:20:10', 1, 'create'),
(257, NULL, 43, 'gp_points', '2025-12-10 10:20:22', 1, 'create'),
(258, NULL, 43, 'gp_points', '2025-12-10 10:20:52', 1, 'create'),
(259, NULL, 43, 'gp_points', '2025-12-10 10:21:03', 1, 'create'),
(260, NULL, 43, 'gp_points', '2025-12-10 10:21:13', 1, 'create'),
(261, NULL, 43, 'gp_points', '2025-12-10 10:21:28', 1, 'create'),
(262, NULL, 43, 'gp_points', '2025-12-10 10:21:43', 1, 'create'),
(263, NULL, 43, 'gp_points', '2025-12-10 10:22:02', 1, 'create'),
(264, NULL, 43, 'gp_points', '2025-12-10 10:22:30', 1, 'create'),
(265, 20, NULL, 'manual_adjustments', '2025-12-10 10:23:24', 1, 'create'),
(266, 20, NULL, 'manual_adjustments', '2025-12-10 10:23:35', 1, 'create'),
(267, 20, NULL, 'manual_adjustments', '2025-12-10 10:23:48', 1, 'create'),
(268, 20, NULL, 'manual_adjustments', '2025-12-10 10:24:01', 1, 'create'),
(269, 20, NULL, 'manual_adjustments', '2025-12-10 10:24:14', 1, 'create'),
(270, 20, NULL, 'manual_adjustments', '2025-12-10 10:24:25', 1, 'create'),
(271, 20, NULL, 'manual_adjustments', '2025-12-10 10:24:39', 1, 'create'),
(272, 20, NULL, 'manual_adjustments', '2025-12-10 10:24:53', 1, 'create'),
(273, 20, NULL, 'manual_adjustments', '2025-12-10 10:25:07', 1, 'update'),
(274, 20, NULL, 'manual_adjustments', '2025-12-10 10:25:16', 1, 'create'),
(275, 20, NULL, 'manual_adjustments', '2025-12-10 10:25:24', 1, 'create'),
(276, 20, NULL, 'manual_adjustments', '2025-12-10 10:25:31', 1, 'create'),
(277, NULL, 44, 'gp_points', '2025-12-10 13:50:57', 1, 'create'),
(278, NULL, 44, 'gp_points', '2025-12-10 13:51:05', 1, 'create'),
(279, NULL, 44, 'gp_points', '2025-12-10 13:51:11', 1, 'create'),
(280, 22, NULL, 'manual_adjustments', '2025-12-10 13:51:29', 1, 'create'),
(281, 22, NULL, 'manual_adjustments', '2025-12-10 13:51:37', 1, 'create'),
(282, NULL, 44, 'penalties', '2025-12-10 16:06:44', 1, 'create'),
(283, NULL, 45, 'gp_points', '2025-12-11 11:18:23', 1, 'create'),
(284, NULL, 45, 'gp_points', '2025-12-11 11:20:52', 1, 'update'),
(285, NULL, 45, 'gp_points', '2025-12-11 11:21:08', 1, 'update'),
(286, NULL, 31, 'gp_stats', '2025-12-11 15:30:52', 1, 'create'),
(287, NULL, 44, 'gp_stats', '2025-12-11 15:49:23', 1, 'create'),
(288, NULL, 44, 'gp_stats', '2025-12-11 15:49:41', 1, 'update'),
(289, NULL, 44, 'gp_points', '2025-12-11 15:53:11', 1, 'create'),
(290, NULL, 44, 'gp_points', '2025-12-11 15:53:54', 1, 'create'),
(291, NULL, 44, 'gp_points', '2025-12-11 15:54:17', 1, 'update'),
(292, NULL, 44, 'gp_points', '2025-12-11 15:54:25', 1, 'update'),
(293, NULL, 44, 'gp_points', '2025-12-11 15:54:40', 1, 'create'),
(294, NULL, 44, 'gp_stats', '2025-12-11 15:55:35', 1, 'update'),
(295, 22, NULL, 'manual_adjustments', '2025-12-11 15:59:33', 1, 'create'),
(296, 23, NULL, 'manual_adjustments', '2025-12-11 15:59:55', 1, 'create'),
(297, NULL, 45, 'penalties', '2025-12-11 16:00:21', 1, 'create'),
(298, 20, NULL, 'manual_adjustments', '2025-12-11 16:14:10', 1, 'create'),
(299, 20, NULL, 'manual_adjustments', '2025-12-11 16:31:30', 1, 'delete'),
(300, 20, NULL, 'manual_adjustments', '2025-12-11 16:31:59', 1, 'create'),
(301, 20, NULL, 'manual_adjustments', '2025-12-11 16:32:07', 1, 'delete'),
(302, 20, NULL, 'manual_adjustments', '2025-12-11 16:32:17', 1, 'create'),
(303, 22, NULL, 'manual_adjustments', '2025-12-11 16:33:18', 1, 'create'),
(304, NULL, 44, 'gp_points', '2025-12-11 16:34:24', 1, 'update'),
(305, NULL, 43, 'penalties', '2025-12-11 18:39:09', 1, 'create'),
(306, NULL, 43, 'penalties', '2025-12-11 18:40:07', 1, 'delete'),
(307, NULL, 26, 'penalties', '2025-12-11 18:40:54', 1, 'create'),
(308, NULL, 44, 'penalties', '2025-12-11 18:41:38', 1, 'delete'),
(309, NULL, 45, 'gp_points', '2025-12-11 18:44:03', 1, 'update'),
(310, 20, NULL, 'manual_adjustments', '2025-12-11 18:44:58', 1, 'delete'),
(311, NULL, 26, 'penalties', '2025-12-11 18:45:42', 1, 'update'),
(312, 22, NULL, 'manual_adjustments', '2025-12-11 18:47:12', 1, 'delete'),
(313, 22, NULL, 'manual_adjustments', '2025-12-11 18:47:15', 1, 'delete'),
(314, 23, NULL, 'manual_adjustments', '2025-12-11 18:47:18', 1, 'delete'),
(315, NULL, 26, 'penalties', '2025-12-11 18:48:05', 1, 'delete'),
(316, NULL, 45, 'penalties', '2025-12-11 18:48:07', 1, 'delete'),
(317, 20, NULL, 'manual_adjustments', '2025-12-11 18:48:38', 1, 'create'),
(318, 20, NULL, 'manual_adjustments', '2025-12-11 18:48:51', 1, 'update'),
(319, 20, NULL, 'manual_adjustments', '2025-12-11 18:49:10', 1, 'create'),
(320, 20, NULL, 'manual_adjustments', '2025-12-11 18:49:23', 1, 'delete'),
(321, 20, NULL, 'manual_adjustments', '2025-12-11 18:50:01', 1, 'create'),
(322, NULL, 43, 'gp_points', '2025-12-11 18:50:40', 1, 'update'),
(323, NULL, 43, 'penalties', '2025-12-11 18:51:20', 1, 'create'),
(324, NULL, 43, 'penalties', '2025-12-11 18:51:50', 1, 'update'),
(325, NULL, 44, 'gp_points', '2025-12-11 19:07:18', 1, 'update'),
(326, NULL, 43, 'penalties', '2025-12-11 19:10:45', 1, 'delete'),
(327, 20, NULL, 'manual_adjustments', '2025-12-11 19:12:08', 1, 'update'),
(328, 20, NULL, 'manual_adjustments', '2025-12-11 19:12:24', 1, 'delete'),
(329, 20, NULL, 'manual_adjustments', '2025-12-11 19:12:42', 1, 'create'),
(330, 20, NULL, 'manual_adjustments', '2025-12-11 19:13:36', 1, 'create'),
(331, NULL, 26, 'penalties', '2025-12-11 19:20:22', 1, 'create'),
(332, NULL, 26, 'penalties', '2025-12-11 19:20:49', 1, 'create'),
(333, 20, NULL, 'manual_adjustments', '2025-12-11 19:21:03', 1, 'delete'),
(334, NULL, 43, 'gp_points', '2025-12-11 19:21:29', 1, 'update'),
(335, 20, NULL, 'manual_adjustments', '2025-12-11 19:21:41', 1, 'delete'),
(336, 20, NULL, 'manual_adjustments', '2025-12-11 19:21:49', 1, 'delete'),
(337, NULL, 26, 'penalties', '2025-12-11 19:22:42', 1, 'create'),
(338, NULL, 44, 'penalties', '2025-12-11 19:31:55', 1, 'create'),
(339, NULL, 43, 'penalties', '2025-12-11 19:33:15', 1, 'update'),
(340, NULL, 26, 'penalties', '2025-12-11 19:33:37', 1, 'delete'),
(341, NULL, 26, 'penalties', '2025-12-11 19:35:28', 1, 'delete'),
(342, NULL, 43, 'penalties', '2025-12-11 19:39:28', 1, 'delete'),
(343, NULL, 44, 'penalties', '2025-12-11 19:39:58', 1, 'delete'),
(344, NULL, 26, 'penalties', '2025-12-12 15:07:13', 1, 'create'),
(345, NULL, 43, 'penalties', '2025-12-12 15:08:25', 1, 'update'),
(346, NULL, 43, 'penalties', '2025-12-12 15:11:54', 1, 'delete'),
(347, NULL, 43, 'penalties', '2025-12-12 15:12:34', 1, 'create'),
(348, NULL, 42, 'penalties', '2025-12-12 15:12:44', 1, 'update'),
(349, NULL, 41, 'penalties', '2025-12-12 15:12:57', 1, 'update'),
(350, NULL, 41, 'penalties', '2025-12-12 15:26:50', 1, 'update'),
(351, NULL, 41, 'penalties', '2025-12-12 15:28:21', 1, 'delete'),
(352, NULL, 45, 'penalties', '2025-12-12 15:29:01', 1, 'create'),
(353, NULL, 45, 'penalties', '2025-12-12 15:36:48', 1, 'update'),
(354, NULL, 26, 'penalties', '2025-12-12 15:52:49', 1, 'create'),
(355, NULL, 45, 'penalties', '2025-12-12 16:07:33', 1, 'update'),
(356, NULL, 45, 'gp_points', '2025-12-12 16:08:33', 1, 'update'),
(357, NULL, 26, 'penalties', '2025-12-12 16:11:16', 1, 'delete'),
(358, NULL, 44, 'gp_points', '2025-12-12 16:12:43', 1, 'update'),
(359, NULL, 44, 'penalties', '2025-12-12 16:13:40', 1, 'create'),
(360, NULL, 44, 'penalties', '2025-12-12 16:13:53', 1, 'update'),
(361, NULL, 44, 'penalties', '2025-12-12 16:14:20', 1, 'update'),
(362, NULL, 44, 'penalties', '2025-12-12 16:17:24', 1, 'update'),
(363, NULL, 46, 'gp_points', '2025-12-12 16:18:08', 1, 'create'),
(364, NULL, 44, 'gp_points', '2025-12-12 16:18:57', 1, 'delete'),
(365, NULL, 44, 'gp_points', '2025-12-12 16:19:03', 1, 'update'),
(366, NULL, 44, 'gp_points', '2025-12-12 16:19:12', 1, 'update'),
(367, NULL, 44, 'gp_points', '2025-12-12 16:20:18', 1, 'update'),
(368, NULL, 44, 'penalties', '2025-12-12 16:31:32', 1, 'update'),
(369, NULL, 44, 'gp_points', '2025-12-12 17:07:31', 1, 'update');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `role_id`) VALUES
(1, 'paraguayan99@laposte.net', '$2y$10$jI3l3bZc92tGCe.OqMbVWuu/diVN7AlMHhxkEWUKR8BxgY3ardwYC', 1),
(2, 'moderateur@gmail.com', '$2y$10$7YqgqnOXLMpR7GxIkDSb/OWnUGtFIa8Ip6yXgOPyNW0GZ5dkmLQt6', 2),
(3, 'utilisateur@gmail.com', '$2y$10$S6lHWQKDdWupIJfwkRgnzOYeNhRGsTOqv8jlInjBV7SYwjNIcDjT2', 3);

-- --------------------------------------------------------

--
-- Structure de la vue `drivers_standings`
--
DROP TABLE IF EXISTS `drivers_standings`;

DROP VIEW IF EXISTS `drivers_standings`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `drivers_standings`  AS SELECT `s`.`id` AS `season_id`, `s`.`season_number` AS `season_number`, `s`.`status` AS `season_status`, `c`.`name` AS `category`, `d`.`id` AS `driver_id`, `d`.`nickname` AS `nickname`, `t`.`name` AS `team_name`, ((coalesce(sum(`gp_pts`.`points_numeric`),0) + coalesce(`ma`.`total_points`,0)) - coalesce(sum(`p`.`points_removed`),0)) AS `total_points`, sum((case when (`gp_pts`.`position` = 1) then 1 else 0 end)) AS `wins`, sum((case when (`gp_pts`.`position` in (1,2,3)) then 1 else 0 end)) AS `podiums` FROM ((((((((`seasons` `s` join `categories` `c` on((`c`.`id` = `s`.`category_id`))) join `gp` `g` on((`g`.`season_id` = `s`.`id`))) join `gp_points` `gp_pts` on((`gp_pts`.`gp_id` = `g`.`id`))) join `drivers` `d` on((`d`.`id` = `gp_pts`.`driver_id`))) left join `teams_drivers` `td` on(((`td`.`driver_id` = `d`.`id`) and (`td`.`season_id` = `s`.`id`)))) left join `teams` `t` on((`t`.`id` = `td`.`team_id`))) left join (select `manual_adjustments`.`season_id` AS `season_id`,`manual_adjustments`.`driver_id` AS `driver_id`,sum(`manual_adjustments`.`points`) AS `total_points` from `manual_adjustments` group by `manual_adjustments`.`season_id`,`manual_adjustments`.`driver_id`) `ma` on(((`ma`.`season_id` = `s`.`id`) and (`ma`.`driver_id` = `d`.`id`)))) left join `penalties` `p` on(((`p`.`driver_id` = `d`.`id`) and (`p`.`gp_id` = `g`.`id`)))) GROUP BY `s`.`id`, `s`.`season_number`, `s`.`status`, `c`.`name`, `d`.`id`, `d`.`nickname`, `t`.`name` ;

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


--------------------------------------------------------------------------------
--------------- AJOUTS OU MODIFICATIONS DE CETTE BASE DE DONNEE ----------------
--------------------------------------------------------------------------------

--------------------------------------------------------------------------------
--------------- DRIVERS PALMARES
--------------------------------------------------------------------------------
DROP VIEW IF EXISTS drivers_palmares;
CREATE VIEW drivers_palmares AS
SELECT
    ds.category,
    d.id AS driver_id,
    d.nickname,

    -- Titres (saisons désactivées uniquement)
    SUM(CASE WHEN ds.season_status = 'desactive' AND ds.total_points = (
        SELECT MAX(ds2.total_points)
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
    ) THEN 1 ELSE 0 END) AS titles,

    SUM(CASE WHEN ds.season_status = 'desactive' AND ds.total_points = (
        SELECT MAX(ds2.total_points)
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
          AND ds2.total_points < (
              SELECT MAX(ds3.total_points)
              FROM drivers_standings ds3
              WHERE ds3.season_id = ds.season_id
          )
    ) THEN 1 ELSE 0 END) AS vice_titles,

    SUM(CASE WHEN ds.season_status = 'desactive' AND ds.total_points = (
        SELECT DISTINCT ds2.total_points
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
        ORDER BY ds2.total_points DESC
        LIMIT 1 OFFSET 2
    ) THEN 1 ELSE 0 END) AS third_places,

    -- Stats globales (toutes saisons)
    SUM(ds.total_points) AS total_points,
    SUM(ds.wins) AS wins,
    SUM(ds.podiums) AS podiums,
    COUNT(DISTINCT ds.season_id) AS seasons_count

FROM drivers_standings ds
JOIN drivers d ON d.id = ds.driver_id
GROUP BY ds.category, d.id, d.nickname;

--------------------------------------------------------------------------------
--------------- TEAMS PALMARES
--------------------------------------------------------------------------------

DROP VIEW IF EXISTS teams_palmares;
CREATE VIEW teams_palmares AS
SELECT
    ts.category,
    t.id AS team_id,
    t.name AS team_name,

    -- Titres constructeurs (saisons désactivées)
    SUM(CASE WHEN s.status = 'desactive' AND ts.total_points = (
        SELECT MAX(ts2.total_points)
        FROM teams_standings ts2
        WHERE ts2.season_id = ts.season_id
    ) THEN 1 ELSE 0 END) AS titles,

    -- Points toutes saisons
    SUM(ts.total_points) AS total_points

FROM teams_standings ts
JOIN teams t ON t.id = ts.team_id
JOIN seasons s ON s.id = ts.season_id
GROUP BY ts.category, t.id, t.name;


--------------------------------------------------------------------------------
--------------- DRIVERS PALMARES : V2 QUI COMPTABILISE LE NOMBRE DE GP (même si position ou points_numeric = null ou 0)
--------------------------------------------------------------------------------

DROP VIEW IF EXISTS drivers_palmares;
CREATE VIEW drivers_palmares AS
SELECT
    ds.category,
    d.id AS driver_id,
    d.nickname,

    -- Titres (saisons désactivées uniquement)
    SUM(CASE WHEN ds.season_status = 'desactive' AND ds.total_points = (
        SELECT MAX(ds2.total_points)
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
    ) THEN 1 ELSE 0 END) AS titles,

    SUM(CASE WHEN ds.season_status = 'desactive' AND ds.total_points = (
        SELECT MAX(ds2.total_points)
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
          AND ds2.total_points < (
              SELECT MAX(ds3.total_points)
              FROM drivers_standings ds3
              WHERE ds3.season_id = ds.season_id
          )
    ) THEN 1 ELSE 0 END) AS vice_titles,

    SUM(CASE WHEN ds.season_status = 'desactive' AND ds.total_points = (
        SELECT DISTINCT ds2.total_points
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
        ORDER BY ds2.total_points DESC
        LIMIT 1 OFFSET 2
    ) THEN 1 ELSE 0 END) AS third_places,

    -- Stats globales
    SUM(ds.total_points) AS total_points,
    SUM(ds.wins) AS wins,
    SUM(ds.podiums) AS podiums,

    -- TOTAL GP = présence du pilote dans gp_points
    COUNT(DISTINCT gp_pts.gp_id) AS total_gp

FROM drivers_standings ds
JOIN drivers d ON d.id = ds.driver_id

-- source de vérité pour la participation GP
JOIN gp_points gp_pts 
    ON gp_pts.driver_id = d.id

JOIN gp g 
    ON g.id = gp_pts.gp_id
   AND g.season_id = ds.season_id

GROUP BY ds.category, d.id, d.nickname;

--------------------------------------------------------------------------------
--------------- DRIVERS PALMARES : V3 QUI NE MULTIPLIE PLUS LES PTS / VICTOIRES / PODIUMS PAR LE NOMBRE DE GP
--------------------------------------------------------------------------------

DROP VIEW IF EXISTS drivers_palmares;
CREATE VIEW drivers_palmares AS
SELECT
    ds.category,
    d.id AS driver_id,
    d.nickname,

    -- Titres (saisons désactivées uniquement)
    SUM(CASE WHEN ds.season_status = 'desactive' AND ds.total_points = (
        SELECT MAX(ds2.total_points)
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
    ) THEN 1 ELSE 0 END) AS titles,

    SUM(CASE WHEN ds.season_status = 'desactive' AND ds.total_points = (
        SELECT MAX(ds2.total_points)
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
          AND ds2.total_points < (
              SELECT MAX(ds3.total_points)
              FROM drivers_standings ds3
              WHERE ds3.season_id = ds.season_id
          )
    ) THEN 1 ELSE 0 END) AS vice_titles,

    SUM(CASE WHEN ds.season_status = 'desactive' AND ds.total_points = (
        SELECT DISTINCT ds2.total_points
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
        ORDER BY ds2.total_points DESC
        LIMIT 1 OFFSET 2
    ) THEN 1 ELSE 0 END) AS third_places,

    -- Stats globales (déjà agrégées → PAS TOUCHER)
    SUM(ds.total_points) AS total_points,
    SUM(ds.wins) AS wins,
    SUM(ds.podiums) AS podiums,

    -- TOTAL GP calculé séparément (DNF / DNS inclus)
    (
        SELECT COUNT(DISTINCT gp_pts.gp_id)
        FROM gp_points gp_pts
        JOIN gp g ON g.id = gp_pts.gp_id
        WHERE gp_pts.driver_id = d.id
    ) AS total_gp

FROM drivers_standings ds
JOIN drivers d ON d.id = ds.driver_id

GROUP BY ds.category, d.id, d.nickname;


--------------------------------------------------------------------------------
--------------- DRIVERS PALMARES : V4 QUI NE TRIAIT PAS LES CATEGORIES
--------------------------------------------------------------------------------

DROP VIEW IF EXISTS drivers_palmares;
CREATE VIEW drivers_palmares AS
SELECT
    ds.category,
    d.id AS driver_id,
    d.nickname,

    -- TITRES (par saison + catégorie)
    SUM(CASE WHEN ds.season_status = 'desactive'
      AND ds.total_points = (
        SELECT MAX(ds2.total_points)
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
          AND ds2.category = ds.category
    ) THEN 1 ELSE 0 END) AS titles,

    -- VICE-CHAMPIONS
    SUM(CASE WHEN ds.season_status = 'desactive'
      AND ds.total_points = (
        SELECT MAX(ds2.total_points)
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
          AND ds2.category = ds.category
          AND ds2.total_points < (
            SELECT MAX(ds3.total_points)
            FROM drivers_standings ds3
            WHERE ds3.season_id = ds.season_id
              AND ds3.category = ds.category
          )
    ) THEN 1 ELSE 0 END) AS vice_titles,

    -- TROISIÈMES
    SUM(CASE WHEN ds.season_status = 'desactive'
      AND ds.total_points = (
        SELECT DISTINCT ds2.total_points
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
          AND ds2.category = ds.category
        ORDER BY ds2.total_points DESC
        LIMIT 1 OFFSET 2
    ) THEN 1 ELSE 0 END) AS third_places,

    -- Stats globales
    SUM(ds.total_points) AS total_points,
    SUM(ds.wins) AS wins,
    SUM(ds.podiums) AS podiums,

    -- TOTAL GP (DNF/DNS inclus)
    (
        SELECT COUNT(DISTINCT gp_pts.gp_id)
        FROM gp_points gp_pts
        JOIN gp g ON g.id = gp_pts.gp_id
        WHERE gp_pts.driver_id = d.id
    ) AS total_gp

FROM drivers_standings ds
JOIN drivers d ON d.id = ds.driver_id

GROUP BY ds.category, d.id, d.nickname;


--------------------------------------------------------------------------------
--------------- DRIVERS PALMARES : V5 QUI NE TRIAIT PAS LES CATEGORIES POUR GP ET POINTS
--------------------------------------------------------------------------------

DROP VIEW IF EXISTS drivers_palmares;
CREATE VIEW drivers_palmares AS
SELECT
    ds.category,
    d.id AS driver_id,
    d.nickname,

    -- Titres (par saison + catégorie)
    SUM(CASE WHEN ds.season_status = 'desactive'
      AND ds.total_points = (
        SELECT MAX(ds2.total_points)
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
          AND ds2.category = ds.category
    ) THEN 1 ELSE 0 END) AS titles,

    -- Vice-titres
    SUM(CASE WHEN ds.season_status = 'desactive'
      AND ds.total_points = (
        SELECT MAX(ds2.total_points)
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
          AND ds2.category = ds.category
          AND ds2.total_points < (
            SELECT MAX(ds3.total_points)
            FROM drivers_standings ds3
            WHERE ds3.season_id = ds.season_id
              AND ds3.category = ds.category
          )
    ) THEN 1 ELSE 0 END) AS vice_titles,

    -- Troisièmes places
    SUM(CASE WHEN ds.season_status = 'desactive'
      AND ds.total_points = (
        SELECT DISTINCT ds2.total_points
        FROM drivers_standings ds2
        WHERE ds2.season_id = ds.season_id
          AND ds2.category = ds.category
        ORDER BY ds2.total_points DESC
        LIMIT 1 OFFSET 2
    ) THEN 1 ELSE 0 END) AS third_places,

    -- Stats par catégorie (via standings)
    SUM(ds.total_points) AS total_points,
    SUM(ds.wins) AS wins,
    SUM(ds.podiums) AS podiums,

    -- TOTAL GP PAR CATÉGORIE
    (
        SELECT COUNT(DISTINCT gp_pts.gp_id)
        FROM gp_points gp_pts
        JOIN gp g ON g.id = gp_pts.gp_id
        JOIN drivers_standings dsx
            ON dsx.driver_id = gp_pts.driver_id
           AND dsx.season_id = g.season_id
           AND dsx.category = ds.category
        WHERE gp_pts.driver_id = d.id
    ) AS total_gp

FROM drivers_standings ds
JOIN drivers d ON d.id = ds.driver_id

GROUP BY ds.category, d.id, d.nickname;

