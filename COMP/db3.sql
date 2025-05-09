CREATE DATABASE IF NOT EXISTS devweb;
USE devweb;


-- Table d’historique (peut dépendre d’autres tables mais aucune ne dépend d’elle)
DROP TABLE IF EXISTS historique;

-- Tables indépendantes
DROP TABLE IF EXISTS salle;
DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS demandes;
DROP TABLE IF EXISTS platform_rules;


CREATE TABLE utilisateur(
id_user int AUTO_INCREMENT primary key,
pseudonyme varchar(15),
age int,
gender varchar(10),
dateNaissance datetime,
avatar varchar(100),
email varchar(20),
last_name varchar(15),
first_name varchar(15),
mot_de_passe varchar(30),
points int,
statut varchar(30),
code_validation VARCHAR(10) DEFAULT NULL,
verifie BOOLEAN DEFAULT FALSE,  -- simple intermediaire avancé administrateur
demande_validee BOOLEAN DEFAULT FALSE,
role varchar(15) ); -- adulte enfant voisin adolescent ...

CREATE TABLE IF NOT EXISTS historique (
    id INT AUTO_INCREMENT PRIMARY KEY,
    timestamp DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    timestamp2 DATETIME DEFAULT NULL, -- pour la fin d’une action, si applicable
    id_objet VARCHAR(100) NOT NULL,   -- ex: "tv-1728444029"
    type_objet VARCHAR(50) NOT NULL,  -- ex: "tv", "lumiere", "chauffage"
    action TEXT NOT NULL,             -- ex: "volume => 75", "température réglée"
    valeur INT DEFAULT NULL,          -- valeur numérique si applicable
    id_user INT DEFAULT NULL,  -- clé étrangère vers `utilisateur.id_user`
    FOREIGN KEY (id_user) REFERENCES utilisateur(id_user) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS platform_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    object_name VARCHAR(255) NOT NULL,
    points_per_hour INT NOT NULL,
    required_role VARCHAR(255) NOT NULL
);


CREATE TABLE IF NOT EXISTS demandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pseudonyme VARCHAR(100),
    age INT,
    gender VARCHAR(20),
    rs VARCHAR(255),
    email VARCHAR(255),
    last_name VARCHAR(100),
    first_name VARCHAR(100),
    date_demande TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS salle (
nom_salle varchar(15) primary key);






INSERT INTO utilisateur(pseudonyme, email, mot_de_passe, verifie, demande_validee, code_validation) values ('nael9', '6ss@zz.com', 'abcd', 0, 0, 123456);
INSERT INTO utilisateur(pseudonyme, email, mot_de_passe, verifie, demande_validee, code_validation, statut, role) values ('root', 'root@root1', 'root', 1, 1, 123456, admin, adulte);


DROP TABLE IF EXISTS `console`;
CREATE TABLE IF NOT EXISTS `console` (id_console INT PRIMARY KEY AUTO_INCREMENT, `volume` FLOAT, `source` FLOAT, `conso_en` FLOAT, `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `fonctionnement` BOOLEAN);

DROP TABLE IF EXISTS `tv`;
CREATE TABLE IF NOT EXISTS `tv` (id_tv INT PRIMARY KEY AUTO_INCREMENT, `volume` FLOAT, `source` FLOAT, `couleurs` VARCHAR(255), `conso_en` FLOAT, `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `fonctionnement` BOOLEAN);

DROP TABLE IF EXISTS `eceinte`;
CREATE TABLE IF NOT EXISTS `eceinte` (id_eceinte INT PRIMARY KEY AUTO_INCREMENT, `controle_parental` BOOLEAN, `volume` FLOAT, `jeu` VARCHAR(255), `conso_en` FLOAT, `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `fonctionnement` BOOLEAN);

DROP TABLE IF EXISTS `seche_linge`;
CREATE TABLE IF NOT EXISTS `seche_linge` (id_seche_linge INT PRIMARY KEY AUTO_INCREMENT, `mode_eco` BOOLEAN, `mode_fragile` BOOLEAN, `couleur` VARCHAR(255), `conso_en` FLOAT, `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `fonctionnement` BOOLEAN);

DROP TABLE IF EXISTS `lave_vaisselle`;
CREATE TABLE IF NOT EXISTS `lave_vaisselle` (id_lave_vaisselle INT PRIMARY KEY AUTO_INCREMENT, `mode_eco` BOOLEAN, `temperature` FLOAT, `conso_en` FLOAT, `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `fonctionnement` BOOLEAN);

DROP TABLE IF EXISTS `chauffage`;
CREATE TABLE IF NOT EXISTS `chauffage` (id_chauffage INT PRIMARY KEY AUTO_INCREMENT, `temperature` FLOAT, `mode_eco` BOOLEAN, `conso_en` FLOAT, `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `fonctionnement` BOOLEAN);

DROP TABLE IF EXISTS `tondeuse`;
CREATE TABLE IF NOT EXISTS `tondeuse` (id_tondeuse INT PRIMARY KEY AUTO_INCREMENT, `coupe fine` BOOLEAN, `coupe large` BOOLEAN, `mode animaux` BOOLEAN, `conso_en` FLOAT, `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `fonctionnement` BOOLEAN);

DROP TABLE IF EXISTS `eclairage`;
CREATE TABLE IF NOT EXISTS `eclairage` (id_eclairage INT PRIMARY KEY AUTO_INCREMENT, `couleur` VARCHAR(255), `mode nocturne` BOOLEAN, `intensité` FLOAT, `conso_en` FLOAT, `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `fonctionnement` BOOLEAN);

DROP TABLE IF EXISTS `arrosage`;
CREATE TABLE IF NOT EXISTS `arrosage` (id_arrosage INT PRIMARY KEY AUTO_INCREMENT, `quantite_eau` FLOAT, `mode rotatif` VARCHAR(255), `mode stable` VARCHAR(255), `conso_en` FLOAT, `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `fonctionnement` BOOLEAN);


--PAS OUBLIER DE DEFINIR LES REGLES 



-- POUR USER
INSERT INTO utilisateur(pseudonyme, email, mot_de_passe, verifie, demande_validee, code_validation) values ('nael9', '6ss@zz.com', 'abcd', 0, 0, 123456);
INSERT INTO utilisateur(pseudonyme, email, mot_de_passe, verifie, demande_validee, code_validation, statut, role) values ('root', 'root@root1', 'root', 1, 1, 123456, admin, adulte);


--POUR OBJ
-- Console
INSERT INTO console (volume, source, conso_en, nom, id_connection, nom_salle, fonctionnement) VALUES
(45, 1, 22.5, 'PlayStation 5', 'conn-001', 'salon', TRUE),
(30, 2, 18.0, 'Xbox Series X', 'conn-002', 'salon', TRUE);

-- TV
INSERT INTO tv (volume, source, couleurs, conso_en, nom, id_connection, nom_salle, fonctionnement) VALUES
(20, 1, 'Vif', 45.0, 'Samsung SmartTV', 'conn-003', 'salon', TRUE),
(35, 3, 'Froid', 60.0, 'LG OLED', 'conn-004', 'salon', FALSE);

-- Eceinte
INSERT INTO eceinte (controle_parental, volume, jeu, conso_en, nom, id_connection, nom_salle, fonctionnement) VALUES
(TRUE, 70, 'Chill Playlist', 15.0, 'Bose SoundTouch', 'conn-005', 'cuisine', TRUE),
(FALSE, 55, 'Jeu Ambiance', 12.0, 'Google Home', 'conn-006', 'salon', TRUE);

-- Sèche-linge
INSERT INTO seche_linge (mode_eco, mode_fragile, couleur, conso_en, nom, id_connection, nom_salle, fonctionnement) VALUES
(TRUE, FALSE, 'Blanc', 75.0, 'Bosch EcoDry', 'conn-007', 'buanderie', TRUE),
(FALSE, TRUE, 'Gris', 90.0, 'Samsung SoftDry', 'conn-008', 'buanderie', TRUE);

-- Chauffage
INSERT INTO chauffage (temperature, mode_eco, conso_en, nom, id_connection, nom_salle, fonctionnement) VALUES
(21.5, TRUE, 100.0, 'Radiateur Intelliterm', 'conn-011', 'chambre', TRUE),
(24.0, FALSE, 130.0, 'Chauffage Bain d’huile', 'conn-012', 'salon', TRUE);

-- Arrosage
INSERT INTO arrosage (quantite_eau, `mode rotatif`, `mode stable`, conso_en, nom, id_connection, nom_salle, fonctionnement) VALUES
(12.5, 'activé', 'désactivé', 25.0, 'Arrosoir Automatique A1', 'conn-017', 'jardin', TRUE),
(9.0, 'désactivé', 'activé', 18.0, 'Sprinkler GreenTech', 'conn-018', 'jardin', FALSE);

-- Lave-vaisselle (1 seul)
INSERT INTO lave_vaisselle (mode_eco, temperature, conso_en, nom, id_connection, nom_salle, fonctionnement) VALUES
(TRUE, 50, 65.0, 'Whirlpool Silence', 'conn-009', 'cuisine', TRUE);

-- Tondeuse (1 seul)
INSERT INTO tondeuse (`coupe fine`, `coupe large`, `mode animaux`, conso_en, nom, id_connection, nom_salle, fonctionnement) VALUES
(TRUE, FALSE, FALSE, 110.0, 'Tondeuse Husqvarna', 'conn-013', 'jardin', TRUE);

-- Éclairage (1 seul)
INSERT INTO eclairage (couleur, `mode nocturne`, `intensité`, conso_en, nom, id_connection, nom_salle, fonctionnement) VALUES
('Blanc chaud', TRUE, 70.0, 8.5, 'Lampe Connectée IKEA', 'conn-015', 'salon', TRUE);





-- POUR HISTO

-- Exemple : données pour 3 utilisateurs (1, 2, 3) sur 5 jours
INSERT INTO historique (timestamp, timestamp2, id_objet, type_objet, action, valeur, id_user) VALUES
-- Console
('2025-05-01 10:00:00', NULL, 'console', 'console', 'allumée', 20, 1),
('2025-05-02 12:00:00', NULL, 'console', 'console', 'jeu lancé', 30, 2),
('2025-05-03 14:00:00', NULL, 'console', 'console', 'mise en veille', 5, 3),

-- TV
('2025-05-01 08:00:00', NULL, 'tv', 'tv', 'volume => 15', 15, 1),
('2025-05-02 19:00:00', NULL, 'tv', 'tv', 'chaîne changée', 8, 2),
('2025-05-03 21:00:00', NULL, 'tv', 'tv', 'éteinte', 2, 3),

-- Enceinte
('2025-05-01 09:00:00', NULL, 'enceinte', 'enceinte', 'lecture musique', 10, 1),
('2025-05-02 11:30:00', NULL, 'enceinte', 'enceinte', 'volume => 60', 25, 2),

-- Sèche-linge
('2025-05-03 15:00:00', NULL, 'seche_linge', 'seche_linge', 'cycle démarré', 50, 3),
('2025-05-04 16:00:00', NULL, 'seche_linge', 'seche_linge', 'cycle terminé', 40, 1),

-- Lave-vaisselle
('2025-05-01 20:00:00', NULL, 'lave_vaiselle', 'lave_vaiselle', 'lavage', 60, 2),
('2025-05-03 22:00:00', NULL, 'lave_vaiselle', 'lave_vaiselle', 'rinçage', 30, 3),

-- Chauffage
('2025-05-01 07:00:00', NULL, 'chauffage', 'chauffage', 'température réglée => 21', 21, 1),
('2025-05-02 07:30:00', NULL, 'chauffage', 'chauffage', 'température réglée => 19', 19, 2),
('2025-05-05 08:00:00', NULL, 'chauffage', 'chauffage', 'mode éco activé', 10, 3),

-- Tondeuse
('2025-05-02 10:00:00', NULL, 'tondeuse', 'tondeuse', 'tonte démarrée', 35, 1),
('2025-05-04 12:00:00', NULL, 'tondeuse', 'tondeuse', 'tonte arrêtée', 15, 2),

-- Éclairage
('2025-05-01 19:00:00', NULL, 'eclairage', 'eclairage', 'lumière allumée', 5, 1),
('2025-05-03 23:00:00', NULL, 'eclairage', 'eclairage', 'lumière éteinte', 2, 3),

-- Arrosage
('2025-05-01 06:00:00', NULL, 'arrosage', 'arrosage', 'début arrosage', 12, 1),
('2025-05-04 06:00:00', NULL, 'arrosage', 'arrosage', 'fin arrosage', 10, 2);
