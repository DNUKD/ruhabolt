USE ruhabolt;

CREATE TABLE termek (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nev VARCHAR(100) NOT NULL,
    ar INT,
    keszlet INT NOT NULL DEFAULT 0
);
