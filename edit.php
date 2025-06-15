<?php
session_start();
require_once './protected/config.php';
require_once './protected/queries.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['hiba_uzenet'] = "Hiányzó termék azonosító.";
    header("Location: start.php?menu=termekek");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM termekek WHERE id = ?");
$stmt->execute([$id]);
$termek = $stmt->fetch();

if (!$termek) {
    $_SESSION['hiba_uzenet'] = "A megadott termék nem található.";
    header("Location: start.php?menu=termekek");
    exit;
}

$tipus_id = $termek['tipus_id'] ?? '';
$meretek = $pdo->query("SELECT meret FROM meretek ORDER BY sorrend")->fetchAll(PDO::FETCH_ASSOC);
$gyerekNemek = ['fiú', 'leány'];
if (isset($termek['nem_id'])) {
    $nemNev = getNemNev($termek['nem_id']);
    if (in_array($nemNev, $gyerekNemek)) {
        $meretek = array_filter($meretek, fn($m) => in_array($m['meret'], ['S', 'M', 'L', 'XL']));
    } else {
        usort($meretek, fn($a, $b) => strnatcasecmp($a['meret'], $b['meret']));
    }
}

include './protected/fejlec.php';
include './protected/menu.php';
?>

<main class="container py-4">
    <h2 class="mb-4">Termék szerkesztése</h2>
    <form method="POST" action="start.php?menu=update" class="row g-3">
        <input type="hidden" name="id" value="<?= htmlspecialchars($termek['id']) ?>">
        <?php include 'form_common.php'; ?>
        <div class="text-end">
            <button type="submit" class="btn btn-success">Mentés</button>
            <a href="start.php?menu=termekek" class="btn btn-secondary ms-2">Mégse</a>
        </div>
    </form>
</main>

<script>
    console.log("tipus_eredeti_id:", document.getElementById("tipus_eredeti_id")?.value);
    console.log("tipus_id:", document.getElementById("tipus_id")?.value);
</script>


<?php include './protected/lablec.php'; ?>