-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : sqlprive-pc2372-001.eu.clouddb.ovh.net:35167
-- Généré le : dim. 11 jan. 2026 à 00:43
-- Version du serveur : 8.0.44-35
-- Version de PHP : 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cefiidev1493`
--

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
(1, 'France', 'FRA', 'img/flags/france.png');
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
  `points_text` varchar(3) DEFAULT NULL,
  `driver_unique_id` int GENERATED ALWAYS AS ((case when (`driver_id` = 1) then NULL else `driver_id` end)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


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
(1, 'admin@racing-championship-app.fr', '$2y$10$63SO9YwcUadXturiIb6OIe5OFxXKbr476nr7fuC1EmAnPdcUs8VU.', 1);

-- --------------------------------------------------------
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
  ADD UNIQUE KEY `uq_gp_driver` (`gp_id`,`driver_unique_id`),
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `circuits`
--
ALTER TABLE `circuits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `gp`
--
ALTER TABLE `gp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `gp_points`
--
ALTER TABLE `gp_points`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT pour la table `manual_adjustments`
--
ALTER TABLE `manual_adjustments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `penalties`
--
ALTER TABLE `penalties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `seasons`
--
ALTER TABLE `seasons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `teams_drivers`
--
ALTER TABLE `teams_drivers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `updates_log`
--
ALTER TABLE `updates_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  ADD CONSTRAINT `circuits_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `fk_circuits_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  ADD CONSTRAINT `fk_drivers_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Contraintes pour la table `gp`
--
ALTER TABLE `gp`
  ADD CONSTRAINT `fk_gp_circuit` FOREIGN KEY (`circuit_id`) REFERENCES `circuits` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_gp_season` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `gp_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gp_ibfk_2` FOREIGN KEY (`circuit_id`) REFERENCES `circuits` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `gp_points`
--
ALTER TABLE `gp_points`
  ADD CONSTRAINT `fk_gp_points_gp` FOREIGN KEY (`gp_id`) REFERENCES `gp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `seasons`
--
ALTER TABLE `seasons`
  ADD CONSTRAINT `fk_seasons_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `seasons_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `fk_teams_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`);

--
-- Contraintes pour la table `teams_drivers`
--
ALTER TABLE `teams_drivers`
  ADD CONSTRAINT `fk_teams_drivers_driver` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teams_drivers_season` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_teams_drivers_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `teams_drivers_ibfk_1` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teams_drivers_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`),
  ADD CONSTRAINT `teams_drivers_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`);

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
