<?php
// เปิด session และกำหนด config คุกกี้
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_name('repair_session'); // ตั้งชื่อ session เพื่อไม่ชนกับระบบอื่น
session_set_cookie_params([
  'lifetime' => 0,         // อยู่จนปิด browser
  'path'     => '/',
  'domain'   => '',        // ใช้โดเมนปัจจุบัน
  'secure'   => $secure,   // ถ้าใช้ HTTPS ให้ true
  'httponly' => true,
  'samesite' => 'Lax',
]);
session_start(); // ✨ ต้องอยู่หลัง session_name และ set_cookie_params

// เชื่อมต่อฐานข้อมูล
$host = 'localhost';
$db = 'repair_system';
$user = 'root';
$pass = ''; // กรอกตามเครื่อง

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
