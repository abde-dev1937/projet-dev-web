set FOREIGN_KEY_CHECKS = 0;

drop table if exists utilisateur;
drop table if exists thermostat;
drop table if exists lumiere;
drop table if exists television;
drop table if exists enceinte;
drop table if exists portail;
drop table if exists laveLinge;
drop table if exists chauffeEau;
drop table if exists arrosage;
drop table if exists salle;

set FOREIGN_KEY_CHECKS = 1;

create table utilisateur(
id_user int primary key,
pseudonyme varchar(15),
age int,
gender varchar(10),
dateNaissance datetime,
statut varchar(10),
avatar varchar(100),
email varchar(20),
last_name varchar(15),
first_name varchar(15),
mot_de_passe varchar(15),
points int);

create table thermostat(
id_thermo int primary key,
nom varchar(15),
temp_actu int,
temp_cible int,
mode_thermo varchar(15),
connexion varchar(20),
batterie int,
derniere_interaction datetime);

create table salle (
nom_salle varchar(15) primary key);

create table lumiere (
id_lum int primary key,
intensite int,
couleur varchar(15),
allume boolean,
nom_salle VARCHAR(15),
CONSTRAINT fk_lumiere_salle FOREIGN KEY (nom_salle) REFERENCES salle(nom_salle));

create table television (
id_tel int primary key,
volume int,
chaine_actu varchar(15),
source_tel varchar(15),
allume boolean,
nom_salle varchar(15),
foreign key (nom_salle) references salle(nom_salle));

create table enceinte (
id_enceinte int primary key,
volume int,
bluetooth boolean,
allume boolean,
nom_salle varchar(15),
foreign key (nom_salle) references salle(nom_salle));

create table portail (
id_portail int primary key,
ouvert boolean);

create table laveLinge (
id_laveLinge int primary key,
programme varchar(15),
temps_restant int,
fonctionnement boolean,
nom_salle varchar(15),
foreign key (nom_salle) references salle(nom_salle));
 
create table chauffeEau (
id_chauffeEau int primary key,
temp_eau int,
volume_dispo int,
mode_fonct varchar(15));

create table arrosage (
id_arro int primary key,
frequence varchar(15),
duree int,
fonctionnement boolean,
derniere_activation datetime);

