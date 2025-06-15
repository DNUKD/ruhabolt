CREATE TABLE termek_meretek (
    termek_id INT,
    meret VARCHAR(10),
    tipus_id INT,
    PRIMARY KEY (termek_id, meret, tipus_id),
    FOREIGN KEY (termek_id) REFERENCES termekek(id),
    FOREIGN KEY (meret, tipus_id) REFERENCES meretek(meret,tipus_id)
  
);

CREATE TABLE tipus_nem (
    tipus_id INT,
    nem_id INT,
    PRIMARY KEY (tipus_id,nem_id),
    FOREIGN KEY (tipus_id) REFERENCES tipusok(id),
    FOREIGN KEY (nem_id) REFERENCES nemek(id)
)