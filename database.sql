DROP DATABASE IF EXISTS camps;

CREATE DATABASE camps DEFAULT CHARACTER SET utf8;

USE camps;

SET foreign_key_checks = 0;

/***************************
* Création des tables
****************************/

CREATE TABLE personnes(
    id INT UNSIGNED AUTO_INCREMENT,
    nom VARCHAR(30) NOT NULL,
    prenom VARCHAR(30) NOT NULL,
    date_naissance DATE NOT NULL,
    id_statut TINYINT UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    CONSTRAINT personnes_to_statuts
        FOREIGN KEY (id_statut) REFERENCES statuts(id)
);


CREATE TABLE statuts(
    id TINYINT UNSIGNED AUTO_INCREMENT,
    libelle VARCHAR (20) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE camps(
    id INT UNSIGNED AUTO_INCREMENT,
    nom VARCHAR (30) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    nb_place TINYINT UNSIGNED NOT NULL,
    id_tranche_age TINYINT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT camp_to_tranche_age 
        FOREIGN KEY (id_tranche_age) REFERENCES tranche_age(id)
);

CREATE TABLE affectation_camps(
    id_camp INT UNSIGNED,
    id_participant INT UNSIGNED,
    PRIMARY KEY (id_camp, id_participant),
    CONSTRAINT affectation_to_camp 
        FOREIGN KEY (id_camp) REFERENCES camps(id),
    CONSTRAINT affectation_to_personne 
        FOREIGN KEY (id_participant) REFERENCES personnes(id)
);

CREATE TABLE tranche_age (
    id TINYINT UNSIGNED AUTO_INCREMENT,
    libelle VARCHAR (20) NOT NULL,
    age_min TINYINT UNSIGNED NOT NULL,
    age_max TINYINT UNSIGNED NOT NULL,
    nb_enfants_par_animateur TINYINT UNSIGNED NOT NULL,
    PRIMARY KEY (id)
);

SET foreign_key_checks = 1;

/***************************
* Insertion des données
****************************/

INSERT INTO statuts (libelle) VALUES ('enfant'), ('animateur');

INSERT INTO tranche_age (libelle, age_min, age_max, nb_enfants_par_animateur)
VALUES
('Farfadet', 6, 8, 4),
('Louveteau', 9, 11, 6),
('Scout', 12, 14, 8),
('Pionnier', 15, 17, 12);

INSERT INTO personnes (nom, prenom, id_statut, date_naissance) VALUES
('Martin', 'Jeanne', 1, '2010-10-18'), ('Martin', 'Pierre', 1, '2013-06-12')
,('Albert', 'Paul', 1, '2008-10-18'), ('Julle', 'Amandine', 1, '2006-06-12')
,('Rhino', 'Odile', 2, '1990-10-18'), ('Saltiel', 'Philippe', 2, '2000-06-12');

INSERT INTO camps (nom, date_debut, date_fin, nb_place, id_tranche_age) VALUES
('Camp dans les landes', '2021-05-12', '2020-05-20', 6, 1),
('Camp dans les calanques', '2021-05-13', '2020-05-24', 6, 2),
('Camp dans les landes', '2021-06-12', '2020-06-20', 6, 1);

/***************************
* Création des vues
****************************/

CREATE OR REPLACE VIEW vue_personnes AS
SELECT a.*, s.libelle as statut, 
truncate(datediff(CURDATE(), date_naissance) / 365.25, 0) as age
FROM personnes as a
INNER JOIN statuts as s ON s.id = a.id_statut ;

CREATE OR REPLACE VIEW vue_animateurs AS
SELECT * FROM vue_personnes WHERE id_statut = 2;

CREATE OR REPLACE VIEW vue_enfants AS
SELECT p.*, ta.libelle as tranche_age
FROM vue_personnes as p
INNER JOIN tranche_age as ta ON p.age BETWEEN ta.age_min and ta.age_max
WHERE id_statut = 1;
