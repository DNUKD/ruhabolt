<?php
require_once 'protected/config.php';
require_once 'protected/queries.php';
?>

<?php if (isset($_SESSION['hiba_uzenet'])): ?>
    <div class="alert alert-danger container mt-4"><?= $_SESSION['hiba_uzenet'] ?></div>
    <?php unset($_SESSION['hiba_uzenet']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['siker_uzenet'])): ?>
    <div class="alert alert-success container mt-4"><?= $_SESSION['siker_uzenet'] ?></div>
    <?php unset($_SESSION['siker_uzenet']); ?>
<?php endif; ?>


<?php

$fazon_id = isset($_POST['fazon_id']) && $_POST['fazon_id'] !== '' ? (int)$_POST['fazon_id'] : null;
$hossz_id = isset($_POST['hossz_id']) && $_POST['hossz_id'] !== '' ? (int)$_POST['hossz_id'] : null;

$tipus_id = $_POST['tipus_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nev'], $_POST['tipus_id'], $_POST['nem_id'])) {
        $nev = $_POST['nev'];
        $ar = trim($_POST['ar']) === '' ? null : floatval($_POST['ar']);
        $tipus_id = $_POST['tipus_id'];
        $nem_id = $_POST['nem_id'];
        $szin_id = $_POST['szin_id'];
        $meret = $_POST['meret'];
        $anyag_id = $_POST['anyag_id'];
        $keszlet = $_POST['keszlet'];
        $leiras = $_POST['leiras'] ?? '';
        $fazon_id = $_POST['fazon_id'] ?? null;
        $hossz_id = $_POST['hossz_id'] ?? null;
    }

    if (isset($_POST['nem_id']) && !isset($_POST['nev'])) {
        $nem_nev = $_POST['nem_id'];

        $stmt = $pdo->prepare("SELECT id FROM nemek WHERE nev = ?");
        $stmt->execute([$nem_nev]);
        $nem_id = $stmt->fetchColumn();

        if (!$nem_id) {
            $_SESSION['hiba_uzenet'] = "Érvénytelen nem: <strong>" . htmlspecialchars($nem_nev) . "</strong>";
            header("Location: start.php?menu=create");
            exit;
        }

        $nem_id_hidden = $nem_id;

        $tipusok = getCelcsoportSzerintiTipusok($nem_id);
        $szinek = $pdo->query("SELECT id, nev FROM szinek ORDER BY nev")->fetchAll();
        $anyagok = $pdo->query("SELECT id, nev FROM anyagok ORDER BY nev")->fetchAll();
        $meretek = $pdo->query("SELECT meret FROM meretek ORDER BY sorrend")->fetchAll();

        $gyerekNemek = ['fiú', 'leány'];
        if (in_array($nem_nev, $gyerekNemek)) {
            $meretek = array_filter($meretek, fn($m) => in_array($m['meret'], ['S', 'M', 'L', 'XL']));
        } else {
            usort($meretek, function ($a, $b) {
                return strnatcasecmp($a['meret'], $b['meret']);
            });
        }

?>
        <main>
            <div class="container px-3 px-md-5 py-4">
                <h2 class="text-center mb-4">Új termék felvitele</h2>
                <form method="POST" action="start.php?menu=create" class="row g-3">
                    <input type="hidden" name="nem_id" value="<?= htmlspecialchars($nem_id) ?>">
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Típus</th>
                                    <th>Méret</th>
                                    <th>Szín</th>
                                    <th>Hossz</th>
                                    <th>Anyag</th>
                                    <th>Fazon</th>
                                    <th>Név</th>
                                    <th>Ár (Ft)</th>
                                    <th>Készlet</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Típus">
                                        <select name="tipus_id" id="tipus_id">
                                            <option value="">--</option>
                                            <?php foreach ($tipusok as $tipus): ?>
                                                <option value="<?= $tipus['id'] ?>"><?= htmlspecialchars($tipus['nev']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td data-label="Méret">
                                        <select name="meret" id="meret">
                                            <option value="">--</option>
                                            <?php foreach ($meretek as $m): ?>
                                                <option><?= htmlspecialchars($m['meret']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td data-label="Szín">
                                        <select name="szin_id" id="szin_id">
                                            <option value="">--</option>
                                            <?php foreach ($szinek as $szin): ?>
                                                <option value="<?= $szin['id'] ?>"><?= htmlspecialchars($szin['nev']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td data-label="Hossz">
                                        <select name="hossz_id" id="hossz_id">
                                            <option value="">--</option>
                                        </select>
                                    </td>
                                    <td data-label="Anyag">
                                        <select name="anyag_id" id="anyag_id">
                                            <option value="">--</option>
                                            <?php foreach ($anyagok as $a): ?>
                                                <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nev']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td data-label="Fazon">
                                        <select name="fazon_id" id="fazon_id">
                                            <option value="">--</option>
                                        </select>
                                    </td>
                                    <td data-label="Név">
                                        <input type="text" name="nev" id="nev">
                                    </td>
                                    <td data-label="Ár (Ft)">
                                        <input type="number" name="ar" id="arInput">
                                        <div id="arTartomanySzoveg"></div>
                                        <div id="arHiba" style="color: red;"></div>
                                    </td>
                                    <td data-label="Készlet">
                                        <input type="number" name="keszlet" id="keszlet">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </table>
                        <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                            <button type="submit" class="btn my-custom-blue m-1" style="min-width: 160px;">Mentés</button>
                        </div>
                    </div>
                </form>
            </div>
        </main>

        <script src="public/js/termek.js?v=<?= time() ?>"></script>

    <?php
    }
} else {
    ?>
    <h2 class="text-center mt-4">Új termék hozzáadása</h2>
    <form method="post" action="start.php?menu=create" class="text-center">
        <button type="submit" name="nem_id" value="férfi" class="btn my-custom-blue m-1">Férfi</button>
        <button type="submit" name="nem_id" value="női" class="btn my-custom-blue m-1">Női</button>
        <button type="submit" name="nem_id" value="unisex" class="btn my-custom-blue m-1">Unisex</button>
        <button type="submit" name="nem_id" value="leány" class="btn my-custom-blue m-1">Leány</button>
        <button type="submit" name="nem_id" value="fiú" class="btn my-custom-blue m-1">Fiú</button>
    </form>
<?php
}
?>