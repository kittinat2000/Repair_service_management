<?php
require 'config.php';

// รับ target จาก URL เช่น it.php?target=IT
$filter_target = $_GET['target'] ?? 'ALL';

// ดึงข้อมูล repair_case พร้อมชื่อสาขา
if ($filter_target == 'ALL') {
  $sql = "SELECT r.id, r.case_number, r.title, r.reporter, r.contact, r.status, r.target, 
            b.branch_type, b.branch_order, b.branch_name
            FROM repair_case r
            LEFT JOIN branches b ON r.branch_id = b.id
            ORDER BY r.created_at DESC";
  $stmt = $conn->prepare($sql);
} else {
  $sql = "SELECT r.id, r.case_number, r.title, r.reporter, r.contact, r.status, r.target, 
            b.branch_type, b.branch_order, b.branch_name
            FROM repair_case r
            LEFT JOIN branches b ON r.branch_id = b.id
            WHERE r.target = ?
            ORDER BY r.created_at DESC";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $filter_target);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <title>📋 จัดการเคสแจ้งซ่อม</title>
  <link rel="stylesheet" href="assets/style_back_office.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
  <?php include 'nav.php'; ?>

  <div class="container py-5">
    <h3 class="mb-4">📋 จัดการเคสแจ้งซ่อม</h3>

    <!-- ปุ่มกรอง target -->
    <div class="mb-3">
      <a href="manage_cases.php?target=ALL" class="btn btn-secondary btn-sm <?= $filter_target == 'ALL' ? 'active' : '' ?>">All</a>
      <a href="manage_cases.php?target=IT" class="btn btn-primary btn-sm <?= $filter_target == 'IT' ? 'active' : '' ?>">IT</a>
      <a href="manage_cases.php?target=MT" class="btn btn-warning btn-sm <?= $filter_target == 'MT' ? 'active' : '' ?>">MT</a>
      <a href="manage_cases.php?target=Withdraw" class="btn btn-success btn-sm <?= $filter_target == 'Withdraw' ? 'active' : '' ?>">Withdraw</a>
    </div>

    <table class="table table-bordered table-hover bg-white">
      <thead class="table-primary">
        <tr>
          <th>Case Number</th>
          <th>สาขา</th>
          <th>หัวข้อ</th>
          <th>ผู้แจ้ง</th>
          <th>เบอร์ติดต่อ</th>
          <th>สถานะ</th>
          <th>ดำเนินการ</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['case_number']) ?></td>
              <td><?= htmlspecialchars($row['branch_type'] . " สาขาที่ " . $row['branch_order'] . " " . $row['branch_name']) ?></td>
              <td><?= htmlspecialchars($row['title']) ?></td>
              <td><?= htmlspecialchars($row['reporter']) ?></td>
              <td><?= htmlspecialchars($row['contact']) ?></td>
              <td><?= htmlspecialchars($row['status']) ?></td>
              <td>
                <!-- เปลี่ยนสถานะ -->
                <form action="function/process_case.php" method="POST" class="d-inline">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <input type="hidden" name="action" value="change_status">
                  <?php if ($row['status'] != 'เสร็จสิ้น'): ?>
                    <button type="submit" class="btn btn-success btn-sm">เสร็จสิ้น</button>
                  <?php endif; ?>
                  <?php if ($row['status'] != 'กำลังดำเนินการ'): ?>
                    <input type="hidden" name="new_status" value="กำลังดำเนินการ">
                    <button type="submit" class="btn btn-warning btn-sm">ดำเนินการ</button>
                  <?php endif; ?>
                </form>

                <!-- เปลี่ยน target -->
                <form action="function/process_case.php" method="POST" class="d-inline">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <input type="hidden" name="action" value="change_target">
                  <select name="new_target" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                    <option value=""><?= htmlspecialchars($row['target']) ?></option>
                    <?php
                    $targets = ['IT', 'MT', 'Withdraw'];
                    foreach ($targets as $t) {
                      if ($t != $row['target']) {
                        echo "<option value='$t'>$t</option>";
                      }
                    }
                    ?>
                  </select>
                </form>

                <!-- ลบ -->
                <form action="function/process_case.php" method="POST" class="d-inline" onsubmit="return confirm('คุณต้องการลบเคสนี้หรือไม่?');">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <input type="hidden" name="action" value="delete">
                  <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center">ไม่มีข้อมูล</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>

</html>