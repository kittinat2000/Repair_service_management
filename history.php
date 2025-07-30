<?php
require 'config.php';
require 'functions.php';

if (!isLoggedIn()) redirect('login.php');

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// นับจำนวนทั้งหมด
$countStmt = $pdo->query("SELECT COUNT(*) FROM repair_history");
$totalRows = $countStmt->fetchColumn();
$totalPages = ceil($totalRows / $limit);

// ดึงข้อมูลแบบแบ่งหน้า
$stmt = $pdo->prepare("
  SELECT h.*, r.case_number 
  FROM repair_history h 
  JOIN repairs r ON h.repair_id = r.id 
  ORDER BY h.updated_at DESC 
  LIMIT ? OFFSET ?
");
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$history = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>ประวัติการดำเนินการ</title>
</head>

<body>
    <?php include 'navbar.php'; ?>
      <h1 class="head">ประวัติการดำเนินการ</h1>

    <table border="1" cellpadding="5">
        <tr>
            <th>เคส</th>
            <th>การดำเนินการ</th>
            <th>โดย</th>
            <th>เมื่อ</th>
        </tr>
        <?php foreach ($history as $h): ?>
            <tr>
                <td><?= htmlspecialchars($h['case_number']) ?></td>
                <td><?= htmlspecialchars($h['action']) ?></td>
                <td><?= htmlspecialchars($h['updated_by']) ?></td>
                <td><?= $h['updated_at'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php include 'assets/pagination.php'; ?>
</body>

</html>