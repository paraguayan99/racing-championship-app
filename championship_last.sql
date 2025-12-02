-- --------------------------------------------------------
-- Création et sélection de la base de données
-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS championship_last
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE championship_last;

-- --------------------------------------------------------
-- Nettoyage
-- --------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS updates_log;
DROP TABLE IF EXISTS gp_points;
DROP TABLE IF EXISTS gp_stats;
DROP TABLE IF EXISTS penalties;
DROP TABLE IF EXISTS manual_adjustments;
DROP TABLE IF EXISTS teams_drivers;
DROP TABLE IF EXISTS gp;
DROP TABLE IF EXISTS drivers;
DROP TABLE IF EXISTS teams;
DROP TABLE IF EXISTS circuits;
DROP TABLE IF EXISTS seasons;
DROP TABLE IF EXISTS countries;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- roles
-- pour déterminer les accès/autorisations au dashboard
-- --------------------------------------------------------
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO roles (id, name) VALUES
(1, 'Administrateur'),
(2, 'Moderateur'),
(3, 'Utilisateur');

-- --------------------------------------------------------
-- users
-- pour créer des profils pour chaque personne qui mettra à jour la BDD et voir qui est actif
-- --------------------------------------------------------
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(191) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role_id INT NOT NULL,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (id, email, password_hash, role_id) VALUES
(1, 'paraguayan99@laposte.net', '$2y$10$jI3l3bZc92tGCe.OqMbVWuu/diVN7AlMHhxkEWUKR8BxgY3ardwYC', 1);

-- --------------------------------------------------------
-- categories
-- --------------------------------------------------------
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE,
  status ENUM('active','desactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categories (id, name) VALUES
(1, 'F1'),
(2, 'F2'),
(3, 'F3'),
(4, 'Project CARS'),
(5, 'Gran Turismo');

-- --------------------------------------------------------
-- countries
-- Table centralisée pour gérer les pays et leurs drapeaux
-- --------------------------------------------------------
CREATE TABLE countries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,   -- nom du pays
    code CHAR(3) DEFAULT NULL UNIQUE,    -- code à 3 lettres (ex: FRA, ITA)
    flag VARCHAR(255) DEFAULT NULL       -- URL ou chemin vers le drapeau
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO countries (id, name, code, flag)
VALUES (1, 'France', 'FRA', NULL);

-- --------------------------------------------------------
-- seasons
-- --------------------------------------------------------
CREATE TABLE seasons (
  id INT NOT NULL AUTO_INCREMENT,
  season_number INT NOT NULL,
  category_id INT NOT NULL,
  videogame VARCHAR(100) NOT NULL,
  status ENUM('active','desactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (id),
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CREATION D'UNE CONTRAINTE d'UNICITE SEASON season_number + category_id UNIQUE
ALTER TABLE seasons
ADD CONSTRAINT unique_season_category UNIQUE (season_number, category_id);
-- AJOUT DE LA COLONNE PLATEFORME POUR INDIQUER PS3, PS4 ...
ALTER TABLE seasons
ADD COLUMN platform VARCHAR(100) NOT NULL AFTER videogame;

-- --------------------------------------------------------
-- circuits
-- --------------------------------------------------------
CREATE TABLE circuits (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  country_id INT NOT NULL,
  status ENUM('active','desactive') DEFAULT 'active',
  FOREIGN KEY (country_id) REFERENCES countries(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- teams
-- --------------------------------------------------------
CREATE TABLE teams (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  logo VARCHAR(255),
  country_id INT NOT NULL,
  status ENUM('active','desactive') DEFAULT 'active',
  FOREIGN KEY (country_id) REFERENCES countries(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- drivers
-- --------------------------------------------------------
CREATE TABLE drivers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nickname VARCHAR(100) NOT NULL UNIQUE,
  country_id INT NOT NULL DEFAULT 1,  -- 1 = France
  status ENUM('active','desactive') DEFAULT 'active',
  FOREIGN KEY (country_id) REFERENCES countries(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- gp (Grands Prix)
-- pour ajouter GP dans une saison et créer le calendrier
-- --------------------------------------------------------
CREATE TABLE gp (
  id INT AUTO_INCREMENT PRIMARY KEY,
  season_id INT NOT NULL,
  circuit_id INT NOT NULL,
  gp_ordre INT NOT NULL, -- numéro du GP dans la saison
  FOREIGN KEY (season_id) REFERENCES seasons(id) ON DELETE CASCADE,
  FOREIGN KEY (circuit_id) REFERENCES circuits(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- teams_drivers (affectation pilote → écurie → saison)
-- pour associer pilote à une écurie dans le classement 
-- sans affecter calcul du championnat constructeur
-- --------------------------------------------------------
CREATE TABLE teams_drivers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  season_id INT NOT NULL,
  driver_id INT NOT NULL,
  team_id INT NOT NULL,
  FOREIGN KEY (season_id) REFERENCES seasons(id) ON DELETE CASCADE,
  FOREIGN KEY (driver_id) REFERENCES drivers(id),
  FOREIGN KEY (team_id) REFERENCES teams(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- gp_points (résultats des GP)
-- Un insert par pilotes (et illimité)
-- --------------------------------------------------------
CREATE TABLE gp_points (
  id INT AUTO_INCREMENT PRIMARY KEY,
  gp_id INT NOT NULL,
  driver_id INT NOT NULL,
  team_id INT NOT NULL,
  position INT,
  points_numeric INT DEFAULT 0,
  points_text VARCHAR(50),   -- si DNF, DNS, DSQ et donc pas de position
  FOREIGN KEY (gp_id) REFERENCES gp(id) ON DELETE CASCADE,
  FOREIGN KEY (driver_id) REFERENCES drivers(id),
  FOREIGN KEY (team_id) REFERENCES teams(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- gp_stats (résultats des GP)
-- Un insert par GP pour les poles et fastest lap
-- --------------------------------------------------------

CREATE TABLE gp_stats (
  gp_id INT PRIMARY KEY,
  pole_position_driver INT,
  pole_position_time VARCHAR(50),
  fastest_lap_driver INT,
  fastest_lap_time VARCHAR(50),
  FOREIGN KEY (gp_id) REFERENCES gp(id) ON DELETE CASCADE,
  FOREIGN KEY (pole_position_driver) REFERENCES drivers(id),
  FOREIGN KEY (fastest_lap_driver) REFERENCES drivers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- penalties
-- --------------------------------------------------------
CREATE TABLE penalties (
  id INT AUTO_INCREMENT PRIMARY KEY,
  gp_id INT NOT NULL,
  driver_id INT NOT NULL,
  points_removed INT NOT NULL,
  comment TEXT,
  FOREIGN KEY (gp_id) REFERENCES gp(id) ON DELETE CASCADE,
  FOREIGN KEY (driver_id) REFERENCES drivers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- manual_adjustments
-- --------------------------------------------------------
CREATE TABLE manual_adjustments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  season_id INT NOT NULL,
  driver_id INT,
  team_id INT,
  points INT NOT NULL,
  comment TEXT,
  FOREIGN KEY (season_id) REFERENCES seasons(id) ON DELETE CASCADE,
  FOREIGN KEY (driver_id) REFERENCES drivers(id),
  FOREIGN KEY (team_id) REFERENCES teams(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- updates_log
-- Enregistre l'historique des mises à jour
-- Peut être lié soit à une saison, soit à un GP
-- possibilité d'afficher tout l'historique des mises à jour
-- possibilité d'afficher la dernière mise à jour
-- --------------------------------------------------------
CREATE TABLE updates_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    season_id INT DEFAULT NULL, -- Si la modification concerne toute la saison
    gp_id INT DEFAULT NULL,     -- Si la modification concerne un GP spécifique
    table_name VARCHAR(50) NOT NULL, -- 'gp_points', 'gp_stats', 'penalties', 'manual_adjustments'
    updated_at DATETIME NOT NULL,
    updated_by INT NOT NULL,    -- utilisateur qui a effectué la modification
    FOREIGN KEY (season_id) REFERENCES seasons(id) ON DELETE CASCADE,
    FOREIGN KEY (gp_id) REFERENCES gp(id) ON DELETE CASCADE,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE CASCADE,
    CHECK (season_id IS NOT NULL OR gp_id IS NOT NULL) -- au moins une des deux doit être renseignée
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------
-- Indexes optimisés pour toutes les tables
-- --------------------------------------------------------

-- seasons
ALTER TABLE seasons
ADD INDEX idx_fk_category (category_id),
ADD INDEX idx_season_status (status);

-- circuits
ALTER TABLE circuits
ADD INDEX idx_circuit_status (status),
ADD INDEX idx_circuit_country (country_id);

-- teams
ALTER TABLE teams
ADD INDEX idx_team_status (status),
ADD INDEX idx_team_country (country_id);

-- drivers
ALTER TABLE drivers
ADD INDEX idx_driver_status (status),
ADD INDEX idx_driver_country (country_id);

-- gp
ALTER TABLE gp
ADD INDEX idx_gp_season (season_id),
ADD INDEX idx_gp_circuit (circuit_id),
ADD INDEX idx_gp_season_ordre (season_id, gp_ordre);

-- teams_drivers
ALTER TABLE teams_drivers
ADD INDEX idx_td_season (season_id),
ADD INDEX idx_td_driver (driver_id),
ADD INDEX idx_td_team (team_id),
ADD UNIQUE INDEX idx_td_unique (season_id, driver_id, team_id);

-- gp_points
ALTER TABLE gp_points
ADD INDEX idx_points_gp (gp_id),
ADD INDEX idx_points_driver (driver_id),
ADD INDEX idx_points_team (team_id),
ADD INDEX idx_points_gp_driver (gp_id, driver_id),
ADD INDEX idx_points_driver_team (driver_id, team_id);

-- gp_stats
ALTER TABLE gp_stats
ADD INDEX idx_stats_pole_driver (pole_position_driver),
ADD INDEX idx_stats_fastest_driver (fastest_lap_driver);

-- penalties
ALTER TABLE penalties
ADD INDEX idx_pen_gp (gp_id),
ADD INDEX idx_pen_driver (driver_id),
ADD INDEX idx_pen_gp_driver (gp_id, driver_id);

-- manual_adjustments
ALTER TABLE manual_adjustments
ADD INDEX idx_ma_season (season_id),
ADD INDEX idx_ma_driver (driver_id),
ADD INDEX idx_ma_team (team_id),
ADD INDEX idx_ma_driver_team (driver_id, team_id);

-- updates_log
ALTER TABLE updates_log
ADD INDEX idx_ul_season (season_id),
ADD INDEX idx_ul_gp (gp_id),
ADD INDEX idx_ul_updated_by (updated_by),
ADD INDEX idx_ul_season_table (season_id, table_name),
ADD INDEX idx_ul_gp_table (gp_id, table_name);






-- --------------------------------------------------------
-- DROPPER LES VUES EXISTANTES
-- --------------------------------------------------------
DROP VIEW IF EXISTS drivers_standings;
DROP VIEW IF EXISTS teams_standings;
DROP VIEW IF EXISTS gp_stats_summary;
DROP VIEW IF EXISTS driver_gp_counts;
DROP VIEW IF EXISTS driver_points_all_seasons;
DROP VIEW IF EXISTS team_points_all_seasons;
DROP VIEW IF EXISTS driver_awards;
DROP VIEW IF EXISTS team_awards;

-- --------------------------------------------------------
-- DRIVERS_STANDINGS
-- Classement pilotes par saison
-- --------------------------------------------------------
DROP VIEW IF EXISTS drivers_standings;

CREATE VIEW drivers_standings AS
SELECT
    s.id AS season_id,
    s.season_number,
    c.name AS category,
    d.id AS driver_id,
    d.nickname,
    -- Total points : points des GP + ajustements manuels - pénalités
    COALESCE(SUM(gp_pts.points_numeric), 0)
      + COALESCE(SUM(ma.points), 0)
      - COALESCE(SUM(p.points_removed), 0) AS total_points,
    -- Nombre de victoires
    SUM(CASE WHEN gp_pts.position = 1 THEN 1 ELSE 0 END) AS wins,
    -- Nombre de podiums
    SUM(CASE WHEN gp_pts.position IN (1,2,3) THEN 1 ELSE 0 END) AS podiums
FROM seasons s
JOIN categories c ON s.category_id = c.id
JOIN gp g ON g.season_id = s.id
JOIN gp_points gp_pts ON gp_pts.gp_id = g.id
JOIN drivers d ON d.id = gp_pts.driver_id
-- Ajustements manuels éventuels
LEFT JOIN manual_adjustments ma 
    ON ma.season_id = s.id AND ma.driver_id = d.id
-- Pénalités éventuelles
LEFT JOIN penalties p 
    ON p.driver_id = d.id AND p.gp_id = g.id
GROUP BY s.id, s.season_number, c.name, d.id, d.nickname;

-- --------------------------------------------------------
-- TEAMS_STANDINGS
-- Classement des équipes par saison
-- Prend en compte : 
--   - Les points gagnés par les pilotes pour chaque GP selon l'équipe pour ce GP
--   - Les ajustements manuels de points liés à l'équipe
--   - Les pénalités des pilotes (indépendamment de l'équipe)
-- --------------------------------------------------------
DROP VIEW IF EXISTS teams_standings;

CREATE VIEW teams_standings AS
SELECT
    s.id AS season_id,                   -- ID de la saison
    s.season_number,                     -- Numéro de la saison
    c.name AS category,                  -- Catégorie (F1, F2, etc.)
    t.id AS team_id,                      -- ID de l'équipe
    t.name AS team_name,                  -- Nom de l'équipe
    -- Total des points de l'équipe = points des GP + ajustements manuels - pénalités
    COALESCE(SUM(gp_pts.points_numeric), 0)
      + COALESCE(SUM(ma.points), 0)
      - COALESCE(SUM(p.points_removed), 0) AS total_points,
    -- Nombre de victoires de l'équipe (position 1 de chaque GP)
    SUM(CASE WHEN gp_pts.position = 1 THEN 1 ELSE 0 END) AS wins,
    -- Nombre de podiums de l'équipe (positions 1,2,3 de chaque GP)
    SUM(CASE WHEN gp_pts.position IN (1,2,3) THEN 1 ELSE 0 END) AS podiums
FROM seasons s
-- On récupère la catégorie de la saison
JOIN categories c ON s.category_id = c.id
-- On parcourt toutes les équipes
JOIN teams t
-- On récupère les pilotes associés à chaque équipe pour cette saison
JOIN teams_drivers td 
  ON td.team_id = t.id 
  AND td.season_id = s.id
JOIN drivers d 
  ON d.id = td.driver_id
-- On relie aux GP de la saison
JOIN gp g 
  ON g.season_id = s.id
-- On ne prend que les points obtenus par ce pilote pour cette équipe sur ce GP précis
JOIN gp_points gp_pts 
  ON gp_pts.driver_id = d.id 
  AND gp_pts.gp_id = g.id 
  AND gp_pts.team_id = t.id
-- Ajustements manuels spécifiques à cette équipe et pilote pour la saison
LEFT JOIN manual_adjustments ma 
  ON ma.season_id = s.id 
  AND ma.driver_id = d.id 
  AND ma.team_id = t.id
-- Pénalités appliquées au pilote pour ce GP
LEFT JOIN penalties p 
  ON p.driver_id = d.id 
  AND p.gp_id = g.id
-- On regroupe par saison et équipe pour calculer les totaux
GROUP BY s.id, s.season_number, c.name, t.id, t.name;

-- --------------------------------------------------------
-- GP_STATS_SUMMARY
-- Meilleurs chronos et statistiques par circuit et par saison
-- --------------------------------------------------------
CREATE VIEW gp_stats_summary AS
SELECT
    g.id AS gp_id,
    g.season_id,
    s.season_number,
    c.name AS category,
    ci.name AS circuit_name,
    gs.pole_position_driver,
    dp.nickname AS pole_driver_name,
    gs.pole_position_time,
    gs.fastest_lap_driver,
    df.nickname AS fastest_driver_name,
    gs.fastest_lap_time
FROM gp g
JOIN seasons s ON s.id = g.season_id
JOIN categories c ON c.id = s.category_id
JOIN circuits ci ON ci.id = g.circuit_id
LEFT JOIN gp_stats gs ON gs.gp_id = g.id
LEFT JOIN drivers dp ON dp.id = gs.pole_position_driver
LEFT JOIN drivers df ON df.id = gs.fastest_lap_driver;

-- --------------------------------------------------------
-- DRIVER GP COUNTS
-- Nombre de GP disputés par pilote sur toutes les saisons
-- --------------------------------------------------------
CREATE VIEW driver_gp_counts AS
SELECT
    d.id AS driver_id,
    d.nickname,
    COUNT(DISTINCT gp_pts.gp_id) AS total_gp
FROM drivers d
LEFT JOIN gp_points gp_pts ON gp_pts.driver_id = d.id
GROUP BY d.id, d.nickname;

-- --------------------------------------------------------
-- DRIVER POINTS ALL SEASONS
-- Points cumulés par pilote sur toutes les saisons
-- --------------------------------------------------------
CREATE VIEW driver_points_all_seasons AS
SELECT
    d.id AS driver_id,
    d.nickname,
    COALESCE(SUM(gp_pts.points_numeric),0)
      + COALESCE(SUM(ma.points),0)
      - COALESCE(SUM(p.points_removed),0) AS total_points
FROM drivers d
LEFT JOIN gp_points gp_pts ON gp_pts.driver_id = d.id
LEFT JOIN gp g ON g.id = gp_pts.gp_id
LEFT JOIN manual_adjustments ma ON ma.driver_id = d.id AND ma.season_id = g.season_id
LEFT JOIN penalties p ON p.driver_id = d.id AND p.gp_id = g.id
GROUP BY d.id, d.nickname;

-- --------------------------------------------------------
-- TEAM POINTS ALL SEASONS
-- Points cumulés par équipe sur toutes les saisons
-- --------------------------------------------------------
CREATE VIEW team_points_all_seasons AS
SELECT
    t.id AS team_id,
    t.name AS team_name,
    COALESCE(SUM(gp_pts.points_numeric),0)
      + COALESCE(SUM(ma.points),0)
      - COALESCE(SUM(p.points_removed),0) AS total_points
FROM teams t
JOIN teams_drivers td ON td.team_id = t.id
JOIN drivers d ON d.id = td.driver_id
JOIN gp_points gp_pts ON gp_pts.driver_id = d.id AND gp_pts.team_id = t.id
JOIN gp g ON g.id = gp_pts.gp_id AND g.season_id = td.season_id
LEFT JOIN manual_adjustments ma ON ma.team_id = t.id AND ma.driver_id = d.id AND ma.season_id = g.season_id
LEFT JOIN penalties p ON p.driver_id = d.id AND p.gp_id = g.id
GROUP BY t.id, t.name;

-- --------------------------------------------------------
-- DRIVER AWARDS
-- Titres, Vice-champions, 3ème place par catégorie sur toutes saisons
-- --------------------------------------------------------
CREATE VIEW driver_awards AS
WITH season_ranking AS (
    SELECT 
        ds.season_id,
        ds.category,
        ds.driver_id,
        ds.nickname,
        ds.total_points,
        RANK() OVER (PARTITION BY ds.season_id ORDER BY ds.total_points DESC) AS rank_season
    FROM drivers_standings ds
)
SELECT
    driver_id,
    nickname,
    category,
    SUM(CASE WHEN rank_season = 1 THEN 1 ELSE 0 END) AS titles,
    SUM(CASE WHEN rank_season = 2 THEN 1 ELSE 0 END) AS vice_titles,
    SUM(CASE WHEN rank_season = 3 THEN 1 ELSE 0 END) AS third_place
FROM season_ranking
GROUP BY driver_id, nickname, category;

-- --------------------------------------------------------
-- TEAM AWARDS
-- Titres, Vice-champions, 3ème place par catégorie sur toutes saisons
-- --------------------------------------------------------
CREATE VIEW team_awards AS
WITH season_ranking AS (
    SELECT 
        ts.season_id,
        ts.category,
        ts.team_id,
        ts.team_name,
        ts.total_points,
        RANK() OVER (PARTITION BY ts.season_id ORDER BY ts.total_points DESC) AS rank_season
    FROM teams_standings ts
)
SELECT
    team_id,
    team_name,
    category,
    SUM(CASE WHEN rank_season = 1 THEN 1 ELSE 0 END) AS titles,
    SUM(CASE WHEN rank_season = 2 THEN 1 ELSE 0 END) AS vice_titles,
    SUM(CASE WHEN rank_season = 3 THEN 1 ELSE 0 END) AS third_place
FROM season_ranking
GROUP BY team_id, team_name, category;
