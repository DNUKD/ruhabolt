<?php

require_once 'protected/config.php';
function getCelcsoportSzerintiTipusok($nem_id)
{
    global $pdo;

    if (in_array($nem_id, [2, 4])) {

        $stmt = $pdo->query("
            SELECT id, nev FROM tipusok
            WHERE nev NOT IN ('Ing')
            ORDER BY nev
        ");
    } else {

        $stmt = $pdo->query("
            SELECT id, nev FROM tipusok
            WHERE nev NOT IN ('Ruha', 'Szoknya', 'Blúz', 'Body')
            ORDER BY nev
        ");
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getFazonokTipusSzerint($tipus_id)
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT f.id, f.nev
        FROM fazonok f
        JOIN tipus_fazon tf ON f.id = tf.fazon_id
        WHERE tf.tipus_id = ?
        ORDER BY f.nev
    ");
    $stmt->execute([$tipus_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getHosszakTipusSzerint($tipus_id)
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT h.id, h.nev
        FROM hosszak h
        JOIN tipus_hossz th ON h.id = th.hossz_id
        WHERE th.tipus_id = ?
        ORDER BY h.nev
    ");
    $stmt->execute([$tipus_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getNemek()
{
    global $pdo;
    $stmt = $pdo->query("SELECT id, nev FROM nemek ORDER BY nev");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getTipusok()
{
    global $pdo;
    $stmt = $pdo->query("SELECT id, nev FROM tipusok ORDER BY nev");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getAnyagok()
{
    global $pdo;
    $stmt = $pdo->query("SELECT id, nev FROM anyagok ORDER BY nev");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getSzinLista()
{
    global $pdo;
    $stmt = $pdo->query("SELECT id, nev FROM szinek ORDER BY nev");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getMeretek()
{
    global $pdo;
    $stmt = $pdo->query("SELECT meret, tipus_id FROM meretek ORDER BY sorrend ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function insertTermek($nev, $ar, $keszlet, $nem_id, $tipus_id, $anyag_id, $szin_id)
{
    global $pdo;

    $sql = "INSERT INTO termekek (nev, ar, keszlet, nem_id, tipus_id, anyag_id, szin_id)
            VALUES (:nev, :ar, :keszlet, :nem_id, :tipus_id, :anyag_id, :szin_id)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nev' => $nev,
        ':ar' => $ar,
        ':keszlet' => $keszlet,
        ':nem_id' => $nem_id,
        ':tipus_id' => $tipus_id ?: null,
        ':anyag_id' => $anyag_id ?: null,
        ':szin_id' => $szin_id ?: null
    ]);

    return $pdo->lastInsertId(); 
}
function getTermekLista()
{
    global $pdo;
    $sql = "SELECT t.nev, tp.nev AS tipus
          FROM termekek t
          LEFT JOIN tipusok tp ON t.tipus_id = tp.id";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
function getFazonok()
{
    global $pdo;
    $stmt = $pdo->query("SELECT id, nev FROM fazonok ORDER BY nev");
    return $stmt->fetchAll();
}
function getHosszak()
{
    global $pdo;
    $stmt = $pdo->query("SELECT id, nev FROM hosszak ORDER BY nev");
    return $stmt->fetchAll();
}
function getTipusNev($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT nev FROM tipusok WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn() ?: '';
}
function getNemNev($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT nev FROM nemek WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchColumn() ?: '';
}

?>

