-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : sqlprive-pc2372-001.eu.clouddb.ovh.net:35167
-- Généré le : sam. 17 jan. 2026 à 23:42
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

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `color`, `status`) VALUES
(1, 'F1', '#e10600', 'active'),
(2, 'F2', '#366092', 'active'),
(3, 'F3', '#666666', 'active');

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
(12, 'Gafit', 1, 'active'),
(13, 'Pintodiogo', 1, 'active'),
(14, 'Sagitariomle', 1, 'active'),
(15, 'Rosbeef', 1, 'active'),
(16, 'Alidebian', 1, 'active'),
(17, 'Sangohan', 1, 'active'),
(18, 'Audalgrege', 1, 'active'),
(19, 'Stephschumi2', 1, 'active'),
(20, 'Nonogolden', 1, 'active'),
(21, 'Saimai', 1, 'active'),
(22, 'Omptitju', 1, 'active'),
(23, 'Alonso59450', 1, 'active'),
(24, 'Mercedeslewis08', 1, 'active'),
(25, 'FerrariMcLaren', 1, 'active'),
(26, 'Rudy Scuderia', 1, 'active'),
(27, 'Kevin193', 1, 'active'),
(28, 'Darkness14', 1, 'active'),
(29, 'Pacochab73', 1, 'active'),
(30, 'Chrald3413', 1, 'active'),
(31, 'Maldagar', 1, 'active'),
(32, 'Erfortissimo92', 1, 'active'),
(33, 'Nonols001', 1, 'active'),
(34, 'Dark shy', 1, 'active'),
(35, 'Mehdimclaren', 1, 'active');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `drivers_palmares`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `drivers_palmares` (
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
CREATE TABLE `drivers_standings` (
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
(18, 1, 18, 18),
(19, 2, 1, 1),
(20, 2, 2, 2),
(21, 2, 3, 3),
(22, 2, 4, 4),
(23, 2, 5, 5),
(24, 2, 6, 6),
(25, 2, 7, 7),
(26, 2, 8, 8),
(27, 2, 9, 9),
(28, 2, 10, 10),
(29, 2, 11, 11),
(30, 2, 12, 12),
(31, 2, 13, 13),
(32, 2, 14, 14),
(33, 2, 15, 15),
(34, 2, 16, 16),
(35, 2, 17, 17),
(36, 2, 18, 18),
(37, 3, 14, 1),
(38, 3, 15, 2),
(39, 3, 16, 3),
(40, 3, 17, 4),
(41, 3, 18, 5);

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
(120, 18, 10, 10, 9, 0.0, NULL),
(121, 19, 14, 3, 1, 0.0, NULL),
(122, 19, 13, 2, 2, 0.0, NULL),
(123, 19, 1, 1, 3, 0.0, NULL),
(124, 19, 16, 10, 6, 0.0, NULL),
(125, 19, 18, 8, 8, 0.0, NULL),
(126, 20, 13, 2, 5, 0.0, NULL),
(127, 20, 14, 3, 1, 0.0, NULL),
(128, 20, 16, 10, 3, 0.0, NULL),
(129, 20, 18, 8, 2, 0.0, NULL),
(130, 21, 13, 2, 1, 0.0, NULL),
(131, 21, 15, 11, 2, 0.0, NULL),
(132, 21, 16, 10, 11, 0.0, NULL),
(133, 21, 18, 8, 3, 0.0, NULL),
(134, 22, 13, 2, 2, 0.0, NULL),
(135, 22, 14, 3, 6, 0.0, NULL),
(136, 22, 15, 11, 1, 0.0, NULL),
(137, 22, 16, 10, 5, 0.0, NULL),
(138, 22, 18, 8, 3, 0.0, NULL),
(139, 22, 21, 12, 11, 0.0, NULL),
(140, 23, 13, 2, 5, 0.0, NULL),
(141, 23, 14, 3, 2, 0.0, NULL),
(142, 23, 15, 11, 4, 0.0, NULL),
(143, 23, 16, 10, 6, 0.0, NULL),
(144, 23, 18, 8, 3, 0.0, NULL),
(145, 23, 20, 6, 10, 0.0, NULL),
(146, 23, 21, 12, 11, 0.0, NULL),
(148, 23, 1, 1, 1, 0.0, NULL),
(149, 24, 13, 2, 1, 0.0, NULL),
(150, 24, 14, 3, 4, 0.0, NULL),
(151, 24, 15, 11, 2, 0.0, NULL),
(152, 24, 16, 10, 10, 0.0, NULL),
(153, 24, 18, 8, 5, 0.0, NULL),
(154, 24, 20, 6, 9, 0.0, NULL),
(155, 24, 21, 12, 11, 0.0, NULL),
(156, 24, 1, 1, 3, 0.0, NULL),
(157, 25, 13, 2, 2, 0.0, NULL),
(158, 25, 14, 3, 6, 0.0, NULL),
(159, 25, 15, 11, 8, 0.0, NULL),
(160, 25, 16, 10, 4, 0.0, NULL),
(161, 25, 18, 8, 1, 0.0, NULL),
(162, 25, 1, 1, 3, 0.0, NULL),
(163, 26, 13, 2, 1, 0.0, NULL),
(164, 26, 14, 3, 4, 0.0, NULL),
(165, 26, 15, 11, 3, 0.0, NULL),
(166, 26, 16, 10, 2, 0.0, NULL),
(167, 26, 18, 8, 7, 0.0, NULL),
(168, 26, 20, 6, 9, 0.0, NULL),
(169, 26, 22, 9, 8, 0.0, NULL),
(170, 27, 13, 2, 2, 0.0, NULL),
(171, 27, 14, 3, 4, 0.0, NULL),
(172, 27, 15, 11, 5, 0.0, NULL),
(173, 27, 16, 10, 1, 0.0, NULL),
(174, 27, 18, 8, 3, 0.0, NULL),
(175, 27, 20, 6, 9, 0.0, NULL),
(176, 27, 21, 12, 11, 0.0, NULL),
(177, 27, 23, 5, 10, 0.0, NULL),
(178, 28, 13, 2, 1, 0.0, NULL),
(179, 28, 14, 3, 2, 0.0, NULL),
(180, 28, 15, 11, 6, 0.0, NULL),
(181, 28, 16, 10, 4, 0.0, NULL),
(182, 28, 18, 8, 5, 0.0, NULL),
(183, 28, 19, 4, 3, 0.0, NULL),
(184, 28, 22, 9, 9, 0.0, NULL),
(185, 28, 23, 5, 8, 0.0, NULL),
(186, 29, 13, 2, 5, 0.0, NULL),
(187, 29, 14, 3, 6, 0.0, NULL),
(188, 29, 15, 11, 4, 0.0, NULL),
(189, 29, 16, 10, 3, 0.0, NULL),
(190, 29, 17, 7, 1, 0.0, NULL),
(191, 29, 18, 8, 7, 0.0, NULL),
(192, 29, 19, 4, 2, 0.0, NULL),
(193, 29, 20, 6, 8, 0.0, NULL),
(194, 29, 21, 12, 10, 0.0, NULL),
(195, 29, 23, 5, 11, 0.0, NULL),
(196, 30, 13, 2, 1, 0.0, NULL),
(197, 30, 14, 3, 4, 0.0, NULL),
(198, 30, 15, 11, 3, 0.0, NULL),
(199, 30, 16, 10, 6, 0.0, NULL),
(200, 30, 17, 7, 2, 0.0, NULL),
(201, 30, 18, 8, 7, 0.0, NULL),
(202, 30, 19, 4, 5, 0.0, NULL),
(203, 30, 20, 6, 9, 0.0, NULL),
(204, 30, 21, 12, 11, 0.0, NULL),
(205, 31, 13, 2, 6, 0.0, NULL),
(206, 31, 14, 3, 4, 0.0, NULL),
(207, 31, 15, 11, 2, 0.0, NULL),
(208, 31, 16, 10, 5, 0.0, NULL),
(209, 31, 17, 7, 1, 0.0, NULL),
(210, 31, 19, 4, 3, 0.0, NULL),
(211, 31, 20, 6, 7, 0.0, NULL),
(212, 32, 13, 2, 7, 0.0, NULL),
(213, 32, 14, 3, 4, 0.0, NULL),
(214, 32, 15, 11, 2, 0.0, NULL),
(215, 32, 16, 10, 5, 0.0, NULL),
(216, 32, 17, 7, 1, 0.0, NULL),
(217, 32, 19, 4, 3, 0.0, NULL),
(218, 32, 20, 6, 6, 0.0, NULL),
(219, 32, 21, 12, 11, 0.0, NULL),
(220, 32, 22, 9, 10, 0.0, NULL),
(221, 33, 13, 2, 2, 0.0, NULL),
(223, 33, 14, 3, 4, 0.0, NULL),
(224, 33, 15, 11, 6, 0.0, NULL),
(225, 33, 16, 10, 5, 0.0, NULL),
(226, 33, 17, 7, 1, 0.0, NULL),
(227, 33, 18, 8, 7, 0.0, NULL),
(228, 33, 19, 4, 3, 0.0, NULL),
(229, 33, 21, 12, 9, 0.0, NULL),
(230, 33, 23, 5, 10, 0.0, NULL),
(231, 34, 13, 2, 4, 0.0, NULL),
(232, 34, 14, 3, 2, 0.0, NULL),
(233, 34, 15, 11, 6, 0.0, NULL),
(234, 34, 16, 10, 5, 0.0, NULL),
(235, 34, 17, 7, 3, 0.0, NULL),
(236, 34, 19, 4, 1, 0.0, NULL),
(237, 34, 21, 12, 7, 0.0, NULL),
(238, 35, 13, 2, 8, 0.0, NULL),
(239, 35, 14, 3, 4, 0.0, NULL),
(240, 35, 15, 11, 5, 0.0, NULL),
(241, 35, 16, 10, 2, 0.0, NULL),
(242, 35, 17, 7, 1, 0.0, NULL),
(243, 35, 18, 8, 11, 0.0, NULL),
(244, 35, 19, 4, 6, 0.0, NULL),
(245, 35, 21, 12, 10, 0.0, NULL),
(246, 35, 1, 1, 3, 0.0, NULL),
(247, 36, 13, 2, 5, 0.0, NULL),
(248, 36, 14, 3, 2, 0.0, NULL),
(249, 36, 15, 11, 3, 0.0, NULL),
(250, 36, 16, 10, 4, 0.0, NULL),
(251, 36, 17, 7, 1, 0.0, NULL),
(252, 36, 18, 8, 10, 0.0, NULL),
(253, 36, 19, 4, 6, 0.0, NULL),
(254, 37, 25, 9, 1, 0.0, NULL),
(255, 37, 26, 4, 3, 0.0, NULL),
(256, 37, 27, 11, 2, 0.0, NULL),
(257, 37, 28, 8, 4, 0.0, NULL),
(258, 38, 24, 5, 1, 0.0, NULL),
(259, 38, 25, 9, 2, 0.0, NULL),
(260, 38, 26, 4, 3, 0.0, NULL),
(261, 38, 28, 8, 4, 0.0, NULL),
(262, 38, 29, 7, 5, 0.0, NULL),
(263, 39, 24, 5, 2, 0.0, NULL),
(264, 39, 26, 4, 3, 0.0, NULL),
(265, 39, 27, 11, 1, 0.0, NULL),
(266, 39, 28, 8, 5, 0.0, NULL),
(267, 39, 29, 7, 4, 0.0, NULL),
(268, 40, 24, 5, 1, 0.0, NULL),
(269, 40, 25, 9, 5, 0.0, NULL),
(270, 40, 26, 4, 6, 0.0, NULL),
(271, 40, 27, 11, 3, 0.0, NULL),
(272, 40, 29, 7, 8, 0.0, NULL),
(273, 40, 30, 2, 2, 0.0, NULL),
(274, 40, 31, 3, 7, 0.0, NULL),
(275, 40, 32, 12, 4, 0.0, NULL),
(276, 40, 33, 6, 9, 0.0, NULL),
(277, 41, 24, 5, 1, 0.0, NULL),
(278, 41, 25, 9, 4, 0.0, NULL),
(279, 41, 26, 4, 8, 0.0, NULL),
(280, 41, 27, 11, 3, 0.0, NULL),
(281, 41, 29, 7, 6, 0.0, NULL),
(282, 41, 30, 2, 5, 0.0, NULL),
(283, 41, 31, 3, 7, 0.0, NULL),
(284, 41, 32, 12, 2, 0.0, NULL),
(285, 41, 34, 10, NULL, 0.0, NULL);

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
(11, 1, 12, 12, 4.0, NULL),
(12, 2, 13, 2, 117.0, NULL),
(13, 2, 14, 3, 101.0, NULL),
(14, 2, 15, 11, 88.0, NULL),
(15, 2, 16, 10, 82.0, NULL),
(16, 2, 17, 7, 74.0, NULL),
(17, 2, 18, 8, 59.0, NULL),
(18, 2, 19, 4, 52.0, NULL),
(19, 2, 20, 6, 6.0, NULL),
(20, 2, 21, 12, 2.0, NULL),
(21, 2, 22, 9, 1.0, NULL),
(22, 2, 23, 5, 1.0, NULL),
(23, 3, 24, 5, 33.0, NULL),
(24, 3, 25, 9, 27.0, NULL),
(25, 3, 26, 4, 22.0, NULL),
(26, 3, 27, 11, 30.0, ''),
(27, 3, 28, 8, 14.0, NULL),
(28, 3, 29, 7, 13.0, NULL),
(29, 3, 30, 2, 12.0, NULL),
(30, 3, 31, 3, 4.0, NULL),
(31, 3, 32, 12, 13.0, '');

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

--
-- Déchargement des données de la table `penalties`
--

INSERT INTO `penalties` (`id`, `gp_id`, `driver_id`, `team_id`, `points_removed`, `comment`) VALUES
(1, 41, 27, 11, 10, NULL),
(2, 41, 32, 12, 10, NULL),
(3, 41, 35, NULL, 5, NULL);

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
(2, 1, 2, 'F1 Championship Edition', 'PS3', 'desactive'),
(3, 1, 3, 'F1 Championship Edition', 'PS3', 'desactive');

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
(11, 1, 12, 12),
(12, 2, 13, 2),
(13, 2, 14, 3),
(14, 2, 15, 11),
(15, 2, 16, 10),
(16, 2, 17, 7),
(17, 2, 18, 8),
(18, 2, 19, 4),
(19, 2, 20, 6),
(20, 2, 21, 12),
(21, 2, 22, 9),
(22, 2, 23, 5),
(23, 3, 24, 5),
(24, 3, 25, 9),
(25, 3, 26, 4),
(26, 3, 27, 11),
(27, 3, 28, 8),
(28, 3, 29, 7),
(29, 3, 30, 2),
(30, 3, 31, 3),
(31, 3, 32, 12),
(32, 3, 33, 6),
(33, 3, 34, 10);

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

CREATE TABLE `updates_log` (
  `id` int NOT NULL,
  `season_id` int DEFAULT NULL,
  `gp_id` int DEFAULT NULL,
  `table_name` varchar(50) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int DEFAULT NULL,
  `action` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `updates_log`
--

INSERT INTO `updates_log` (`id`, `season_id`, `gp_id`, `table_name`, `updated_at`, `updated_by`, `action`) VALUES
(1, NULL, 19, 'gp_points', '2026-01-17 21:51:21', 1, 'create'),
(2, NULL, 19, 'gp_points', '2026-01-17 21:51:35', 1, 'create'),
(3, NULL, 19, 'gp_points', '2026-01-17 21:51:46', 1, 'create'),
(4, NULL, 19, 'gp_points', '2026-01-17 21:51:58', 1, 'create'),
(5, NULL, 19, 'gp_points', '2026-01-17 21:52:11', 1, 'create'),
(6, NULL, 20, 'gp_points', '2026-01-17 21:56:16', 1, 'create'),
(7, NULL, 20, 'gp_points', '2026-01-17 21:56:39', 1, 'create'),
(8, NULL, 20, 'gp_points', '2026-01-17 21:56:51', 1, 'create'),
(9, NULL, 20, 'gp_points', '2026-01-17 21:57:01', 1, 'create'),
(10, NULL, 21, 'gp_points', '2026-01-17 22:00:39', 1, 'create'),
(11, NULL, 21, 'gp_points', '2026-01-17 22:00:48', 1, 'create'),
(12, NULL, 21, 'gp_points', '2026-01-17 22:00:59', 1, 'create'),
(13, NULL, 21, 'gp_points', '2026-01-17 22:01:14', 1, 'create'),
(14, NULL, 22, 'gp_points', '2026-01-17 22:01:57', 1, 'create'),
(15, NULL, 22, 'gp_points', '2026-01-17 22:02:07', 1, 'create'),
(16, NULL, 22, 'gp_points', '2026-01-17 22:02:15', 1, 'create'),
(17, NULL, 22, 'gp_points', '2026-01-17 22:04:21', 1, 'create'),
(18, NULL, 22, 'gp_points', '2026-01-17 22:04:35', 1, 'create'),
(19, NULL, 22, 'gp_points', '2026-01-17 22:04:44', 1, 'create'),
(20, NULL, 23, 'gp_points', '2026-01-17 22:08:42', 1, 'create'),
(21, NULL, 23, 'gp_points', '2026-01-17 22:08:52', 1, 'create'),
(22, NULL, 23, 'gp_points', '2026-01-17 22:09:00', 1, 'create'),
(23, NULL, 23, 'gp_points', '2026-01-17 22:09:11', 1, 'create'),
(24, NULL, 23, 'gp_points', '2026-01-17 22:09:20', 1, 'create'),
(25, NULL, 23, 'gp_points', '2026-01-17 22:09:42', 1, 'create'),
(26, NULL, 23, 'gp_points', '2026-01-17 22:09:57', 1, 'create'),
(27, NULL, 23, 'gp_points', '2026-01-17 22:10:26', 1, 'create'),
(28, NULL, 24, 'gp_points', '2026-01-17 22:12:14', 1, 'create'),
(29, NULL, 24, 'gp_points', '2026-01-17 22:12:24', 1, 'create'),
(30, NULL, 24, 'gp_points', '2026-01-17 22:12:32', 1, 'create'),
(31, NULL, 24, 'gp_points', '2026-01-17 22:12:57', 1, 'create'),
(32, NULL, 24, 'gp_points', '2026-01-17 22:13:10', 1, 'create'),
(33, NULL, 24, 'gp_points', '2026-01-17 22:13:22', 1, 'create'),
(34, NULL, 24, 'gp_points', '2026-01-17 22:13:48', 1, 'create'),
(35, NULL, 24, 'gp_points', '2026-01-17 22:13:58', 1, 'create'),
(36, NULL, 25, 'gp_points', '2026-01-17 22:15:09', 1, 'create'),
(37, NULL, 25, 'gp_points', '2026-01-17 22:15:23', 1, 'create'),
(38, NULL, 25, 'gp_points', '2026-01-17 22:15:37', 1, 'create'),
(39, NULL, 25, 'gp_points', '2026-01-17 22:15:46', 1, 'create'),
(40, NULL, 25, 'gp_points', '2026-01-17 22:16:00', 1, 'create'),
(41, NULL, 25, 'gp_points', '2026-01-17 22:16:18', 1, 'create'),
(42, NULL, 26, 'gp_points', '2026-01-17 22:17:10', 1, 'create'),
(43, NULL, 26, 'gp_points', '2026-01-17 22:17:17', 1, 'create'),
(44, NULL, 26, 'gp_points', '2026-01-17 22:17:49', 1, 'create'),
(45, NULL, 26, 'gp_points', '2026-01-17 22:18:02', 1, 'create'),
(46, NULL, 26, 'gp_points', '2026-01-17 22:18:12', 1, 'create'),
(47, NULL, 26, 'gp_points', '2026-01-17 22:18:26', 1, 'create'),
(48, NULL, 26, 'gp_points', '2026-01-17 22:18:39', 1, 'create'),
(49, NULL, 27, 'gp_points', '2026-01-17 22:20:24', 1, 'create'),
(50, NULL, 27, 'gp_points', '2026-01-17 22:20:35', 1, 'create'),
(51, NULL, 27, 'gp_points', '2026-01-17 22:20:46', 1, 'create'),
(52, NULL, 27, 'gp_points', '2026-01-17 22:20:54', 1, 'create'),
(53, NULL, 27, 'gp_points', '2026-01-17 22:21:04', 1, 'create'),
(54, NULL, 27, 'gp_points', '2026-01-17 22:21:15', 1, 'create'),
(55, NULL, 27, 'gp_points', '2026-01-17 22:21:27', 1, 'create'),
(56, NULL, 27, 'gp_points', '2026-01-17 22:21:40', 1, 'create'),
(57, NULL, 28, 'gp_points', '2026-01-17 22:23:14', 1, 'create'),
(58, NULL, 28, 'gp_points', '2026-01-17 22:23:21', 1, 'create'),
(59, NULL, 28, 'gp_points', '2026-01-17 22:23:29', 1, 'create'),
(60, NULL, 28, 'gp_points', '2026-01-17 22:23:38', 1, 'create'),
(61, NULL, 28, 'gp_points', '2026-01-17 22:23:46', 1, 'create'),
(62, NULL, 28, 'gp_points', '2026-01-17 22:24:18', 1, 'create'),
(63, NULL, 28, 'gp_points', '2026-01-17 22:24:34', 1, 'create'),
(64, NULL, 28, 'gp_points', '2026-01-17 22:24:45', 1, 'create'),
(65, NULL, 29, 'gp_points', '2026-01-17 22:25:46', 1, 'create'),
(66, NULL, 29, 'gp_points', '2026-01-17 22:25:54', 1, 'create'),
(67, NULL, 29, 'gp_points', '2026-01-17 22:26:02', 1, 'create'),
(68, NULL, 29, 'gp_points', '2026-01-17 22:26:13', 1, 'create'),
(69, NULL, 29, 'gp_points', '2026-01-17 22:27:25', 1, 'create'),
(70, NULL, 29, 'gp_points', '2026-01-17 22:27:35', 1, 'create'),
(71, NULL, 29, 'gp_points', '2026-01-17 22:28:09', 1, 'create'),
(72, NULL, 29, 'gp_points', '2026-01-17 22:28:19', 1, 'create'),
(73, NULL, 29, 'gp_points', '2026-01-17 22:28:34', 1, 'create'),
(74, NULL, 29, 'gp_points', '2026-01-17 22:28:43', 1, 'create'),
(75, NULL, 30, 'gp_points', '2026-01-17 22:30:19', 1, 'create'),
(76, NULL, 30, 'gp_points', '2026-01-17 22:30:30', 1, 'create'),
(77, NULL, 30, 'gp_points', '2026-01-17 22:30:40', 1, 'create'),
(78, NULL, 30, 'gp_points', '2026-01-17 22:31:00', 1, 'create'),
(79, NULL, 30, 'gp_points', '2026-01-17 22:31:15', 1, 'create'),
(80, NULL, 30, 'gp_points', '2026-01-17 22:31:24', 1, 'create'),
(81, NULL, 30, 'gp_points', '2026-01-17 22:31:34', 1, 'create'),
(82, NULL, 30, 'gp_points', '2026-01-17 22:31:47', 1, 'create'),
(83, NULL, 30, 'gp_points', '2026-01-17 22:31:57', 1, 'create'),
(84, NULL, 31, 'gp_points', '2026-01-17 22:33:13', 1, 'create'),
(85, NULL, 31, 'gp_points', '2026-01-17 22:33:21', 1, 'create'),
(86, NULL, 31, 'gp_points', '2026-01-17 22:33:27', 1, 'create'),
(87, NULL, 31, 'gp_points', '2026-01-17 22:33:37', 1, 'create'),
(88, NULL, 31, 'gp_points', '2026-01-17 22:33:57', 1, 'create'),
(89, NULL, 31, 'gp_points', '2026-01-17 22:34:14', 1, 'create'),
(90, NULL, 31, 'gp_points', '2026-01-17 22:34:23', 1, 'create'),
(91, NULL, 32, 'gp_points', '2026-01-17 22:37:24', 1, 'create'),
(92, NULL, 32, 'gp_points', '2026-01-17 22:37:32', 1, 'create'),
(93, NULL, 32, 'gp_points', '2026-01-17 22:37:43', 1, 'create'),
(94, NULL, 32, 'gp_points', '2026-01-17 22:37:55', 1, 'create'),
(95, NULL, 32, 'gp_points', '2026-01-17 22:38:13', 1, 'create'),
(96, NULL, 32, 'gp_points', '2026-01-17 22:38:26', 1, 'create'),
(97, NULL, 32, 'gp_points', '2026-01-17 22:38:38', 1, 'create'),
(98, NULL, 32, 'gp_points', '2026-01-17 22:38:49', 1, 'create'),
(99, NULL, 32, 'gp_points', '2026-01-17 22:39:32', 1, 'create'),
(100, NULL, 33, 'gp_points', '2026-01-17 22:41:28', 1, 'create'),
(101, NULL, 33, 'gp_points', '2026-01-17 22:41:42', 1, 'create'),
(102, NULL, 33, 'gp_points', '2026-01-17 22:41:58', 1, 'create'),
(103, NULL, 33, 'gp_points', '2026-01-17 22:42:07', 1, 'create'),
(104, NULL, 33, 'gp_points', '2026-01-17 22:42:20', 1, 'create'),
(105, NULL, 33, 'gp_points', '2026-01-17 22:42:31', 1, 'create'),
(106, NULL, 33, 'gp_points', '2026-01-17 22:42:45', 1, 'create'),
(107, NULL, 33, 'gp_points', '2026-01-17 22:42:56', 1, 'create'),
(108, NULL, 33, 'gp_points', '2026-01-17 22:43:09', 1, 'create'),
(109, NULL, 34, 'gp_points', '2026-01-17 22:44:58', 1, 'create'),
(110, NULL, 34, 'gp_points', '2026-01-17 22:45:08', 1, 'create'),
(111, NULL, 34, 'gp_points', '2026-01-17 22:45:15', 1, 'create'),
(112, NULL, 34, 'gp_points', '2026-01-17 22:45:54', 1, 'create'),
(113, NULL, 34, 'gp_points', '2026-01-17 22:46:18', 1, 'create'),
(114, NULL, 34, 'gp_points', '2026-01-17 22:46:32', 1, 'create'),
(115, NULL, 34, 'gp_points', '2026-01-17 22:46:39', 1, 'create'),
(116, NULL, 35, 'gp_points', '2026-01-17 22:47:31', 1, 'create'),
(117, NULL, 35, 'gp_points', '2026-01-17 22:47:39', 1, 'create'),
(118, NULL, 35, 'gp_points', '2026-01-17 22:48:26', 1, 'create'),
(119, NULL, 35, 'gp_points', '2026-01-17 22:48:38', 1, 'create'),
(120, NULL, 35, 'gp_points', '2026-01-17 22:48:48', 1, 'create'),
(121, NULL, 35, 'gp_points', '2026-01-17 22:49:02', 1, 'create'),
(122, NULL, 35, 'gp_points', '2026-01-17 22:49:15', 1, 'create'),
(123, NULL, 35, 'gp_points', '2026-01-17 22:49:24', 1, 'create'),
(124, NULL, 35, 'gp_points', '2026-01-17 22:49:33', 1, 'create'),
(125, NULL, 36, 'gp_points', '2026-01-17 22:51:18', 1, 'create'),
(126, NULL, 36, 'gp_points', '2026-01-17 22:51:26', 1, 'create'),
(127, NULL, 36, 'gp_points', '2026-01-17 22:51:33', 1, 'create'),
(128, NULL, 36, 'gp_points', '2026-01-17 22:51:47', 1, 'create'),
(129, NULL, 36, 'gp_points', '2026-01-17 22:51:57', 1, 'create'),
(130, NULL, 36, 'gp_points', '2026-01-17 22:52:24', 1, 'create'),
(131, NULL, 36, 'gp_points', '2026-01-17 22:52:33', 1, 'create'),
(132, 2, NULL, 'manual_adjustments', '2026-01-17 22:53:47', 1, 'create'),
(133, 2, NULL, 'manual_adjustments', '2026-01-17 22:53:57', 1, 'create'),
(134, 2, NULL, 'manual_adjustments', '2026-01-17 22:54:15', 1, 'create'),
(135, 2, NULL, 'manual_adjustments', '2026-01-17 22:54:44', 1, 'create'),
(136, 2, NULL, 'manual_adjustments', '2026-01-17 22:54:56', 1, 'create'),
(137, 2, NULL, 'manual_adjustments', '2026-01-17 22:55:12', 1, 'create'),
(138, 2, NULL, 'manual_adjustments', '2026-01-17 22:55:22', 1, 'create'),
(139, 2, NULL, 'manual_adjustments', '2026-01-17 22:55:41', 1, 'create'),
(140, 2, NULL, 'manual_adjustments', '2026-01-17 22:55:49', 1, 'create'),
(141, 2, NULL, 'manual_adjustments', '2026-01-17 22:56:01', 1, 'create'),
(142, 2, NULL, 'manual_adjustments', '2026-01-17 22:56:14', 1, 'create'),
(143, NULL, 37, 'gp_points', '2026-01-17 23:12:41', 1, 'create'),
(144, NULL, 37, 'gp_points', '2026-01-17 23:12:55', 1, 'create'),
(145, NULL, 37, 'gp_points', '2026-01-17 23:13:14', 1, 'create'),
(146, NULL, 37, 'gp_points', '2026-01-17 23:13:57', 1, 'create'),
(147, NULL, 38, 'gp_points', '2026-01-17 23:14:14', 1, 'create'),
(148, NULL, 38, 'gp_points', '2026-01-17 23:14:31', 1, 'create'),
(149, NULL, 38, 'gp_points', '2026-01-17 23:14:45', 1, 'create'),
(150, NULL, 38, 'gp_points', '2026-01-17 23:14:56', 1, 'create'),
(151, NULL, 38, 'gp_points', '2026-01-17 23:15:06', 1, 'create'),
(152, NULL, 39, 'gp_points', '2026-01-17 23:15:23', 1, 'create'),
(153, NULL, 39, 'gp_points', '2026-01-17 23:15:36', 1, 'create'),
(154, NULL, 39, 'gp_points', '2026-01-17 23:15:52', 1, 'create'),
(155, NULL, 39, 'gp_points', '2026-01-17 23:16:01', 1, 'create'),
(156, NULL, 39, 'gp_points', '2026-01-17 23:16:33', 1, 'create'),
(157, NULL, 40, 'gp_points', '2026-01-17 23:16:54', 1, 'create'),
(158, NULL, 40, 'gp_points', '2026-01-17 23:17:05', 1, 'create'),
(159, NULL, 40, 'gp_points', '2026-01-17 23:17:18', 1, 'create'),
(160, NULL, 40, 'gp_points', '2026-01-17 23:17:33', 1, 'create'),
(161, NULL, 40, 'gp_points', '2026-01-17 23:17:45', 1, 'create'),
(162, NULL, 40, 'gp_points', '2026-01-17 23:17:57', 1, 'create'),
(163, NULL, 40, 'gp_points', '2026-01-17 23:18:15', 1, 'create'),
(164, NULL, 40, 'gp_points', '2026-01-17 23:18:32', 1, 'create'),
(165, NULL, 40, 'gp_points', '2026-01-17 23:18:41', 1, 'create'),
(166, NULL, 41, 'gp_points', '2026-01-17 23:19:01', 1, 'create'),
(167, NULL, 41, 'gp_points', '2026-01-17 23:19:11', 1, 'create'),
(168, NULL, 41, 'gp_points', '2026-01-17 23:19:20', 1, 'create'),
(169, NULL, 41, 'gp_points', '2026-01-17 23:19:36', 1, 'create'),
(170, NULL, 41, 'gp_points', '2026-01-17 23:19:53', 1, 'create'),
(171, NULL, 41, 'gp_points', '2026-01-17 23:20:12', 1, 'create'),
(172, NULL, 41, 'gp_points', '2026-01-17 23:20:21', 1, 'create'),
(173, NULL, 41, 'gp_points', '2026-01-17 23:22:22', 1, 'create'),
(174, 3, NULL, 'manual_adjustments', '2026-01-17 23:26:01', 1, 'create'),
(175, 3, NULL, 'manual_adjustments', '2026-01-17 23:26:18', 1, 'create'),
(176, 3, NULL, 'manual_adjustments', '2026-01-17 23:26:28', 1, 'create'),
(177, 3, NULL, 'manual_adjustments', '2026-01-17 23:26:44', 1, 'create'),
(178, 3, NULL, 'manual_adjustments', '2026-01-17 23:27:01', 1, 'create'),
(179, 3, NULL, 'manual_adjustments', '2026-01-17 23:27:19', 1, 'create'),
(180, 3, NULL, 'manual_adjustments', '2026-01-17 23:27:36', 1, 'create'),
(181, 3, NULL, 'manual_adjustments', '2026-01-17 23:27:52', 1, 'create'),
(182, 3, NULL, 'manual_adjustments', '2026-01-17 23:28:07', 1, 'create'),
(183, NULL, 41, 'gp_points', '2026-01-17 23:29:41', 1, 'create'),
(184, NULL, 41, 'penalties', '2026-01-17 23:31:23', 1, 'create'),
(185, NULL, 41, 'penalties', '2026-01-17 23:32:00', 1, 'create'),
(186, NULL, 41, 'penalties', '2026-01-17 23:35:10', 1, 'create'),
(187, 3, NULL, 'manual_adjustments', '2026-01-17 23:35:38', 1, 'update'),
(188, 3, NULL, 'manual_adjustments', '2026-01-17 23:36:00', 1, 'update');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `circuits`
--
ALTER TABLE `circuits`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `gp`
--
ALTER TABLE `gp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `gp_points`
--
ALTER TABLE `gp_points`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;

--
-- AUTO_INCREMENT pour la table `manual_adjustments`
--
ALTER TABLE `manual_adjustments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `penalties`
--
ALTER TABLE `penalties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `teams_drivers`
--
ALTER TABLE `teams_drivers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT pour la table `updates_log`
--
ALTER TABLE `updates_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
