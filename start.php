<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './protected/config.php';
require_once './protected/queries.php';

if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    try {
        if ($_GET['ajax'] === 'get_fazonok') {
            $tipus_id = $_GET['tipus_id'] ?? null;
            if (!$tipus_id) throw new Exception("Hiányzó típus ID");
            echo json_encode(getFazonokTipusSzerint($tipus_id));
            exit;
        }
        if ($_GET['ajax'] === 'get_hosszak') {
            $tipus_id = $_GET['tipus_id'] ?? null;
            if (!$tipus_id) throw new Exception("Hiányzó típus ID");
            echo json_encode(getHosszakTipusSzerint($tipus_id));
            exit;
        }
        if ($_GET['ajax'] === 'get_tipusok' && isset($_GET['nem_id'])) {
            echo json_encode(getCelcsoportSzerintiTipusok((int)$_GET['nem_id']));
            exit;
        }
        if ($_GET['ajax'] === 'get_meretek') {
            $nem_id = $_GET['nem_id'] ?? null;
            $meretek = getMeretek();
            $gyerekNemek = ['fiú', 'leány'];
            if ($nem_id !== null) {
                $stmt = $pdo->prepare("SELECT nev FROM nemek WHERE id = ?");
                $stmt->execute([$nem_id]);
                $nev = $stmt->fetchColumn();
                if (in_array($nev, $gyerekNemek)) {
                    $meretek = array_filter($meretek, fn($m) => in_array($m['meret'], ['S', 'M', 'L', 'XL']));
                } else {
                    $meretRend = ['S', 'M', 'L', 'XL', 'XXL', '32', '34', '36', '38', '40', '42', '44'];

                    usort($meretek, function ($a, $b) use ($meretRend) {
                        $posA = array_search($a['meret'], $meretRend);
                        $posB = array_search($b['meret'], $meretRend);
                        $posA = $posA === false ? PHP_INT_MAX : $posA;
                        $posB = $posB === false ? PHP_INT_MAX : $posB;
                        return $posA <=> $posB;
                    });
                }
            }
            echo json_encode(array_values($meretek));
            exit;
        }
        throw new Exception("Ismeretlen AJAX típus");
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// CREATE (termék létrehozás)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['menu'] === 'create' && isset($_POST['nev'])) {
    require './protected/handle_create.php';
    exit;
}


// UPDATE (termék szerkesztés)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['menu'] === 'update') {
    require './protected/handle_update.php';
    exit;
}

// Törlés
if (isset($_GET['menu']) && $_GET['menu'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM termekek WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['siker_uzenet'] = "A termék törlése sikeres.";
    } catch (PDOException $e) {
        $_SESSION['hiba_uzenet'] = "Hiba történt törléskor: " . $e->getMessage();
    }
    header("Location: start.php?menu=termekek");
    exit;
}

// Terméklista megjelenítése
if (isset($_GET['menu']) && $_GET['menu'] === 'termekek') {
    include './protected/fejlec.php';
    include './protected/menu.php';
    echo '<main class="container py-4">';
    include 'products.php';
    echo '</main>';
    include './protected/lablec.php';
    exit;
}

?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WP 2 - projektvázlat</title>
</head>

<body>
    <header><?php include PROTECTED_DIR . 'fejlec.php'; ?></header>
    <nav><?php include PROTECTED_DIR . 'menu.php'; ?></nav>
    <article><?php require PROTECTED_DIR . 'tartalom.php'; ?></article>
    <footer><?php include PROTECTED_DIR . 'lablec.php'; ?></footer>
</body>

</html>