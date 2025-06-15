<?php 

$host = '127.0.0.1';
$port = '3307';
$db = 'ruhabolt';
$user = 'root';
$pass = '';

try{
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die("kapcsolódási hiba: ".$e->getMessage());
}

define('BASE_URL', 'http://localhost/projekt');

define('ROOT_DIR', './');
define('PROTECTED_DIR', ROOT_DIR.'protected/');
define('PUBLIC_DIR', ROOT_DIR.'public/');