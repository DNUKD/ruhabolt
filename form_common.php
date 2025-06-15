<?php
function selectOption($name, $options, $selectedId = null)
{
    echo "<select class='form-select' name='{$name}' id='{$name}' required>";
    echo "<option value=''>-- Válassz --</option>";
    foreach ($options as $opt) {
        $selected = ($opt['id'] == $selectedId) ? 'selected' : '';
        echo "<option value='{$opt['id']}' {$selected}>{$opt['nev']}</option>";
    }
    echo "</select>";
}

require_once './protected/queries.php';

$nev = $termek['nev'] ?? '';
$ar = $termek['ar'] ?? '';
$keszlet = $termek['keszlet'] ?? '';
$tipus_id = $termek['tipus_id'] ?? '';
$szin_id = $termek['szin_id'] ?? null;
$aktualis_tipus_nev = getTipusNev($tipus_id);
$anyag_id = $termek['anyag_id'] ?? '';
$nem_id = $termek['nem_id'] ?? '';
$fazon_id = $termek['fazon_id'] ?? '';
$hossz_id = $termek['hossz_id'] ?? '';
$meret = $termek['meret'] ?? '';

$szinek = getSzinLista();
$anyagok = getAnyagok();
$nemek = getNemek();
$fazonok = getFazonok();
$hosszak = getHosszak();
?>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="nev" class="form-label">Termék neve</label>
        <input type="text" class="form-control" name="nev" id="nev" value="<?= htmlspecialchars($nev) ?>" required>
    </div>
    <div class="col-md-6">
        <label for="ar" class="form-label">Ár (Ft)</label>
        <input type="number" class="form-control" name="ar" id="arInput" value="<?= htmlspecialchars($ar) ?>">
    </div>
    <div class="col-md-6">
        <label for="keszlet" class="form-label">Készlet (db)</label>
        <input type="number" class="form-control" name="keszlet" id="keszlet" value="<?= htmlspecialchars($keszlet) ?>" required>
    </div>
    <div class="col-md-6">
        <label for="nem" class="form-label">Nem</label>
        <select class="form-select" name="nem_id" id="nem" required>
            <?php foreach ($nemek as $n): ?>
                <option value="<?= $n['id'] ?>" <?= $n['id'] == $nem_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($n['nev']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label for="tipus_id" class="form-label">Típus</label>

        <?php if (isset($termek['tipus_id'])): ?>
            <input type="hidden" id="tipus_eredeti_id" value="<?= $termek['tipus_id'] ?>">
        <?php endif; ?>

        <select class="form-select" name="tipus_id" id="tipus_id" required>
            <option value="">-- Válassz típust --</option>
        </select>
    </div>
    <div class="col-md-6">
        <label for="szin_id" class="form-label">Szín</label>
        <?php selectOption('szin_id', $szinek, $szin_id); ?>
    </div>
    <div class="col-md-6">
        <label for="anyag_id" class="form-label">Anyag</label>
        <?php selectOption('anyag_id', $anyagok, $anyag_id); ?>
    </div>
    <div class="col-md-6">
        <label for="fazon_id" class="form-label">Fazon</label>
        <select class="form-select" name="fazon_id" id="fazon_id" data-eredeti="<?= $fazon_id ?? '' ?>">
            <option value="">-- Válassz fazont --</option>
            <?php foreach ($fazonok as $f): ?>
                <option value="<?= $f['id'] ?>" <?= $f['id'] == $fazon_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($f['nev']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label for="hossz_id" class="form-label">Hossz</label>
        <select class="form-select" name="hossz_id" id="hossz_id" data-eredeti="<?= $hossz_id ?? '' ?>">
            <option value="">-- Válassz hosszt --</option>
            <?php foreach ($hosszak as $h): ?>
                <option value="<?= $h['id'] ?>" <?= $h['id'] == $hossz_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($h['nev']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label for="meret" class="form-label">Méret</label>
        <select class="form-select" name="meret" id="meret">
            <option value="">-- Válassz méretet --</option>
            <?php foreach ($meretek as $m): ?>
                <?php $value = is_array($m) ? $m['meret'] : $m; ?>
                <option value="<?= htmlspecialchars($value) ?>" <?= $value == $meret ? 'selected' : '' ?>>
                    <?= htmlspecialchars($value) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>