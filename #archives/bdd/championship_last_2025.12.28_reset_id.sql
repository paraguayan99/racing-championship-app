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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `color`, `status`) VALUES
(1, 'F1', '#e10600', 'active'),
(2, 'F2', '#366092', 'active'),
(3, 'F3', '#c904d7', 'active');

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
(1, 'Sakhir', 1, 'active'),
(2, 'Sepang', 1, 'active'),
(3, 'Melbourne', 1, 'active'),
(4, 'Imola', 1, 'active'),
(5, 'Nurburgring', 1, 'active'),
(6, 'Barcelone', 1, 'active'),
(7, 'Monte-Carlo', 1, 'active'),
(8, 'Silverstone', 1, 'active'),
(9, 'Montreal', 1, 'active'),
(10, 'Indianapolis', 1, 'active'),
(11, 'Nevers Magny-Cours', 1, 'active'),
(12, 'Hockenheim', 1, 'active'),
(13, 'Hungaroring', 1, 'active'),
(14, 'Istanbul', 1, 'active'),
(15, 'Monza', 1, 'active'),
(16, 'Shanghai', 1, 'active'),
(17, 'Suzuka', 1, 'active'),
(18, 'Interlagos', 1, 'active');

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
(13, 'Turquie', 'TUR', ''),
(14, 'Chine', 'CHI', 'img/flags/chine.png'),
(15, 'Japon', 'JAP', 'img/flags/japon.png'),
(16, 'Brésil', 'BRE', 'img/flags/bresil.png'),
(17, 'Suisse', 'SUI', 'img/flags/suisse.png'),
(18, 'Autriche', 'AUT', 'img/flags/autriche.png'),
(19, 'Pays-Bas', 'P-B', 'img/flags/paysbas.png');

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
(1, 1, 1, 'F1 Championship Edition', 'PS3', 'active');

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
(2, 'Toyota', '', '#f90606', 1, 'active'),
(3, 'Williams', 'img/teams/williams.png', '#03A8EA', 1, 'active'),
(4, 'Ferrari', 'img/teams/ferrari.png', '#FE0000', 1, 'active'),
(5, 'Renault', '', '#8e9018', 1, 'active'),
(6, 'Super Aguri', '', '#ff0000', 1, 'active'),
(7, 'Honda', '', '#000000', 1, 'active'),
(8, 'BMW Sauber', '', '#139fcd', 1, 'active'),
(9, 'McLaren', 'img/teams/mclaren.png', '#FF8500', 1, 'active'),
(10, 'Red Bull', 'img/teams/redbull.png', '#15185E', 1, 'active'),
(11, 'Toro Rosso', '', '#2f2cf2', 1, 'active'),
(12, 'Spyker', '', NULL, 1, 'active');

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
