<?php
if (isset($_GET['menu'])) {
    $menu = $_GET['menu'];
} else {
    $menu = 'home';
}

$elerheto = ['home', 'products', 'create'];

if (in_array($menu, $elerheto)) {
    require_once ROOT_DIR . $menu . '.php';
} else {
    echo "<p class='text-danger'>Az oldal nem elérhető.</p>";
}
?>