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
mot_de_passe varchar(15),
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



-- TABLE : lumière connectée
CREATE TABLE IF NOT EXISTS lumiere_connectee (
    id_lumiere_connectee INT PRIMARY KEY AUTO_INCREMENT,
    intensite INT,
    couleur VARCHAR(15),
    allume BOOLEAN,
    nom_salle VARCHAR(15),
    connexion VARCHAR(20), -- pour savoir si elle est connectée (WiFi, Bluetooth, etc.)
    id_connection VARCHAR(20),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : chauffage connecté
CREATE TABLE IF NOT EXISTS chauffage_connecte (
    id_chauffage_connecte INT PRIMARY KEY AUTO_INCREMENT,
    temperature_actuelle INT,
    temperature_cible INT,
    mode VARCHAR(15),  -- ex: 'chauffage', 'refroidissement'
    connexion VARCHAR(20),
    nom_salle VARCHAR(15),
    id_connection VARCHAR(20),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : TV connectée
CREATE TABLE IF NOT EXISTS tv_connectee (
    id_tv_connectee INT PRIMARY KEY AUTO_INCREMENT,
    volume INT,
    chaine_actuelle VARCHAR(15),
    source_tv VARCHAR(15),
    allume BOOLEAN,
    nom_salle VARCHAR(15),
    connexion VARCHAR(20),  -- par exemple, 'Wi-Fi', 'Ethernet'
    id_connection VARCHAR(20),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Alexa/Google Home
CREATE TABLE IF NOT EXISTS assistant_voix (
    id_assistant INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    type_assistant VARCHAR(50),  -- 'Alexa', 'Google Home'
    connexion VARCHAR(20),       -- 'Wi-Fi', 'Bluetooth'
    nom_salle VARCHAR(15),
    id_connection VARCHAR(20),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Enceintes connectées
CREATE TABLE IF NOT EXISTS enceinte_connectee (
    id_enceinte_connectee INT PRIMARY KEY AUTO_INCREMENT,
    volume INT,
    bluetooth BOOLEAN,
    allume BOOLEAN,
    connexion VARCHAR(20),
    nom_salle VARCHAR(15),
    id_connection VARCHAR(20),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Gestion de l'eau
CREATE TABLE IF NOT EXISTS gestion_eau (
    id_gestion_eau INT PRIMARY KEY AUTO_INCREMENT,
    consommation_eau INT,  -- Consommation en L/min ou similaire
    mode_fonctionnement VARCHAR(15),  -- 'éteint', 'en fonctionnement'
    dernier_usage DATETIME,   -- Dernière utilisation
    nom_salle VARCHAR(15),
    id_connection VARCHAR(20),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Gestion du chauffage
CREATE TABLE IF NOT EXISTS gestion_chauffage (
    id_gestion_chauffage INT PRIMARY KEY AUTO_INCREMENT,
    temperature_actuelle INT,
    temperature_cible INT,
    mode_fonctionnement VARCHAR(15),  -- 'chauffage', 'arrêt'
    dernier_ajustement DATETIME,  -- Dernière modification
    nom_salle VARCHAR(15),
    id_connection VARCHAR(20),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);


-- TABLE : Ouverture des portes
CREATE TABLE IF NOT EXISTS ouverture_portes (
    id_porte INT PRIMARY KEY AUTO_INCREMENT,
    porte VARCHAR(15),  -- 'porte principale', 'porte garage'
    etat BOOLEAN,  -- TRUE = ouverte, FALSE = fermée
    connexion VARCHAR(20),  -- 'Wi-Fi', 'Bluetooth'
    nom_salle VARCHAR(15),
    id_connection VARCHAR(20),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Machine à laver
CREATE TABLE IF NOT EXISTS machine_laver (
    id_machine_laver INT PRIMARY KEY AUTO_INCREMENT,
    programme VARCHAR(15),  -- ex: 'Coton', 'Synthétiques'
    temps_restant INT,  -- Temps restant en minutes
    fonctionnement BOOLEAN,  -- TRUE = en marche, FALSE = arrêt
    nom_salle VARCHAR(15),
    id_connection VARCHAR(20),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Sèche-linge


-- TABLE : Lave-vaisselle
CREATE TABLE IF NOT EXISTS lave_vaisselle (
    id_lave_vaisselle INT PRIMARY KEY AUTO_INCREMENT,
    programme VARCHAR(15),  -- 'Rapide', 'Intensif', etc.
    temps_restant INT,
    taux_remplissage FLOAT,
    fonctionnement BOOLEAN,
    nom_salle VARCHAR(15),
    id_connection VARCHAR(20),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Aspirateur
CREATE TABLE IF NOT EXISTS aspirateur (
    id_aspirateur INT PRIMARY KEY AUTO_INCREMENT,
	nom VARCHAR(15),
    programme VARCHAR(15),  -- 'Normal', 'Turbo', etc.
    temps_restant INT,  -- Temps restant en minutes
    fonctionnement BOOLEAN,
    nom_salle VARCHAR(15),
    id_connection VARCHAR(20),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle) -- ou id ?
);

-- TABLE : Tondeuse
CREATE TABLE IF NOT EXISTS tondeuse (
    id_tondeuse INT PRIMARY KEY AUTO_INCREMENT,
    programme VARCHAR(15),  -- 'Pelouse', 'Bordure', etc.
    fonctionnement BOOLEAN,
    nom_salle VARCHAR(15),
    id_connection VARCHAR(20),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Plaque chauffante
CREATE TABLE IF NOT EXISTS plaque_chauffante (
    id_plaque INT PRIMARY KEY AUTO_INCREMENT,
    temperature INT,  -- Température actuelle
    fonctionnement BOOLEAN,  -- TRUE = en marche, FALSE = arrêt
    nom_salle VARCHAR(15),
    id_connection VARCHAR(20),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

CREATE TABLE IF NOT EXISTS projecteur (
    id_projecteur INT PRIMARY KEY AUTO_INCREMENT,
    fonctionnement BOOLEAN,
    volume FLOAT,
    qualite FLOAT,
    intensite_couleurs VARCHAR(15),
    id_connection VARCHAR(15),
    nom_salle VARCHAR(15),
    nom VARCHAR(15),
    conso_en INT,
    source VARCHAR(15),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

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

DROP TABLE IF EXISTS `seche_linge`;
CREATE TABLE IF NOT EXISTS `seche_linge` (id_seche_linge INT PRIMARY KEY AUTO_INCREMENT, `temperature` FLOAT, `programme` VARCHAR(255), `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `conso_en` FLOAT, `fonctionnement` BOOLEAN);

DROP TABLE IF EXISTS `console`;
CREATE TABLE IF NOT EXISTS `console` (id_console INT PRIMARY KEY AUTO_INCREMENT, `source_tv` FLOAT, `volume` VARCHAR(255), `controle` FLOAT, `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `fonctionnement` BOOLEAN);

DROP TABLE IF EXISTS `console`;
CREATE TABLE IF NOT EXISTS `console` (id_console INT PRIMARY KEY AUTO_INCREMENT, `source_tv` FLOAT, `volume` VARCHAR(255), `controle` FLOAT, `nom` VARCHAR(255), `id_connection` VARCHAR(255), `nom_salle` VARCHAR(255), `fonctionnement` BOOLEAN);

