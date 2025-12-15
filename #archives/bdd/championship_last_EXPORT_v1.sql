-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 09 déc. 2025 à 15:25
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
  `status` enum('active','desactive') DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`) VALUES
(15, 'F1', 'active'),
(16, 'F2', 'active'),
(17, 'F3', 'active');

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
(1, 'France', 'FRA', ''),
(19, 'Bahreïn', 'BAH', ''),
(20, 'Malaisie', 'MAL', ''),
(21, 'Australie', 'AUS', ''),
(22, 'Italie', 'ITA', ''),
(23, 'Allemagne', 'ALL', ''),
(24, 'Espagne', 'ESP', ''),
(25, 'Monaco', 'MON', ''),
(26, 'Grande-Bretagne', 'GBR', ''),
(27, 'Canada', 'CAN', ''),
(28, 'États-Unis', 'USA', ''),
(29, 'Hongrie', 'HON', ''),
(30, 'Turquie', 'TUR', ''),
(31, 'Chine', 'CHI', ''),
(32, 'Japon', 'JAP', ''),
(33, 'Brésil', 'BRE', ''),
(34, 'Suisse', 'SUI', ''),
(35, 'Autriche', 'AUT', ''),
(36, 'Pays-Bas', 'P-B', '');

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
,`total_points` decimal(34,1)
,`wins` decimal(23,0)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `driver_awards`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `driver_awards`;
CREATE TABLE IF NOT EXISTS `driver_awards` (
`category` varchar(50)
,`driver_id` int
,`nickname` varchar(100)
,`third_place` decimal(23,0)
,`titles` decimal(23,0)
,`vice_titles` decimal(23,0)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `driver_gp_counts`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `driver_gp_counts`;
CREATE TABLE IF NOT EXISTS `driver_gp_counts` (
`driver_id` int
,`nickname` varchar(100)
,`total_gp` bigint
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `driver_points_all_seasons`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `driver_points_all_seasons`;
CREATE TABLE IF NOT EXISTS `driver_points_all_seasons` (
`driver_id` int
,`nickname` varchar(100)
,`total_points` decimal(34,1)
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
(45, 26, 1, 1, 2, 0.0, NULL);

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
-- Doublure de structure pour la vue `gp_stats_summary`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `gp_stats_summary`;
CREATE TABLE IF NOT EXISTS `gp_stats_summary` (
`category` varchar(50)
,`circuit_name` varchar(100)
,`fastest_driver_name` varchar(100)
,`fastest_lap_driver` int
,`fastest_lap_time` varchar(50)
,`gp_id` int
,`pole_driver_name` varchar(100)
,`pole_position_driver` int
,`pole_position_time` varchar(50)
,`season_id` int
,`season_number` int
);

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `seasons`
--

INSERT INTO `seasons` (`id`, `season_number`, `category_id`, `videogame`, `platform`, `status`) VALUES
(20, 1, 15, 'F1 Championship Edition', 'PS3', 'active'),
(22, 1, 16, 'F1 Championship Edition', 'PS3', 'active'),
(23, 1, 17, 'F1 Championship Edition', 'PS3', 'active');

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
(30, 'Williams', '', '', 26, 'active'),
(31, 'Ferrari', '', '', 22, 'active'),
(32, 'Renault', '', '', 1, 'active'),
(33, 'Super Aguri', '', '', 32, 'active'),
(34, 'Honda', '', '', 32, 'active'),
(35, 'BMW Sauber', '', '', 34, 'active'),
(36, 'McLaren', '', '', 26, 'active'),
(37, 'Red Bull', '', '', 35, 'active'),
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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Doublure de structure pour la vue `teams_standings`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `teams_standings`;
CREATE TABLE IF NOT EXISTS `teams_standings` (
`category` varchar(50)
,`podiums` decimal(23,0)
,`season_id` int
,`season_number` int
,`team_id` int
,`team_name` varchar(100)
,`total_points` decimal(34,1)
,`wins` decimal(23,0)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `team_awards`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `team_awards`;
CREATE TABLE IF NOT EXISTS `team_awards` (
`category` varchar(50)
,`team_id` int
,`team_name` varchar(100)
,`third_place` decimal(23,0)
,`titles` decimal(23,0)
,`vice_titles` decimal(23,0)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `team_points_all_seasons`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `team_points_all_seasons`;
CREATE TABLE IF NOT EXISTS `team_points_all_seasons` (
`team_id` int
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
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(151, NULL, 26, 'gp_points', '2025-12-09 15:59:05', 1, 'delete');

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
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `drivers_standings`  AS SELECT `s`.`id` AS `season_id`, `s`.`season_number` AS `season_number`, `c`.`name` AS `category`, `d`.`id` AS `driver_id`, `d`.`nickname` AS `nickname`, ((coalesce(sum(`gp_pts`.`points_numeric`),0) + coalesce(sum(`ma`.`points`),0)) - coalesce(sum(`p`.`points_removed`),0)) AS `total_points`, sum((case when (`gp_pts`.`position` = 1) then 1 else 0 end)) AS `wins`, sum((case when (`gp_pts`.`position` in (1,2,3)) then 1 else 0 end)) AS `podiums` FROM ((((((`seasons` `s` join `categories` `c` on((`s`.`category_id` = `c`.`id`))) join `gp` `g` on((`g`.`season_id` = `s`.`id`))) join `gp_points` `gp_pts` on((`gp_pts`.`gp_id` = `g`.`id`))) join `drivers` `d` on((`d`.`id` = `gp_pts`.`driver_id`))) left join `manual_adjustments` `ma` on(((`ma`.`season_id` = `s`.`id`) and (`ma`.`driver_id` = `d`.`id`)))) left join `penalties` `p` on(((`p`.`driver_id` = `d`.`id`) and (`p`.`gp_id` = `g`.`id`)))) GROUP BY `s`.`id`, `s`.`season_number`, `c`.`name`, `d`.`id`, `d`.`nickname` ;

-- --------------------------------------------------------

--
-- Structure de la vue `driver_awards`
--
DROP TABLE IF EXISTS `driver_awards`;

DROP VIEW IF EXISTS `driver_awards`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `driver_awards`  AS WITH     `season_ranking` as (select `ds`.`season_id` AS `season_id`,`ds`.`category` AS `category`,`ds`.`driver_id` AS `driver_id`,`ds`.`nickname` AS `nickname`,`ds`.`total_points` AS `total_points`,rank() OVER (PARTITION BY `ds`.`season_id` ORDER BY `ds`.`total_points` desc )  AS `rank_season` from `drivers_standings` `ds`) select `season_ranking`.`driver_id` AS `driver_id`,`season_ranking`.`nickname` AS `nickname`,`season_ranking`.`category` AS `category`,sum((case when (`season_ranking`.`rank_season` = 1) then 1 else 0 end)) AS `titles`,sum((case when (`season_ranking`.`rank_season` = 2) then 1 else 0 end)) AS `vice_titles`,sum((case when (`season_ranking`.`rank_season` = 3) then 1 else 0 end)) AS `third_place` from `season_ranking` group by `season_ranking`.`driver_id`,`season_ranking`.`nickname`,`season_ranking`.`category`  ;

-- --------------------------------------------------------

--
-- Structure de la vue `driver_gp_counts`
--
DROP TABLE IF EXISTS `driver_gp_counts`;

DROP VIEW IF EXISTS `driver_gp_counts`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `driver_gp_counts`  AS SELECT `d`.`id` AS `driver_id`, `d`.`nickname` AS `nickname`, count(distinct `gp_pts`.`gp_id`) AS `total_gp` FROM (`drivers` `d` left join `gp_points` `gp_pts` on((`gp_pts`.`driver_id` = `d`.`id`))) GROUP BY `d`.`id`, `d`.`nickname` ;

-- --------------------------------------------------------

--
-- Structure de la vue `driver_points_all_seasons`
--
DROP TABLE IF EXISTS `driver_points_all_seasons`;

DROP VIEW IF EXISTS `driver_points_all_seasons`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `driver_points_all_seasons`  AS SELECT `d`.`id` AS `driver_id`, `d`.`nickname` AS `nickname`, ((coalesce(sum(`gp_pts`.`points_numeric`),0) + coalesce(sum(`ma`.`points`),0)) - coalesce(sum(`p`.`points_removed`),0)) AS `total_points` FROM ((((`drivers` `d` left join `gp_points` `gp_pts` on((`gp_pts`.`driver_id` = `d`.`id`))) left join `gp` `g` on((`g`.`id` = `gp_pts`.`gp_id`))) left join `manual_adjustments` `ma` on(((`ma`.`driver_id` = `d`.`id`) and (`ma`.`season_id` = `g`.`season_id`)))) left join `penalties` `p` on(((`p`.`driver_id` = `d`.`id`) and (`p`.`gp_id` = `g`.`id`)))) GROUP BY `d`.`id`, `d`.`nickname` ;

-- --------------------------------------------------------

--
-- Structure de la vue `gp_stats_summary`
--
DROP TABLE IF EXISTS `gp_stats_summary`;

DROP VIEW IF EXISTS `gp_stats_summary`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `gp_stats_summary`  AS SELECT `g`.`id` AS `gp_id`, `g`.`season_id` AS `season_id`, `s`.`season_number` AS `season_number`, `c`.`name` AS `category`, `ci`.`name` AS `circuit_name`, `gs`.`pole_position_driver` AS `pole_position_driver`, `dp`.`nickname` AS `pole_driver_name`, `gs`.`pole_position_time` AS `pole_position_time`, `gs`.`fastest_lap_driver` AS `fastest_lap_driver`, `df`.`nickname` AS `fastest_driver_name`, `gs`.`fastest_lap_time` AS `fastest_lap_time` FROM ((((((`gp` `g` join `seasons` `s` on((`s`.`id` = `g`.`season_id`))) join `categories` `c` on((`c`.`id` = `s`.`category_id`))) join `circuits` `ci` on((`ci`.`id` = `g`.`circuit_id`))) left join `gp_stats` `gs` on((`gs`.`gp_id` = `g`.`id`))) left join `drivers` `dp` on((`dp`.`id` = `gs`.`pole_position_driver`))) left join `drivers` `df` on((`df`.`id` = `gs`.`fastest_lap_driver`))) ;

-- --------------------------------------------------------

--
-- Structure de la vue `teams_standings`
--
DROP TABLE IF EXISTS `teams_standings`;

DROP VIEW IF EXISTS `teams_standings`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `teams_standings`  AS SELECT `s`.`id` AS `season_id`, `s`.`season_number` AS `season_number`, `c`.`name` AS `category`, `t`.`id` AS `team_id`, `t`.`name` AS `team_name`, ((coalesce(sum(`gp_pts`.`points_numeric`),0) + coalesce(sum(`ma`.`points`),0)) - coalesce(sum(`p`.`points_removed`),0)) AS `total_points`, sum((case when (`gp_pts`.`position` = 1) then 1 else 0 end)) AS `wins`, sum((case when (`gp_pts`.`position` in (1,2,3)) then 1 else 0 end)) AS `podiums` FROM ((((((((`seasons` `s` join `categories` `c` on((`s`.`category_id` = `c`.`id`))) join `teams` `t`) join `teams_drivers` `td` on(((`td`.`team_id` = `t`.`id`) and (`td`.`season_id` = `s`.`id`)))) join `drivers` `d` on((`d`.`id` = `td`.`driver_id`))) join `gp` `g` on((`g`.`season_id` = `s`.`id`))) join `gp_points` `gp_pts` on(((`gp_pts`.`driver_id` = `d`.`id`) and (`gp_pts`.`gp_id` = `g`.`id`) and (`gp_pts`.`team_id` = `t`.`id`)))) left join `manual_adjustments` `ma` on(((`ma`.`season_id` = `s`.`id`) and (`ma`.`driver_id` = `d`.`id`) and (`ma`.`team_id` = `t`.`id`)))) left join `penalties` `p` on(((`p`.`driver_id` = `d`.`id`) and (`p`.`gp_id` = `g`.`id`)))) GROUP BY `s`.`id`, `s`.`season_number`, `c`.`name`, `t`.`id`, `t`.`name` ;

-- --------------------------------------------------------

--
-- Structure de la vue `team_awards`
--
DROP TABLE IF EXISTS `team_awards`;

DROP VIEW IF EXISTS `team_awards`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `team_awards`  AS WITH     `season_ranking` as (select `ts`.`season_id` AS `season_id`,`ts`.`category` AS `category`,`ts`.`team_id` AS `team_id`,`ts`.`team_name` AS `team_name`,`ts`.`total_points` AS `total_points`,rank() OVER (PARTITION BY `ts`.`season_id` ORDER BY `ts`.`total_points` desc )  AS `rank_season` from `teams_standings` `ts`) select `season_ranking`.`team_id` AS `team_id`,`season_ranking`.`team_name` AS `team_name`,`season_ranking`.`category` AS `category`,sum((case when (`season_ranking`.`rank_season` = 1) then 1 else 0 end)) AS `titles`,sum((case when (`season_ranking`.`rank_season` = 2) then 1 else 0 end)) AS `vice_titles`,sum((case when (`season_ranking`.`rank_season` = 3) then 1 else 0 end)) AS `third_place` from `season_ranking` group by `season_ranking`.`team_id`,`season_ranking`.`team_name`,`season_ranking`.`category`  ;

-- --------------------------------------------------------

--
-- Structure de la vue `team_points_all_seasons`
--
DROP TABLE IF EXISTS `team_points_all_seasons`;

DROP VIEW IF EXISTS `team_points_all_seasons`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `team_points_all_seasons`  AS SELECT `t`.`id` AS `team_id`, `t`.`name` AS `team_name`, ((coalesce(sum(`gp_pts`.`points_numeric`),0) + coalesce(sum(`ma`.`points`),0)) - coalesce(sum(`p`.`points_removed`),0)) AS `total_points` FROM ((((((`teams` `t` join `teams_drivers` `td` on((`td`.`team_id` = `t`.`id`))) join `drivers` `d` on((`d`.`id` = `td`.`driver_id`))) join `gp_points` `gp_pts` on(((`gp_pts`.`driver_id` = `d`.`id`) and (`gp_pts`.`team_id` = `t`.`id`)))) join `gp` `g` on(((`g`.`id` = `gp_pts`.`gp_id`) and (`g`.`season_id` = `td`.`season_id`)))) left join `manual_adjustments` `ma` on(((`ma`.`team_id` = `t`.`id`) and (`ma`.`driver_id` = `d`.`id`) and (`ma`.`season_id` = `g`.`season_id`)))) left join `penalties` `p` on(((`p`.`driver_id` = `d`.`id`) and (`p`.`gp_id` = `g`.`id`)))) GROUP BY `t`.`id`, `t`.`name` ;

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















-- MODIFICATIONS FAITES :

-- les points manual_adjustments étaient multipliés par le nombre de GP de chaque pilote
-- voici le rectificatif

DROP VIEW IF EXISTS `drivers_standings`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `drivers_standings` AS
SELECT
  s.id AS season_id,
  s.season_number AS season_number,
  c.name AS category,
  d.id AS driver_id,
  d.nickname AS nickname,
  (
    COALESCE(SUM(gp_pts.points_numeric), 0)
    + COALESCE(ma_sum.ma_points, 0)
    - COALESCE(SUM(p.points_removed), 0)
  ) AS total_points,
  SUM(CASE WHEN gp_pts.position = 1 THEN 1 ELSE 0 END) AS wins,
  SUM(CASE WHEN gp_pts.position IN (1,2,3) THEN 1 ELSE 0 END) AS podiums
FROM seasons s
JOIN categories c ON (s.category_id = c.id)
JOIN gp g ON (g.season_id = s.id)
JOIN gp_points gp_pts ON (gp_pts.gp_id = g.id)
JOIN drivers d ON (d.id = gp_pts.driver_id)
-- derived table that pre-aggregates manual adjustments per season/driver
LEFT JOIN (
    SELECT season_id, driver_id, SUM(points) AS ma_points
    FROM manual_adjustments
    GROUP BY season_id, driver_id
) AS ma_sum ON (ma_sum.season_id = s.id AND ma_sum.driver_id = d.id)
LEFT JOIN penalties p ON (p.driver_id = d.id AND p.gp_id = g.id)
GROUP BY s.id, s.season_number, c.name, d.id, d.nickname;


-- MODIF DRIVER AWARDS POUR NE RENVOYER QUE LES PILOTES AVEC TITRES

DROP VIEW IF EXISTS `driver_awards`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `driver_awards` AS
WITH season_ranking AS (
    SELECT
        ds.season_id AS season_id,
        ds.category AS category,
        ds.driver_id AS driver_id,
        ds.nickname AS nickname,
        ds.total_points AS total_points,
        RANK() OVER (PARTITION BY ds.season_id ORDER BY ds.total_points DESC) AS rank_season
    FROM drivers_standings ds
)
SELECT
    sr.driver_id AS driver_id,
    sr.nickname AS nickname,
    sr.category AS category,
    SUM(CASE WHEN sr.rank_season = 1 THEN 1 ELSE 0 END) AS titles,
    SUM(CASE WHEN sr.rank_season = 2 THEN 1 ELSE 0 END) AS vice_titles,
    SUM(CASE WHEN sr.rank_season = 3 THEN 1 ELSE 0 END) AS third_place
FROM season_ranking sr
GROUP BY sr.driver_id, sr.nickname, sr.category
HAVING SUM(CASE WHEN sr.rank_season = 1 THEN 1 ELSE 0 END)
    + SUM(CASE WHEN sr.rank_season = 2 THEN 1 ELSE 0 END)
    + SUM(CASE WHEN sr.rank_season = 3 THEN 1 ELSE 0 END) > 0;

-- MODIF GP COUNT POUR QUE LE CALCUL DES GP SOIT FAIT PAR CATEGORIE

DROP VIEW IF EXISTS `driver_gp_counts`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `driver_gp_counts` AS
SELECT 
    d.id AS driver_id,
    d.nickname AS nickname,
    c.name AS category,
    COUNT(DISTINCT gp_pts.gp_id) AS total_gp
FROM drivers d
LEFT JOIN gp_points gp_pts ON gp_pts.driver_id = d.id
LEFT JOIN gp g ON g.id = gp_pts.gp_id
LEFT JOIN seasons s ON s.id = g.season_id
LEFT JOIN categories c ON c.id = s.category_id
GROUP BY d.id, d.nickname, c.name
ORDER BY c.name, d.nickname;


-- MODIF DRIVER STANDINGS POUR QUE LA TEAM DE LA TABLE TEAMS_DRIVERS S'AFFICHE :

DROP VIEW IF EXISTS `drivers_standings`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `drivers_standings` AS
SELECT
    s.id AS season_id,
    s.season_number AS season_number,
    c.name AS category,
    d.id AS driver_id,
    d.nickname AS nickname,
    t.name AS team_name,
    (
        COALESCE(SUM(gp_pts.points_numeric), 0)
        + COALESCE(ma_sum.ma_points, 0)
        - COALESCE(SUM(p.points_removed), 0)
    ) AS total_points,
    SUM(CASE WHEN gp_pts.position = 1 THEN 1 ELSE 0 END) AS wins,
    SUM(CASE WHEN gp_pts.position IN (1,2,3) THEN 1 ELSE 0 END) AS podiums
FROM seasons s
JOIN categories c ON c.id = s.category_id
JOIN gp g ON g.season_id = s.id
JOIN gp_points gp_pts ON gp_pts.gp_id = g.id
JOIN drivers d ON d.id = gp_pts.driver_id
-- jointure avec teams_drivers et teams pour récupérer la team
LEFT JOIN teams_drivers td ON td.driver_id = d.id AND td.season_id = s.id
LEFT JOIN teams t ON t.id = td.team_id
-- pré-agrégation des manual_adjustments
LEFT JOIN (
    SELECT season_id, driver_id, SUM(points) AS ma_points
    FROM manual_adjustments
    GROUP BY season_id, driver_id
) AS ma_sum ON ma_sum.season_id = s.id AND ma_sum.driver_id = d.id
LEFT JOIN penalties p ON p.driver_id = d.id AND p.gp_id = g.id
GROUP BY s.id, s.season_number, c.name, d.id, d.nickname, t.name;


-- SUPPRESSION DES 2 VUES SQL drivergpcounts et driverpointsallseasons
-- POUR EN CREER UNE SEULE QUI REUNIT LES DEUX :

DROP VIEW IF EXISTS `driver_gp_counts`;
DROP VIEW IF EXISTS `driver_points_all_seasons`;


DROP VIEW IF EXISTS `driver_stats_all_seasons`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `driver_stats_all_seasons` AS
SELECT
    d.id AS driver_id,
    d.nickname AS nickname,
    c.name AS category,
    COUNT(DISTINCT gp_pts.gp_id) AS total_gp,
    (
        COALESCE(SUM(gp_pts.points_numeric), 0)
        + COALESCE(ma_sum.ma_points, 0)
        - COALESCE(SUM(p.points_removed), 0)
    ) AS total_points
FROM drivers d
LEFT JOIN gp_points gp_pts ON gp_pts.driver_id = d.id
LEFT JOIN gp g ON g.id = gp_pts.gp_id
LEFT JOIN seasons s ON s.id = g.season_id
LEFT JOIN categories c ON c.id = s.category_id
LEFT JOIN (
    SELECT season_id, driver_id, SUM(points) AS ma_points
    FROM manual_adjustments
    GROUP BY season_id, driver_id
) AS ma_sum ON ma_sum.driver_id = d.id AND ma_sum.season_id = s.id
LEFT JOIN penalties p ON p.driver_id = d.id AND p.gp_id = g.id
GROUP BY d.id, d.nickname, c.name;


-- MODIF DRIVERS STANDINGS POUR QUE LE PARAMETRE SEASON ACTIVE OU DESACTIVE SOIT PRIS EN COMPTE
-- LE BUT ETANT D'AFFICHER SEULEMENT LES STANDINGS DES SEASONS ACTIVE ET D 'AJOUTER EN LISTE DEROULANTE L'ACCES AUX SEASONS DESACTIVE

DROP VIEW IF EXISTS `drivers_standings`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `drivers_standings` AS
SELECT
    s.id AS season_id,
    s.season_number AS season_number,
    s.status AS season_status,
    c.name AS category,
    d.id AS driver_id,
    d.nickname AS nickname,
    t.name AS team_name,
    (
        COALESCE(SUM(gp_pts.points_numeric), 0)
        + COALESCE(ma_sum.ma_points, 0)
        - COALESCE(SUM(p.points_removed), 0)
    ) AS total_points,
    SUM(CASE WHEN gp_pts.position = 1 THEN 1 ELSE 0 END) AS wins,
    SUM(CASE WHEN gp_pts.position IN (1,2,3) THEN 1 ELSE 0 END) AS podiums
FROM seasons s
JOIN categories c ON c.id = s.category_id
JOIN gp g ON g.season_id = s.id
JOIN gp_points gp_pts ON gp_pts.gp_id = g.id
JOIN drivers d ON d.id = gp_pts.driver_id
LEFT JOIN teams_drivers td ON td.driver_id = d.id AND td.season_id = s.id
LEFT JOIN teams t ON t.id = td.team_id
LEFT JOIN (
    SELECT season_id, driver_id, SUM(points) AS ma_points
    FROM manual_adjustments
    GROUP BY season_id, driver_id
) AS ma_sum ON ma_sum.season_id = s.id AND ma_sum.driver_id = d.id
LEFT JOIN penalties p ON p.driver_id = d.id AND p.gp_id = g.id
GROUP BY s.id, s.season_number, s.status, c.name, d.id, d.nickname, t.name;


-- TEAMS STANDINGS MIS A JOUR POUR QUE LE CALCUL SOIT BON :

DROP VIEW IF EXISTS `teams_standings`;

CREATE ALGORITHM=UNDEFINED 
DEFINER=`root`@`localhost` 
SQL SECURITY DEFINER 
VIEW `teams_standings` AS

SELECT 
    s.id AS season_id,
    s.season_number,
    c.name AS category,
    t.id AS team_id,
    t.name AS team_name,

    -- Total points = points gp + manual_adjustments - penalties
    (
        COALESCE((
            SELECT SUM(gp_pts.points_numeric)
            FROM gp g
            JOIN gp_points gp_pts 
              ON gp_pts.gp_id = g.id
             AND gp_pts.team_id = t.id
            JOIN teams_drivers td ON td.driver_id = gp_pts.driver_id AND td.team_id = t.id AND td.season_id = s.id
            WHERE g.season_id = s.id
        ),0)
        +
        COALESCE((
            SELECT SUM(ma.points)
            FROM manual_adjustments ma
            WHERE ma.season_id = s.id
              AND ma.team_id = t.id
        ),0)
        -
        COALESCE((
            SELECT SUM(p.points_removed)
            FROM penalties p
            JOIN gp g ON g.id = p.gp_id
            JOIN drivers d ON d.id = p.driver_id
            JOIN teams_drivers td ON td.driver_id = d.id AND td.team_id = t.id AND td.season_id = s.id
            WHERE g.season_id = s.id
        ),0)
    ) AS total_points

FROM seasons s
JOIN categories c ON c.id = s.category_id
JOIN teams t
JOIN teams_drivers td ON td.team_id = t.id AND td.season_id = s.id

GROUP BY s.id, s.season_number, c.name, t.id, t.name;

-- TEAMS STANDINGS REFAIT CAR PRENAIT EN COMPTE L'ASSOCIATION TEAMSDRIVERS POUR DEDUIRE LES PENALITES DES PILOTES A LEUR TEAM ASSOCIEE

DROP VIEW IF EXISTS `teams_standings`;

CREATE ALGORITHM=UNDEFINED
DEFINER=`root`@`localhost`
SQL SECURITY DEFINER
VIEW `teams_standings` AS
SELECT 
    s.id AS season_id,
    s.season_number,
    c.name AS category,
    t.id AS team_id,
    t.name AS team_name,

    -- Total points pour l'équipe
    (
        COALESCE(SUM(gp_pts.points_numeric), 0)
        + COALESCE(SUM(ma.points), 0)
        - COALESCE(SUM(p.points_removed), 0)
    ) AS total_points

FROM seasons s
JOIN categories c ON c.id = s.category_id
JOIN teams t

-- Tous les GP de la saison
LEFT JOIN gp g ON g.season_id = s.id

-- GP points liés directement à l'équipe
LEFT JOIN gp_points gp_pts 
    ON gp_pts.gp_id = g.id 
   AND gp_pts.team_id = t.id

-- Manual adjustments liés à l'équipe
LEFT JOIN manual_adjustments ma
    ON ma.season_id = s.id 
   AND ma.team_id = t.id

-- Pénalités liés à l'équipe (via GP)
LEFT JOIN penalties p
    ON p.gp_id = g.id
   AND p.team_id = t.id

GROUP BY s.id, s.season_number, c.name, t.id, t.name;


-- NOUVEAU TEAMS_STANDINGS CAR APPLIQUAIT MANUAL_ADJUSTMENTS POUR CHAQUE GP, DONC SUR UNE SAISON A 18GP IL FAISAIT LES POINTS x 18
-- CELUI LA FONCTIONNE BIEN POUR LES CALCULS !

DROP VIEW IF EXISTS `teams_standings`;

CREATE ALGORITHM=UNDEFINED
DEFINER=`root`@`localhost`
SQL SECURITY DEFINER
VIEW `teams_standings` AS
SELECT 
    s.id AS season_id,
    s.season_number,
    c.name AS category,
    t.id AS team_id,
    t.name AS team_name,

    -- Total points pour l'équipe
    (
        COALESCE(SUM(gp_pts.points_numeric), 0)
        + COALESCE(ma.total_points, 0)
        - COALESCE(SUM(p.points_removed), 0)
    ) AS total_points

FROM seasons s
JOIN categories c ON c.id = s.category_id
JOIN teams t

-- Tous les GP de la saison
LEFT JOIN gp g ON g.season_id = s.id

-- GP points liés directement à l'équipe
LEFT JOIN gp_points gp_pts 
    ON gp_pts.gp_id = g.id 
   AND gp_pts.team_id = t.id

-- Manual adjustments liés à l'équipe (agrégés une seule fois)
LEFT JOIN (
    SELECT season_id, team_id, SUM(points) AS total_points
    FROM manual_adjustments
    GROUP BY season_id, team_id
) ma
    ON ma.season_id = s.id 
   AND ma.team_id = t.id

-- Pénalités liées à l'équipe (via GP)
LEFT JOIN penalties p
    ON p.gp_id = g.id
   AND p.team_id = t.id

GROUP BY s.id, s.season_number, c.name, t.id, t.name;


-- DRIVERS STANDINGS PREND EN COMPTE LES PENALITES DES DRIVERS (CE NETAIT PAS LE CAS)

DROP VIEW IF EXISTS `drivers_standings`;

CREATE ALGORITHM=UNDEFINED
DEFINER=`root`@`localhost`
SQL SECURITY DEFINER
VIEW `drivers_standings` AS
SELECT
    s.id AS season_id,
    s.season_number AS season_number,
    s.status AS season_status,
    c.name AS category,
    d.id AS driver_id,
    d.nickname AS nickname,
    t.name AS team_name,

    -- Total points pour le pilote
    (
        COALESCE(SUM(gp_pts.points_numeric), 0)
        + COALESCE(ma.total_points, 0)
        - COALESCE(SUM(p.points_removed), 0)
    ) AS total_points,

    SUM(CASE WHEN gp_pts.position = 1 THEN 1 ELSE 0 END) AS wins,
    SUM(CASE WHEN gp_pts.position IN (1,2,3) THEN 1 ELSE 0 END) AS podiums

FROM seasons s
JOIN categories c ON c.id = s.category_id
JOIN gp g ON g.season_id = s.id
JOIN gp_points gp_pts ON gp_pts.gp_id = g.id
JOIN drivers d ON d.id = gp_pts.driver_id

-- Récupérer l'équipe actuelle (optionnelle)
LEFT JOIN teams_drivers td ON td.driver_id = d.id AND td.season_id = s.id
LEFT JOIN teams t ON t.id = td.team_id

-- Manual adjustments par pilote (une seule fois par saison)
LEFT JOIN (
    SELECT season_id, driver_id, SUM(points) AS total_points
    FROM manual_adjustments
    GROUP BY season_id, driver_id
) ma ON ma.season_id = s.id AND ma.driver_id = d.id

-- Pénalités liées au pilote pour chaque GP
LEFT JOIN penalties p ON p.driver_id = d.id AND p.gp_id = g.id

GROUP BY s.id, s.season_number, s.status, c.name, d.id, d.nickname, t.name;


-- AJOUT DE L INFO COLOR POUR LES CATEGORIES

ALTER TABLE categories
ADD COLUMN color VARCHAR(7) NOT NULL DEFAULT '#E10600' AFTER name;
