-- TABLE : lumière connectée
CREATE TABLE IF NOT EXISTS lumiere_connectee (
    id_lumiere_connectee INT PRIMARY KEY AUTO_INCREMENT,
    intensite INT,
    couleur VARCHAR(15),
    allume BOOLEAN,
    nom_salle VARCHAR(15),
    connexion VARCHAR(20), -- pour savoir si elle est connectée (WiFi, Bluetooth, etc.)
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
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Alexa/Google Home
CREATE TABLE IF NOT EXISTS assistant_voix (
    id_assistant INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    type_assistant VARCHAR(50),  -- 'Alexa', 'Google Home'
    connexion VARCHAR(20),       -- 'Wi-Fi', 'Bluetooth'
    nom_salle VARCHAR(15),
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
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Gestion de l'eau
CREATE TABLE IF NOT EXISTS gestion_eau (
    id_gestion_eau INT PRIMARY KEY AUTO_INCREMENT,
    consommation_eau INT,  -- Consommation en L/min ou similaire
    mode_fonctionnement VARCHAR(15),  -- 'éteint', 'en fonctionnement'
    dernier_usage DATETIME,   -- Dernière utilisation
    nom_salle VARCHAR(15),
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
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Ouverture des portes
CREATE TABLE IF NOT EXISTS ouverture_portes (
    id_porte INT PRIMARY KEY AUTO_INCREMENT,
    porte VARCHAR(15),  -- 'porte principale', 'porte garage'
    etat BOOLEAN,  -- TRUE = ouverte, FALSE = fermée
    connexion VARCHAR(20),  -- 'Wi-Fi', 'Bluetooth'
    nom_salle VARCHAR(15),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Machine à laver
CREATE TABLE IF NOT EXISTS machine_laver (
    id_machine_laver INT PRIMARY KEY AUTO_INCREMENT,
    programme VARCHAR(15),  -- ex: 'Coton', 'Synthétiques'
    temps_restant INT,  -- Temps restant en minutes
    fonctionnement BOOLEAN,  -- TRUE = en marche, FALSE = arrêt
    nom_salle VARCHAR(15),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Sèche-linge
CREATE TABLE IF NOT EXISTS seche_linge (
    id_seche_linge INT PRIMARY KEY AUTO_INCREMENT,
    programme VARCHAR(15),  -- 'Coton', 'Synthetique', etc.
    temps_restant INT,  -- Temps restant en minutes
    fonctionnement BOOLEAN,
    nom_salle VARCHAR(15),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Lave-vaisselle
CREATE TABLE IF NOT EXISTS lave_vaisselle (
    id_lave_vaisselle INT PRIMARY KEY AUTO_INCREMENT,
    programme VARCHAR(15),  -- 'Rapide', 'Intensif', etc.
    temps_restant INT,
    fonctionnement BOOLEAN,
    nom_salle VARCHAR(15),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Aspirateur
CREATE TABLE IF NOT EXISTS aspirateur (
    id_aspirateur INT PRIMARY KEY AUTO_INCREMENT,
    programme VARCHAR(15),  -- 'Normal', 'Turbo', etc.
    temps_restant INT,  -- Temps restant en minutes
    fonctionnement BOOLEAN,
    nom_salle VARCHAR(15),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Tondeuse
CREATE TABLE IF NOT EXISTS tondeuse (
    id_tondeuse INT PRIMARY KEY AUTO_INCREMENT,
    programme VARCHAR(15),  -- 'Pelouse', 'Bordure', etc.
    fonctionnement BOOLEAN,
    nom_salle VARCHAR(15),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);

-- TABLE : Plaque chauffante
CREATE TABLE IF NOT EXISTS plaque_chauffante (
    id_plaque INT PRIMARY KEY AUTO_INCREMENT,
    temperature INT,  -- Température actuelle
    fonctionnement BOOLEAN,  -- TRUE = en marche, FALSE = arrêt
    nom_salle VARCHAR(15),
    FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle)
);
