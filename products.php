<?php
require_once 'protected/config.php';
require_once 'protected/queries.php';

if (isset($_SESSION['hiba_uzenet'])): ?>
    <div class="alert alert-danger container mt-4"><?= $_SESSION['hiba_uzenet'] ?></div>
    <?php unset($_SESSION['hiba_uzenet']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['siker_uzenet'])): ?>
    <div class="alert alert-success container mt-4"><?= $_SESSION['siker_uzenet'] ?></div>
    <?php unset($_SESSION['siker_uzenet']); ?>
<?php endif; ?>

<?php

try {
    $stmt = $pdo->query("SELECT 
        t.id, t.nev, t.ar, t.keszlet, t.tipus_id, t.nem_id, 
        t.szin_id, sz.nev AS szin_nev,
        t.anyag_id, a.nev AS anyag_nev,
        t.hossz_id, h.nev AS hossz_nev,
        t.fazon_id, fz.nev AS fazon_nev,
        t.meret,
        ti.nev AS tipus_nev, n.nev AS nem_nev
    FROM termekek t
    JOIN tipusok ti ON t.tipus_id = ti.id
    JOIN nemek n ON t.nem_id = n.id
    LEFT JOIN szinek sz ON t.szin_id = sz.id
    LEFT JOIN anyagok a ON t.anyag_id = a.id
    LEFT JOIN hosszak h ON t.hossz_id = h.id
    LEFT JOIN fazonok fz ON t.fazon_id = fz.id
    ORDER BY t.nev");

    $termekek = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p class='text-danger'>Hiba: {$e->getMessage()}</p>";
    return;
}
?>

<h2 class="mt-4 mb-3">Termékek</h2>
<?php if (count($termekek) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-nowrap">
            <thead class="table-light">
                <tr>
                    <th>Nem</th>
                    <th>Típus</th>
                    <th>Méret</th>
                    <th>Szín</th>
                    <th>Hossz</th>
                    <th>Anyag</th>
                    <th>Fazon</th>
                    <th>Név</th>
                    <th>Ár</th>
                    <th>Készlet</th>
                    <th>Művelet</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($termekek as $termek): ?>
                    <tr>
                        <td><?= htmlspecialchars($termek['nem_nev']) ?></td>
                        <td><?= htmlspecialchars($termek['tipus_nev']) ?></td>
                        <td><?= htmlspecialchars($termek['meret']) ?></td>
                        <td><?= htmlspecialchars($termek['szin_nev']) ?></td>
                        <td><?= !empty($termek['hossz_nev']) ? htmlspecialchars($termek['hossz_nev']) : '–' ?></td>
                        <td><?= htmlspecialchars($termek['anyag_nev']) ?></td>
                        <td><?= !empty($termek['fazon_nev']) ? htmlspecialchars($termek['fazon_nev']) : '–' ?></td>
                        <td><?= htmlspecialchars($termek['nev']) ?></td>
                        <td><?= number_format($termek['ar'], 0, '', ' ') ?></td>
                        <td><?= htmlspecialchars($termek['keszlet']) ?></td>
                        <td class="text-nowrap">
                            <a href="edit.php?id=<?= $termek['id'] ?>" class="btn btn-warning btn-sm">Szerkesztés</a>
                            <a href="start.php?menu=delete&id=<?= $termek['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Biztosan törölni szeretnéd?')">Törlés</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>Nincs elérhető termék.</p>
<?php endif; ?>