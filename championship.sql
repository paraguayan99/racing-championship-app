-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 28 nov. 2025 à 09:00
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
-- Base de données : `championship`
--

-- --------------------------------------------------------

--
-- Structure de la table `actual_teams`
--a

DROP TABLE IF EXISTS `actual_teams`;
CREATE TABLE IF NOT EXISTS `actual_teams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `team_id` int NOT NULL,
  `driver1_id` int DEFAULT NULL,
  `driver2_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_id` (`team_id`),
  KEY `driver1_id` (`driver1_id`),
  KEY `driver2_id` (`driver2_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `circuits`
--

DROP TABLE IF EXISTS `circuits`;
CREATE TABLE IF NOT EXISTS `circuits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `drivers`
--

DROP TABLE IF EXISTS `drivers`;
CREATE TABLE IF NOT EXISTS `drivers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nickname` varchar(100) NOT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `drivers_standings`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `drivers_standings`;
CREATE TABLE IF NOT EXISTS `drivers_standings` (
`category` enum('F1','F2','F3')
,`driver_id` int
,`nickname` varchar(100)
,`podiums` decimal(23,0)
,`season_id` int
,`season_number` int
,`total_points` decimal(32,0)
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
  `gp_name` varchar(150) DEFAULT NULL,
  `gp_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `season_id` (`season_id`),
  KEY `circuit_id` (`circuit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `points` int DEFAULT '0',
  `pole_position_driver` int DEFAULT NULL,
  `pole_position_time` varchar(50) DEFAULT NULL,
  `fastest_lap_driver` int DEFAULT NULL,
  `fastest_lap_time` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gp_id` (`gp_id`),
  KEY `driver_id` (`driver_id`),
  KEY `team_id` (`team_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  KEY `season_id` (`season_id`),
  KEY `driver_id` (`driver_id`),
  KEY `team_id` (`team_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  KEY `gp_id` (`gp_id`),
  KEY `driver_id` (`driver_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `category` enum('F1','F2','F3') NOT NULL,
  `videogame` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--

DROP TABLE IF EXISTS `teams`;
CREATE TABLE IF NOT EXISTS `teams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  KEY `season_id` (`season_id`),
  KEY `driver_id` (`driver_id`),
  KEY `team_id` (`team_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `role_id`) VALUES
(1, 'paraguayan99@laposte.net', '$2y$10$jI3l3bZc92tGCe.OqMbVWuu/diVN7AlMHhxkEWUKR8BxgY3ardwYC', 1);

-- --------------------------------------------------------

--
-- Structure de la vue `drivers_standings`
--
DROP TABLE IF EXISTS `drivers_standings`;

DROP VIEW IF EXISTS `drivers_standings`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `drivers_standings`  AS SELECT `s`.`id` AS `season_id`, `s`.`season_number` AS `season_number`, `s`.`category` AS `category`, `d`.`id` AS `driver_id`, `d`.`nickname` AS `nickname`, coalesce(sum(`gp`.`points`),0) AS `total_points`, sum((case when (`gp`.`position` = 1) then 1 else 0 end)) AS `wins`, sum((case when (`gp`.`position` in (1,2,3)) then 1 else 0 end)) AS `podiums` FROM (((`seasons` `s` join `gp` `g` on((`g`.`season_id` = `s`.`id`))) join `gp_points` `gp` on((`gp`.`gp_id` = `g`.`id`))) join `drivers` `d` on((`d`.`id` = `gp`.`driver_id`))) GROUP BY `s`.`id`, `d`.`id` ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
