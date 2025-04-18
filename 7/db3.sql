CREATE DATABASE IF NOT EXISTS devweb;
USE devweb;


-- Tables dépendantes (avec FOREIGN KEY)
DROP TABLE IF EXISTS lumiere_connectee;
DROP TABLE IF EXISTS chauffage_connecte;
DROP TABLE IF EXISTS tv_connectee;
DROP TABLE IF EXISTS assistant_voix;
DROP TABLE IF EXISTS enceinte_connectee;
DROP TABLE IF EXISTS gestion_eau;
DROP TABLE IF EXISTS gestion_chauffage;
DROP TABLE IF EXISTS ouverture_portes;
DROP TABLE IF EXISTS machine_laver;
DROP TABLE IF EXISTS seche_linge;
DROP TABLE IF EXISTS lave_vaisselle;
DROP TABLE IF EXISTS aspirateur;
DROP TABLE IF EXISTS tondeuse;
DROP TABLE IF EXISTS plaque_chauffante;
DROP TABLE IF EXISTS historique;
DROP TABLE IF EXISTS projecteur;

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





SHOW COLUMNS FROM utilisateur LIKE 'verifie';

SELECT * FROM utilisateur;
SELECT * FROM historique;
SHOW TABLES FROM devweb;
SELECT * FROM salle;
INSERT INTO salle(nom_salle) values ('salmanger');
INSERT INTO salle(nom_salle) values ('salon');
INSERT INTO salle(nom_salle) values ('cuisine');
-- INSERT INTO aspirateur(programme, id_connection,temps_restant, fonctionnement, nom_salle) values ('eco', 'ab23cd98', 10, FALSE, 'salon');
-- INSERT INTO plaque_chauffante(programme, temps_restant, fonctionnement, nom_salle) values ('eco', 10, FALSE, 'salmanger');
INSERT INTO utilisateur(pseudonyme, email, mot_de_passe, verifie, demande_validee, code_validation) values ('nael9', '6ss@zz.com', 'abcd', 0, 0, 123456);
SELECT * FROM aspirateur;
SELECT * FROM lave_vaisselle;
SELECT * FROM projecteur;
SELECT * FROM seche_linge;
SELECT * FROM console;
DROP TABLE IF EXISTS `console`;
DROP TABLE IF EXISTS `seche_linge`;
CREATE TABLE IF NOT EXISTS `seche_linge` (id_seche_linge INT PRIMARY KEY AUTO_INCREMENT, `temperature` FLOAT, `eco` FLOAT, `fragile` BOOLEAN, `programme` BOOLEAN, `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `fonctionnement` BOOLEAN);

DROP TABLE IF EXISTS `console`;
CREATE TABLE IF NOT EXISTS `console` (id_console INT PRIMARY KEY AUTO_INCREMENT, `volume` FLOAT, `par` BOOLEAN, `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `fonctionnement` BOOLEAN, `conso_en` FLOAT);

UPDATE utilisateur SET points=200 WHERE id_user = "4";
UPDATE utilisateur SET points=200 WHERE id_user = "2";