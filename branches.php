<?php
require "config.php";

// รับค่าการค้นหา
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$search_like = "%" . $search . "%";

// ตั้งค่าจำนวนต่อหน้า
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) $page = 1;

// 1) นับจำนวนทั้งหมด
$count_sql = "SELECT COUNT(*) AS total 
              FROM branches 
              WHERE branch_name LIKE ? 
                 OR branch_type LIKE ?
                 OR branch_order LIKE ?";
$stmt_count = $conn->prepare($count_sql);
$stmt_count->bind_param("sss", $search_like, $search_like, $search_like);
$stmt_count->execute();
$count_result = $stmt_count->get_result();
$total_records = $count_result->fetch_assoc()['total'];

// 2) คำนวณจำนวนหน้า
$total_pages = ceil($total_records / $limit);

// 3) กำหนด offset
$offset = ($page - 1) * $limit;

// 4) ดึงข้อมูลเฉพาะหน้าปัจจุบัน
$sql = "SELECT * FROM branches 
        WHERE branch_name LIKE ? 
           OR branch_type LIKE ?
           OR branch_order LIKE ?
        ORDER BY 
          CASE branch_type 
            WHEN 'Suki' THEN 1 
            WHEN 'BBQ' THEN 2 
            ELSE 3 
          END,
          branch_order ASC 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $search_like, $search_like, $search_like, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>รายชื่อสาขา</title>
  <link rel="stylesheet" href="assets/style_back_office.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="..." crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="margin-top: 40px;">

  <!-- Navbar -->
  <?php include 'nav.php'; ?>

  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>รายชื่อสาขา</h3>
      <a href="branch_add.php" class="btn btn-success">➕ เพิ่มสาขา</a>
    </div>

    <!-- ฟอร์มค้นหา -->
    <form class="row mb-3" method="GET">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อสาขา..." value="<?php echo htmlspecialchars($search); ?>">
      </div>
      <div class="col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary">ค้นหา</button>
        <a href="<?php echo basename($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary">เคลียร์</a>
      </div>
    </form>

    <!-- ตารางรายชื่อสาขา -->
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>สาขาที่</th>
            <th>ชื่อสาขา</th>
            <th>เบอร์ติดต่อ</th>
            <th class="text-center">จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $row['branch_order']; ?></td>
              <td><?php echo htmlspecialchars($row['branch_type']); ?> - <?php echo htmlspecialchars($row['branch_name']); ?></td>
              <td><?php echo htmlspecialchars($row['phonenumber']); ?></td>
              <td class="text-center">
                <a href="branch_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <?php include "pagination.php"; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>