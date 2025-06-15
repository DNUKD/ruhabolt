USE ruhabolt;

INSERT INTO tipusok (nev) VALUES 
('Póló'), ('Ing'), ('Blúz'), ('Pulóver'), ('Nadrág'), ('Ruha'),('Szoknya'),('Body');

INSERT INTO szinek (nev) VALUES 
('Fehér'), ('Fekete'), ('Kék'), ('Zöld'), ('Sárga');

INSERT INTO anyagok (nev) VALUES 
('Pamut'), ('Vászon'), ('Len'), ('Szövet'), ('Farmer'), ('Kevert anyagú');

INSERT INTO nemek (nev) VALUES 
('Férfi'), ('Női'), ('Unisex'), ('Leány'), ('Fiú');

UPDATE nemek SET nev = LOWER(nev);

INSERT INTO meretek (meret, tipus_id) VALUES 
('S', 1), ('M', 1), ('L', 1), ('XL', 1), ('32', 2), ('34', 2), ('36', 2), ('38', 2), ('40', 2);

INSERT INTO fazonok (nev) VALUES 
('Egyenes'), ('Trapéz'), ('V-nyakú'),('Környakú'),('Csónaknyakú'), ('3/4-es'), ('Bő'),
('Fűzős'), ('Megkötős'), ('Kapucnis'), ('Tüll'), ('Csipkés');

INSERT INTO hosszak (nev) VALUES 
('Rövid'), ('Közepes'), ('Hosszú'),('Rövid ujjú'),('Hosszú ujjú');


/*Nemekhez tartozó áruk*/

INSERT INTO tipus_nem (tipus_id, nem_id)
SELECT tipusok.id, nemek.id
FROM tipusok, nemek
WHERE tipusok.nev="Póló" AND nemek.nev = "Unisex";

INSERT INTO tipus_nem (tipus_id, nem_id)
SELECT tipusok.id, nemek.id
FROM tipusok, nemek
WHERE  tipusok.nev="Ruha" AND nemek.nev = "Női";

INSERT INTO tipus_nem (tipus_id, nem_id)
SELECT tipusok.id, nemek.id
FROM tipusok, nemek
WHERE  tipusok.nev="Ruha" AND nemek.nev = "Leány";

INSERT INTO tipus_nem (tipus_id, nem_id)
SELECT tipusok.id, nemek.id
FROM tipusok, nemek
WHERE  tipusok.nev="Szoknya" AND nemek.nev = "Női";

INSERT INTO tipus_nem (tipus_id, nem_id)
SELECT tipusok.id, nemek.id
FROM tipusok, nemek
WHERE  tipusok.nev="Szoknya" AND nemek.nev = "Leány";

INSERT INTO tipus_nem (tipus_id, nem_id)
SELECT tipusok.id, nemek.id
FROM tipusok, nemek
WHERE  tipusok.nev="Blúz" AND nemek.nev = "Női";

INSERT INTO tipus_nem (tipus_id, nem_id)
SELECT tipusok.id, nemek.id
FROM tipusok, nemek
WHERE  tipusok.nev="Blúz" AND nemek.nev = "Leány";

INSERT INTO tipus_nem (tipus_id, nem_id)
SELECT tipusok.id, nemek.id
FROM tipusok, nemek
WHERE  tipusok.nev="Body" AND nemek.nev = "Női";

INSERT INTO tipus_nem (tipus_id, nem_id)
SELECT tipusok.id, nemek.id
FROM tipusok, nemek
WHERE  tipusok.nev="Body" AND nemek.nev = "Leány";

/*Fazon és hossz*/

INSERT INTO tipus_fazon (tipus_id, fazon_id)
SELECT t.id, f.id FROM tipusok t, fazonok f
WHERE t.nev = 'Póló' AND f.nev IN ('V-nyakú', 'Környakú', 'Csónaknyakú');

INSERT INTO tipus_hossz (tipus_id, hossz_id)
SELECT t.id, h.id FROM tipusok t, hosszak h
WHERE t.nev = 'Póló' AND h.nev IN ('Rövid ujjú', 'Hosszú ujjú');

INSERT INTO tipus_fazon (tipus_id, fazon_id)
SELECT t.id, f.id FROM tipusok t, fazonok f
WHERE t.nev = 'Nadrág' AND f.nev IN ( 'Egyenes', 'Trapéz');

INSERT INTO tipus_hossz (tipus_id, hossz_id)
SELECT t.id, h.id FROM tipusok t, hosszak h
WHERE t.nev = 'Nadrág' AND h.nev IN ( 'Rövid', '3/4-es', 'Hosszú');

INSERT INTO tipus_fazon (tipus_id, fazon_id)
SELECT t.id, f.id FROM tipusok t, fazonok f
WHERE t.nev = 'Ruha' AND f.nev IN ( 'Fűzős', 'Megkötős');

INSERT INTO tipus_hossz (tipus_id, hossz_id)
SELECT t.id, h.id FROM tipusok t, hosszak h
WHERE t.nev = 'Ruha' AND h.nev IN ('Rövid', 'Közepes', 'Hosszú');

INSERT INTO tipus_fazon (tipus_id, fazon_id)
SELECT t.id, f.id FROM tipusok t, fazonok f
WHERE t.nev = 'Blúz' AND f.nev IN ( 'Környakú', 'V-nyakú');

INSERT INTO tipus_hossz (tipus_id, hossz_id)
SELECT t.id, h.id FROM tipusok t, hosszak h
WHERE t.nev = 'Blúz' AND h.nev IN ('rövid ujjú', 'hosszú ujjú');

INSERT INTO tipus_hossz (tipus_id, hossz_id)
SELECT t.id, h.id FROM tipusok t, hosszak h
WHERE t.nev = 'Ing' AND h.nev IN ('Rövid ujjú', 'Hosszú ujjú');

INSERT INTO tipus_fazon (tipus_id, fazon_id)
SELECT t.id, f.id FROM tipusok t, fazonok f
WHERE t.nev = 'Pulóver' AND f.nev IN ( 'Kapucnis', 'Bő');

INSERT INTO tipus_fazon (tipus_id, fazon_id)
SELECT t.id, f.id FROM tipusok t, fazonok f
WHERE t.nev = 'Szoknya' AND f.nev IN ('Tüll', 'Csipkés');

INSERT INTO tipus_hossz (tipus_id, hossz_id)
SELECT t.id, h.id FROM tipusok t, hosszak h
WHERE t.nev = 'Szoknya' AND h.nev IN ('Rövid','Hosszú');

INSERT INTO tipus_fazon (tipus_id, fazon_id)
SELECT t.id, f.id FROM tipusok t, fazonok f
WHERE t.nev = 'Body' AND f.nev IN  ('V-nyakú', 'Csónaknyakú');

INSERT INTO tipus_hossz (tipus_id, hossz_id)
SELECT t.id, h.id FROM tipusok t, hosszak h
WHERE t.nev = 'Body' AND h.nev IN ('Rövid ujjú', 'Hosszú ujjú');


