<?php
if (!isset($pdo)) {
    require_once './protected/config.php';
}
session_start();

require_once './protected/config.php';
require_once './protected/queries.php';

$nev = trim($_POST['nev'] ?? '');
$ar = trim($_POST['ar'] ?? '') === '' ? null : (float)$_POST['ar'];
$keszlet = isset($_POST['keszlet']) && $_POST['keszlet'] !== '' ? (int)$_POST['keszlet'] : 0;
$tipus_id = (int)($_POST['tipus_id'] ?? 0);
$szin_id = (int)($_POST['szin_id'] ?? 0);
$anyag_id = (int)($_POST['anyag_id'] ?? 0);
$nem_id = $_POST['nem_id'] ?? null;
$fazon_id = $_POST['fazon_id'] !== '' ? (int)$_POST['fazon_id'] : null;
$hossz_id = $_POST['hossz_id'] !== '' ? (int)$_POST['hossz_id'] : null;
$meret = $_POST['meret'] ?? '';
$leiras = $_POST['leiras'] ?? '';

// Nem numerikus nem_id esetén lekérdezzük
if (!is_numeric($nem_id)) {
    $stmt = $pdo->prepare("SELECT id FROM nemek WHERE LOWER(nev) = LOWER(?)");
    $stmt->execute([$nem_id]);
    $nem_id = $stmt->fetchColumn();
    if (!$nem_id) {
        $_SESSION['hiba_uzenet'] = "Érvénytelen nem érték.";
        header("Location: ../projekt/start.php?menu=create");
        exit;
    }
}

// Kötelező mezők ellenőrzése
if (
    $nev === '' ||
    empty($tipus_id) ||
    empty($szin_id) ||
    empty($anyag_id) ||
    empty($nem_id) ||
    $_POST['keszlet'] === ''
) {
    $_SESSION['hiba_uzenet'] = "Minden kötelező mezőt ki kell tölteni!";
    header("Location: ../projekt/start.php?menu=create");
    exit;
}

// Tartalmi validációk
if (!preg_match('/[a-zA-Z]/', $nev)) {
    $_SESSION['hiba_uzenet'] = "A név nem lehet csak szám vagy üres.";
    header("Location: ../projekt/start.php?menu=create");
    exit;
}

if ($ar !== null && ($ar < 0 || $ar > 100000)) {
    $_SESSION['hiba_uzenet'] = "Az árnak 0 és 100000 Ft között kell lennie, vagy üresen hagyható.";
    header("Location: ../projekt/start.php?menu=create");
    exit;
}

if ($keszlet < 0 || $keszlet > 9999) {
    $_SESSION['hiba_uzenet'] = "A készlet értéke 0 és 9999 közötti szám lehet.";
    header("Location: ../projekt/start.php?menu=create");
    exit;
}

// Beszúrás adatbázisba
try {
    $stmt = $pdo->prepare("
        INSERT INTO termekek (nev, ar, keszlet, tipus_id, szin_id, anyag_id, nem_id, fazon_id, hossz_id, meret, leiras)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $nev,
        $ar,
        $keszlet,
        $tipus_id,
        $szin_id,
        $anyag_id,
        $nem_id,
        $fazon_id,
        $hossz_id,
        $meret,
        $leiras
    ]);
    $_SESSION['siker_uzenet'] = "A termék sikeresen elmentve: <strong>" . htmlspecialchars($nev) . "</strong>";
    header("Location: ../projekt/start.php?menu=create");
    exit;
} catch (PDOException $e) {
    $_SESSION['hiba_uzenet'] = "Hiba mentés közben: " . $e->getMessage();
    header("Location: ../projekt/start.php?menu=create");
    exit;
}


