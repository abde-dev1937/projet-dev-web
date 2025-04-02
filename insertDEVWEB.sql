insert into utilisateur (id_user, pseudonyme, gender, dateNaissance, age, email, avatar, statut, first_name, last_name, mot_de_passe, points) values (1, 'meliss1', 'Female', '1983-09-26', 41, 'hklos0@vkontakte.ru', 'https://robohash.org/laborumconsequaturiusto.png?size=50x50&set=set1', 'invité', 'Mélissandre', 'Klos', 'gL8@dZ?y',0);
insert into utilisateur (id_user, pseudonyme, gender, dateNaissance, age, email, avatar, statut, first_name, last_name, mot_de_passe, points) values (2, 'gisele2', 'Female', '1980-05-01', 44, 'tpitone1@paypal.com', 'https://robohash.org/quovelitsunt.png?size=50x50&set=set1', 'invité', 'Gisèle', 'Pitone', 'jP3@Xxff}%v<p',0);
insert into utilisateur (id_user, pseudonyme, gender, dateNaissance, age, email, avatar, statut, first_name, last_name, mot_de_passe, points) values (3, 'rachele3', 'Male', '2015-02-10', 10, 'fdoag2@netvibes.com', 'https://robohash.org/quiaofficiaaut.png?size=50x50&set=set1', 'enfant', 'Rachèle', 'Doag', 'oH7+!gcOik',10);
insert into utilisateur (id_user, pseudonyme, gender, dateNaissance, age, email, avatar, statut, first_name, last_name, mot_de_passe, points) values (4, 'madamed4', 'Female', '2007-02-05', 18, 'dhorstead3@blog.com', 'https://robohash.org/harummagnimolestiae.png?size=50x50&set=set1', 'invité', 'Håkan', 'Horstead', 'gA4%PJYIu1y)O{P',0);
insert into utilisateur (id_user, pseudonyme, gender, dateNaissance, age, email, avatar, statut, first_name, last_name, mot_de_passe, points) values (5, 'monsieur5', 'Male', '1969-06-06', 55, 'kwhipp4@icio.us', 'https://robohash.org/nonitaqueautem.png?size=50x50&set=set1', 'invité', 'Kuí', 'Whipp', 'lI6~r/_b/al?rO',0);
insert into utilisateur (id_user, pseudonyme, gender, dateNaissance, age, email, avatar, statut, first_name, last_name, mot_de_passe, points) values (6, 'papacool6', 'Male', '1970-11-15', 54, 'papacool@gmail.com', 'https://robohash.org/repudiandaenecessitatibusexcepturi.png?size=50x50&set=set1', 'père', 'didier', 'dupont','Papacool123',100);
insert into utilisateur (id_user, pseudonyme, gender, dateNaissance, age, email, avatar, statut, first_name, last_name, mot_de_passe, points) values (7, 'crazymom7', 'Female', '1974-07-12', 50, 'crazymom@gmail.com', 'https://robohash.org/liberodistinctioaut.png?size=50x50&set=set1', 'mère', 'syndie', 'dupont', 'Flemme17',100);

insert into thermostat (id_thermo, nom, temp_actu, temp_cible, mode_thermo, connexion, batterie, derniere_interaction) values (1, 'thermostat1', 19, 21,'automatique', 'WI-FI', 80, '2025-04-01');

insert into salle (nom_salle) values ('chambre1');
insert into salle (nom_salle) values ('chambre2');
insert into salle (nom_salle) values ('salon');
insert into salle (nom_salle) values ('salle à manger');
insert into salle (nom_salle) values ('salle de bain');
insert into salle (nom_salle) values ('cagibi');
insert into salle (nom_salle) values ('cuisine');

insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (1, 35, 'Yellow', true, 'chambre1');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (2, 5, 'Teal', true, 'chambre1');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (3, 0, 'Maroon', false, 'chambre2');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (4, 54, 'Teal', false, 'chambre2');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (5, 6, 'Violet', true, 'salon');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (6, 16, 'Fuscia', true, 'salon');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (7, 43, 'Violet', false, 'salon');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (8, 93, 'Maroon', false, 'salon');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (9, 83, 'Goldenrod', true, 'cagibi');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (10, 78, 'Maroon', true, 'cagibi');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (11, 4, 'Blue', false, 'salle de bain');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (12, 55, 'Orange', true, 'salle à manger');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (13, 66, 'Violet', false, 'cuisine');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (14, 55, 'Maroon', true, 'salle à manger');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (15, 41, 'Orange', true, 'cuisine');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (16, 94, 'Khaki', false, 'salle de bain');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (17, 10, 'Aquamarine', true, 'chambre1');
insert into lumiere (id_lum, intensite, couleur, allume, nom_salle) values (18, 36, 'Maroon', false, 'chambre2');

insert into television (id_tel, volume, chaine_actu, allume, source_tel, nom_salle) values (1, 92, 'W9', true, 'HDMI2', 'chambre1');
insert into television (id_tel, volume, chaine_actu, allume, source_tel, nom_salle) values (2, 99, 'Netflix', true, 'ANTENNE', 'salon');
insert into television (id_tel, volume, chaine_actu, allume, source_tel, nom_salle) values (3, 44, 'Canal+', true, 'HDMI2', 'chambre2');

insert into enceinte (id_enceinte, volume, allume, bluetooth, nom_salle) values (1, 62, false, false, 'chambre1');
insert into enceinte (id_enceinte, volume, allume, bluetooth, nom_salle) values (2, 6, true, false, 'chambre2');
insert into enceinte (id_enceinte, volume, allume, bluetooth, nom_salle) values (3, 63, true, true, 'salon');
insert into enceinte (id_enceinte, volume, allume, bluetooth, nom_salle) values (4, 63, true, true, 'salon');

insert into portail (id_portail, ouvert) values (1, true);

insert into laveLinge (id_laveLinge, programme, temps_restant, fonctionnement, nom_salle) values (1, 'coton', 47, false, null);

insert into chauffeEau (id_chauffeEau, temp_eau, volume_dispo, mode_fonct) values (1, 38, 100, 'automatique');

insert into arrosage (id_arro, frequence, duree, fonctionnement, derniere_activation) values (1, 'mensuelle', 30, true, '2024-09-17');
insert into arrosage (id_arro, frequence, duree, fonctionnement, derniere_activation) values (2, 'mensuelle', 15, false, '2024-09-13');
insert into arrosage (id_arro, frequence, duree, fonctionnement, derniere_activation) values (3, 'mensuelle', 20, true, '2025-03-26');
insert into arrosage (id_arro, frequence, duree, fonctionnement, derniere_activation) values (4, 'mensuelle', 20, true, '2024-06-29');
insert into arrosage (id_arro, frequence, duree, fonctionnement, derniere_activation) values (5, 'hebdomadaire', 15, true, '2024-10-05');
insert into arrosage (id_arro, frequence, duree, fonctionnement, derniere_activation) values (6, 'hebdomadaire', 30, true, '2025-01-07');
insert into arrosage (id_arro, frequence, duree, fonctionnement, derniere_activation) values (7, 'mensuelle', 10, true, '2024-07-16');
insert into arrosage (id_arro, frequence, duree, fonctionnement, derniere_activation) values (8, 'mensuelle', 15, false, '2024-06-24');
insert into arrosage (id_arro, frequence, duree, fonctionnement, derniere_activation) values (9, 'hebdomadaire', 20, true, '2024-06-14');
insert into arrosage (id_arro, frequence, duree, fonctionnement, derniere_activation) values (10, 'mensuelle', 15, false, '2024-06-11');




