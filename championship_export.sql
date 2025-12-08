-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 04 déc. 2025 à 16:39
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`) VALUES
(13, 'F1', 'active'),
(14, 'Project CARS', 'active');

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `circuits`
--

INSERT INTO `circuits` (`id`, `name`, `country_id`, `status`) VALUES
(1, 'Paul Ricard', 1, 'active'),
(6, 'Monza', 4, 'active');

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `flag`) VALUES
(1, 'France', 'FRA', ''),
(4, 'Italie', 'ITA', ''),
(10, 'Paraguay', 'PAR', ''),
(17, 'Espagne', 'ESP', '');

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `drivers`
--

INSERT INTO `drivers` (`id`, `nickname`, `country_id`, `status`) VALUES
(10, 'paraguayan99', 10, 'active'),
(11, 'ClousCoco', 4, 'active');

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
,`total_points` decimal(34,0)
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
,`total_points` decimal(34,0)
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `gp`
--

INSERT INTO `gp` (`id`, `season_id`, `circuit_id`, `gp_ordre`) VALUES
(22, 13, 6, 1),
(23, 19, 1, 1);

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
  `points_numeric` int DEFAULT '0',
  `points_text` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_points_gp` (`gp_id`),
  KEY `idx_points_driver` (`driver_id`),
  KEY `idx_points_team` (`team_id`),
  KEY `idx_points_gp_driver` (`gp_id`,`driver_id`),
  KEY `idx_points_driver_team` (`driver_id`,`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `points` int NOT NULL,
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
(26, 19, 10, 27, 11, ''),
(27, 13, 11, 27, 10, '');

-- --------------------------------------------------------

--
-- Structure de la table `penalties`
--

DROP TABLE IF EXISTS `penalties`;
CREATE TABLE IF NOT EXISTS `penalties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gp_id` int NOT NULL,
  `driver_id` int NOT NULL,
  `points_removed` int NOT NULL,
  `comment` text,
  PRIMARY KEY (`id`),
  KEY `idx_pen_gp` (`gp_id`),
  KEY `idx_pen_driver` (`driver_id`),
  KEY `idx_pen_gp_driver` (`gp_id`,`driver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `seasons`
--

INSERT INTO `seasons` (`id`, `season_number`, `category_id`, `videogame`, `platform`, `status`) VALUES
(13, 1, 13, 'F1 Championship Edition', 'PS3', 'active'),
(19, 1, 14, 'PC', 'PS3', 'active');

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `teams`
--

INSERT INTO `teams` (`id`, `name`, `logo`, `color`, `country_id`, `status`) VALUES
(27, 'ALPINE', '', '', 1, 'active'),
(28, 'FERRARI', '', '', 4, 'active');

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
  KEY `idx_td_season` (`season_id`),
  KEY `idx_td_driver` (`driver_id`),
  KEY `idx_td_team` (`team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `teams_drivers`
--

INSERT INTO `teams_drivers` (`id`, `season_id`, `driver_id`, `team_id`) VALUES
(11, 13, 11, 28),
(12, 19, 10, 27);

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
,`total_points` decimal(34,0)
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
,`total_points` decimal(34,0)
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `updates_log`
--

INSERT INTO `updates_log` (`id`, `season_id`, `gp_id`, `table_name`, `updated_at`, `updated_by`, `action`) VALUES
(11, NULL, NULL, 'manual_adjustments', '2025-12-04 17:12:43', 1, 'create'),
(12, NULL, NULL, 'manual_adjustments', '2025-12-04 17:12:54', 1, 'update'),
(13, NULL, NULL, 'manual_adjustments', '2025-12-04 17:12:57', 1, 'delete'),
(14, 19, NULL, 'manual_adjustments', '2025-12-04 17:18:53', 1, 'update'),
(15, 19, NULL, 'manual_adjustments', '2025-12-04 17:22:55', 1, 'update'),
(16, 19, NULL, 'manual_adjustments', '2025-12-04 17:23:06', 1, 'create'),
(17, 19, NULL, 'manual_adjustments', '2025-12-04 17:23:10', 1, 'delete');

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
  ADD CONSTRAINT `penalties_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`);

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
