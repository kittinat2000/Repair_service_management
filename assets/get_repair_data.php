<?php
require '../config.php'; // include database connect

header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;
if (!$id) {
  echo json_encode(['error' => 'No id']);
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM repairs WHERE id=?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'ไม่พบข้อมูล']);
}
?>