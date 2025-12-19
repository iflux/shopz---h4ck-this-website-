<?php
$db_host = '127.0.0.1';
$db_name = 'shopz';
$db_user = 'shopz_user';
$db_pass = 'shopz_pass123';

$secret_key = 'supersecretkey123';
$jwt_secret = 'secret';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

function sanitize_weak($input) {
    return str_replace(array("'", '"'), "", $input);
}
