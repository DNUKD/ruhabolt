CREATE TABLE tipusok(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nev VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE szinek(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nev VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE anyagok (
    id INT AUTO_INCREMENT  PRIMARY KEY,
    nev VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE nemek (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nev ENUM('férfi', 'női', 'unisex', 'leány', 'fiú') NOT NULL UNIQUE
);

CREATE TABLE meretek(
    meret VARCHAR(10) NOT NULL,
    tipus_id int NOT NULL,
    FOREIGN KEY (tipus_id) REFERENCES tipusok(id),
    UNIQUE (meret, tipus_id)
);

CREATE TABLE termekek(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nev VARCHAR(100) NOT NULL,
    ar decimal(10,2),
    keszlet INT NOT NULL DEFAULT 0,
    tipus_id INT,
    szin_id INT,
    anyag_id INT,
    nem_id INT,
    leiras TEXT,
    FOREIGN KEY (tipus_id) REFERENCES tipusok(id),
    FOREIGN KEY (szin_id) REFERENCES szinek(id),
    FOREIGN KEY (anyag_id) REFERENCES anyagok(id),
    FOREIGN KEY (nem_id) REFERENCES nemek(id)
);

CREATE TABLE fazonok (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nev VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE hosszak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nev VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE tipus_fazon (
    tipus_id INT NOT NULL,
    fazon_id INT NOT NULL,
    PRIMARY KEY (tipus_id, fazon_id),
    FOREIGN KEY (tipus_id) REFERENCES tipusok(id),
    FOREIGN KEY (fazon_id) REFERENCES fazonok(id)
);

CREATE TABLE tipus_hossz (
    tipus_id INT NOT NULL,
    hossz_id INT NOT NULL,
    PRIMARY KEY (tipus_id, hossz_id),
    FOREIGN KEY (tipus_id) REFERENCES tipusok(id),
    FOREIGN KEY (hossz_id) REFERENCES hosszak(id)
);

ALTER TABLE meretek ADD sorrend INT DEFAULT 0;

UPDATE meretek SET sorrend = 1 WHERE meret = 'S' AND tipus_id = 1;
UPDATE meretek SET sorrend = 2 WHERE meret = 'M' AND tipus_id = 1;
UPDATE meretek SET sorrend = 3 WHERE meret = 'L' AND tipus_id = 1;
UPDATE meretek SET sorrend = 4 WHERE meret = 'XL' AND tipus_id = 1;

UPDATE meretek SET sorrend = 1 WHERE meret = '32' AND tipus_id = 2;
UPDATE meretek SET sorrend = 2 WHERE meret = '34' AND tipus_id = 2;
UPDATE meretek SET sorrend = 3 WHERE meret = '36' AND tipus_id = 2;
UPDATE meretek SET sorrend = 4 WHERE meret = '38' AND tipus_id = 2;
UPDATE meretek SET sorrend = 5 WHERE meret = '40' AND tipus_id = 2;

ALTER TABLE termekek ADD COLUMN fazon_id INT NULL;
ALTER TABLE termekek ADD COLUMN hossz_id INT NULL;
ALTER TABLE termekek ADD COLUMN meret VARCHAR(10);


