--liquibase formatted sql

--changeset lucas:001

DROP TABLE IF EXISTS Absence, Justificatif, Utilisateur, Statut, MotifPourResponsable, MotifPourEleve,testpush CASCADE;

CREATE TABLE MotifPourEleve (
                                motifEleve TEXT PRIMARY KEY
);

CREATE TABLE MotifPourResponsable (
                                      motifRespon TEXT PRIMARY KEY
);

CREATE TABLE Statut (
                        Statut TEXT PRIMARY KEY
);

CREATE TABLE Utilisateur (
                             idUtilisateur SERIAL PRIMARY KEY,
                             nomUtilisateur TEXT,
                             motDePasse TEXT,
                             nom TEXT,
                             prénom TEXT,
                             role TEXT
);

CREATE TABLE Justificatif (
                              idJustificatif SERIAL PRIMARY KEY,
                              dateDebut DATE,
                              dateFin DATE,
                              heureDebut TIME,
                              heureFin TIME,
                              fichier BYTEA,
                              CommentaireEleve TEXT,
                              AdresseMail TEXT,
                              commentaireRespon TEXT,

                              motifEleve TEXT,
                              motifRespon TEXT,
                              statutJustificatif TEXT,

                              FOREIGN KEY (motifEleve) REFERENCES MotifPourEleve(motifEleve),
                              FOREIGN KEY (motifRespon) REFERENCES MotifPourResponsable(motifRespon),
                              FOREIGN KEY (statutJustificatif) REFERENCES Statut(Statut)
);


CREATE TABLE Absence (
                         idAbsence SERIAL PRIMARY KEY,
                         date DATE,
                         heure TIME,
                         duree INTERVAL,
                         evaluation BOOLEAN,
                         matiere TEXT,

                         statutAbsence TEXT,
                         idUtilisateur INTEGER,
                         idJustificatif INTEGER,

                         FOREIGN KEY (statutAbsence) REFERENCES Statut(Statut),
                         FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur),
                         FOREIGN KEY (idJustificatif) REFERENCES Justificatif(idJustificatif)
);














ALTER TABLE Absence
ALTER COLUMN duree TYPE TIME;

ALTER TABLE Absence
    ADD COLUMN prof TEXT;

DROP TABLE IF EXISTS Statut CASCADE;

ALTER TABLE Justificatif
DROP COLUMN statutJustificatif;

ALTER TABLE Absence
DROP COLUMN statutAbsence;

ALTER TABLE Justificatif
    ADD COLUMN statut TEXT;

ALTER TABLE Absence
    ADD COLUMN statut TEXT;

ALTER TABLE Utilisateur
    ADD COLUMN identifiantIUT INT UNIQUE;

DROP TABLE IF EXISTS MotifPourEleve CASCADE;

DROP TABLE IF EXISTS MotifPourResponsable CASCADE;


ALTER TABLE Justificatif
DROP COLUMN motifeleve;

ALTER TABLE Justificatif
DROP COLUMN motifrespon;

ALTER TABLE Justificatif
    ADD COLUMN motifeleve TEXT;

ALTER TABLE Justificatif
    ADD COLUMN motifrespon TEXT;

ALTER TABLE Justificatif
DROP COLUMN fichier;

ALTER TABLE Justificatif
    ADD COLUMN fichier VARCHAR(255);

ALTER TABLE Utilisateur
    ADD COLUMN Groupe TEXT;

ALTER TABLE Utilisateur
    ADD COLUMN email TEXT UNIQUE;

ALTER TABLE Justificatif
DROP COLUMN adressemail;

ALTER TABLE Absence
    ADD COLUMN ressource TEXT;

ALTER TABLE Absence
    ADD COLUMN typecours TEXT;

ALTER TABLE Justificatif
DROP COLUMN fichier;

ALTER TABLE Justificatif
    ADD COLUMN fichier1 VARCHAR(255);

ALTER TABLE Justificatif
    ADD COLUMN fichier2 VARCHAR(255);

ALTER TABLE Utilisateur
    ADD COLUMN tentatives_echouees INT;

ALTER TABLE Utilisateur
    ADD COLUMN date_fin_blocage TIMESTAMP;

ALTER TABLE utilisateur
    ADD COLUMN derniere_tentative TIMESTAMP;

ALTER TABLE Justificatif
    ADD COLUMN date_depot TIMESTAMP;

ALTER DATABASE neondb SET timezone = 'Europe/Paris';

CREATE TABLE motifRefus (
    id SERIAL PRIMARY KEY,
    motif TEXT
);

CREATE TABLE motifacceptation (
    id SERIAL PRIMARY KEY,
    motif TEXT NOT NULL
);


ALTER TABLE Utilisateur
    ADD COLUMN token_recuperation VARCHAR(255) DEFAULT NULL,
    ADD COLUMN date_expiration_token TIMESTAMP DEFAULT NULL;


