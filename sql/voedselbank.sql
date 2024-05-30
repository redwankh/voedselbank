CREATE DATABASE voedselbank;

USE voedselbank;

CREATE TABLE klanten
(
    klant_id INT AUTO_INCREMENT PRIMARY KEY,
    naam     VARCHAR(255) NOT NULL
);

CREATE TABLE pakketten
(
    pakket_id INT AUTO_INCREMENT PRIMARY KEY,
    klant_id  INT,
    datum     DATE,
    status    VARCHAR(50),
    FOREIGN KEY (klant_id) REFERENCES klanten (klant_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE producten
(
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    naam       VARCHAR(255) NOT NULL,
    voorraad   INT          NOT NULL
);

CREATE TABLE pakket_producten
(
    pakket_id  INT,
    product_id INT,
    aantal     INT,
    FOREIGN KEY (pakket_id) REFERENCES pakketten (pakket_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES producten (product_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE gebruikers
(
    gebruiker_id   INT AUTO_INCREMENT PRIMARY KEY,
    gebruikersnaam VARCHAR(255) NOT NULL UNIQUE,
    wachtwoord     VARCHAR(255) NOT NULL
);

