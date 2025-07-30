<?php
session_start();
$host = 'localhost';
$db = 'repair_system';
$user = 'root';
$pass = ''; // กรอกตาม MySQL ของคุณ

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
