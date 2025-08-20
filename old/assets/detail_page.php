<?php
include 'navbar.php';
require 'functions.php';

if (!isLoggedIn()) redirect('login.php');

// ลบข้อมูล
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $action = "ลบข้อมูล";
  $updated_by = $_SESSION['user']['username'] ?? 'system';

  // บันทึกประวัติ
  $stmt = $pdo->prepare("INSERT INTO repair_history (repair_id, action, updated_by) VALUES (?, ?, ?)");
  if (!$stmt->execute([$id, $action, $updated_by])) {
    echo "SQL Error (history): ";
    print_r($stmt->errorInfo());
    exit;
  }

  // เปลี่ยนสถานะลบ (soft delete)
  $stmt = $pdo->prepare("UPDATE repairs SET deleted=1 WHERE id=?");
  if (!$stmt->execute([$id])) {
    echo "SQL Error (soft delete): ";
    print_r($stmt->errorInfo());
    exit;
  }

  header("Location: details_a.php");
  exit;
}

// โยนให้ B
if (isset($_GET['move']) && isset($_GET['to'])) {
  $id = $_GET['move'];
  $moveTarget = $_GET['to'];

  if (!in_array($moveTarget, ['A', 'B', 'C'])) {
    exit("target ไม่ถูกต้อง");
  }

  $stmt = $pdo->prepare("UPDATE repairs SET target=? WHERE id=?");
  $stmt->execute([$moveTarget, $id]);

  $stmt = $pdo->prepare("INSERT INTO repair_history (repair_id, action, updated_by) VALUES (?, ?, ?)");
  $stmt->execute([$id, "โยนข้อมูลไปให้ $moveTarget", $_SESSION['user']['username']]);

  header("Location: details_$target.php");
  exit;
}

$where = "WHERE target='$target' AND deleted=0";
$params = [];

// filter
if (!empty($_GET['status'])) {
  $where .= " AND status = ?";
  $params[] = $_GET['status'];
}

if (!empty($_GET['branch_prefix'])) {
  $where .= " AND branch_prefix = ?";
  $params[] = $_GET['branch_prefix'];
}

if (!empty($_GET['search'])) {
  $search = '%' . $_GET['search'] . '%';
  $where .= " AND (case_number LIKE ? OR branch LIKE ? OR title LIKE ? OR description LIKE ? OR reporter LIKE ? OR contact LIKE ?)";
  array_push($params, $search, $search, $search, $search, $search, $search);
}

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// นับจำนวนทั้งหมด
$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM repairs $where");
$totalStmt->execute($params);
$totalRows = $totalStmt->fetchColumn();
$totalPages = ceil($totalRows / $limit);

// ดึงข้อมูลจำกัดตามหน้า
$query = "SELECT * FROM repairs $where ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $pdo->prepare($query);

// bind params ของ WHERE
foreach ($params as $k => $v) {
  $stmt->bindValue($k + 1, $v);  // index เริ่มจาก 1
}

// bind LIMIT และ OFFSET เป็น int
$stmt->bindValue(count($params) + 1, $limit, PDO::PARAM_INT);
$stmt->bindValue(count($params) + 2, $offset, PDO::PARAM_INT);

$stmt->execute();
$repairs = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>แจ้งซ่อม <?= htmlspecialchars($target) ?></title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <h1 class="head">แจ้งซ่อม <?= htmlspecialchars($target) ?></h1>
  <?php include 'assets/filter.php'; ?>
  <table border="1" cellpadding="5">
    <tr>
      <th>Status</th>
      <th>Case</th>
      <th>สาขา</th>
      <th>เรื่อง</th>
      <th>อาการ</th>
      <th>ผู้แจ้ง</th>
      <th>เบอร์</th>
      <th>รูป</th>
      <th>จัดการ</th>
    </tr>
    <?php foreach ($repairs as $r): ?>
      <tr>
        <?php
        // เปลี่ยนสี status (แก้ได้ที่ css)
        $statusClass = '';
        switch ($r['status']) {
          case 'แจ้งซ่อม':
            $statusClass = 'status-red';
            break;
          case 'กำลังดำเนินการ':
            $statusClass = 'status-yellow';
            break;
          case 'เสร็จสิ้น':
            $statusClass = 'status-green';
            break;
          default:
            $statusClass = '';
        }
        //เปลียนสีตาม branch_prefix
        $branchPrefix = htmlspecialchars($r['branch_prefix'] ?? '');
        $branch = htmlspecialchars($r['branch'] ?? '');
        $color = ($branchPrefix === 'suki') ? 'red' : 'black';
        ?>

        <td class="fit-content <?= $statusClass ?>"><?= htmlspecialchars($r['status']) ?></td>
        <td class="fit-content"><?= htmlspecialchars($r['case_number']) ?></td>
        <td><span style="color: <?= $color ?>;"><?= $branchPrefix ?></span>-<?= $branch ?></td>
        <td><?= htmlspecialchars($r['title']) ?></td>
        <td class="ellipsis-text""><?= htmlspecialchars($r['description']) ?></td>
                <td><?= htmlspecialchars($r['reporter']) ?></td>
                <td><?= htmlspecialchars($r['contact']) ?></td>
                <td class=" fit-content">
          <div class="img-icon">
            <?php for ($i = 1; $i <= 3; $i++): ?>
              <?php if (!empty($r["image$i"])): ?>
                <!-- Icon image แทนรูปจริง -->
                <i class="fa fa-image" style="font-size: 24px; margin-right: 10px; cursor: pointer;"
                  onclick="showImage('uploads/<?= htmlspecialchars($r["image$i"]) ?>')"></i>
              <?php endif; ?>
            <?php endfor; ?>
          </div>
        </td>

        <td class="fit-content">
          <!-- ปุ่มคัดลอก -->
          <button class="btn copy-btn" data-tooltip="คัดลอกข้อมูล" onclick="copyRepairData(<?= $r['id'] ?>)">
            <i class="fa fa-copy"></i>
          </button>
          <!-- ปุ่มอัพเดท -->
          <a href="update_details.php?id=<?= htmlspecialchars($r['id']) ?>">
            <button type="button" class="btn update-btn" data-tooltip="อัพเดท">
              <i class="fa fa-edit"></i>
            </button>
          </a>
          <!-- ปุ่มโยนให้ ฝั่งตรงข้าม -->
          <?php if ($target == 'C'): ?>
            <button type="button" class="btn move-btn" data-tooltip="โยนให้ <?= htmlspecialchars($discriptionA) ?>" onclick="showConfirm(<?= $r['id'] ?>, 'move','<?= $moveTargetA ?>')">
              <i class="fa fa-share"></i>IT
            </button>
            <button type="button" class="btn move-btn" data-tooltip="โยนให้ <?= htmlspecialchars($discriptionB) ?>" onclick="showConfirm(<?= $r['id'] ?>, 'move','<?= $moveTargetB ?>')">
              <i class="fa fa-share"></i>MT
            </button>
          <?php else: ?>
            <button type="button" class="btn move-btn" data-tooltip="โยนให้ <?= htmlspecialchars($discription) ?>" onclick="showConfirm(<?= $r['id'] ?>, 'move','<?= $moveTarget ?>')">
              <i class="fa fa-share"></i>
            </button>
          <?php endif; ?>
          <!-- ปุ่มลบ -->
          <button type="button" class="btn delete-btn" data-tooltip="ลบ" onclick="showConfirm(<?= $r['id'] ?>, 'delete')">
            <i class="fa fa-trash"></i>
          </button>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <?php include 'assets/pagination.php'; ?>

  <div id="lightbox" onclick="closeLightbox()">
    <span id="lightbox-close">&times;</span>
    <img id="lightbox-img" src="" alt="รูปขยาย">
  </div>

  <?php include 'assets/model_box_confirm.php'; ?>

  <script src="assets/script.js"></script>

</body>

</html>