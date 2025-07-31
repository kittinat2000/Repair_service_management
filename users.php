<?php
include 'navbar.php';
require 'functions.php';

if (!isLoggedIn()) redirect('login.php');

// อนุญาตเฉพาะ admin
if (($_SESSION['user']['role'] ?? '') !== 'admin') {
  redirect('dashboard.php');
}

// ---------- เพิ่มผู้ใช้ ----------
$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $role     = $_POST['role'] ?? 'it';

  // allowed_pages จาก checkbox (array) + กล่องกรอกเพิ่ม (string)
  $ap_chk = $_POST['allowed_pages'] ?? [];               // array
  $ap_extra = trim($_POST['allowed_pages_extra'] ?? ''); // string
  if ($ap_extra !== '') {
    // แตกด้วยคอมมา เพิ่มเข้ากับ array
    foreach (explode(',', $ap_extra) as $p) {
      $p = trim($p);
      if ($p !== '') $ap_chk[] = $p;
    }
  }
  // กำจัดซ้ำ/ว่าง แล้วรวมเป็นสตริง
  $ap_chk = array_unique(array_filter($ap_chk, fn($v) => $v !== ''));
  $allowed_pages = implode(',', $ap_chk);

  // ตรวจสอบข้อมูล
  $validRoles = ['admin', 'it', 'mt'];
  if ($username === '' || $password === '') {
    $error = 'กรุณากรอก Username/Password';
  } elseif (!in_array($role, $validRoles, true)) {
    $error = 'role ไม่ถูกต้อง';
  } else {
    // ตรวจสอบชื่อซ้ำ
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username=?");
    $stmt->execute([$username]);
    $exists = (int)$stmt->fetchColumn();

    if ($exists > 0) {
      $error = 'มีชื่อผู้ใช้นี้อยู่แล้ว';
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO users (username, password, role, allowed_pages) VALUES (?, ?, ?, ?)");
      if ($stmt->execute([$username, $hash, $role, $allowed_pages])) {
        $success = 'เพิ่มผู้ใช้สำเร็จ';
      } else {
        $error = 'เพิ่มผู้ใช้ไม่สำเร็จ';
      }
    }
  }
}

// ---------- แสดงรายชื่อผู้ใช้ (แบ่งหน้า) ----------
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// นับทั้งหมด
$totalStmt = $pdo->query("SELECT COUNT(*) FROM users");
$totalRows  = (int)$totalStmt->fetchColumn();
$totalPages = (int)ceil($totalRows / $limit);

// ดึงรายการ
$listStmt = $pdo->prepare("SELECT id, username, role, allowed_pages FROM users ORDER BY id ASC LIMIT ? OFFSET ?");
$listStmt->bindValue(1, $limit,  PDO::PARAM_INT);
$listStmt->bindValue(2, $offset, PDO::PARAM_INT);
$listStmt->execute();
$users = $listStmt->fetchAll(PDO::FETCH_ASSOC);

// หน้า ที่แนะนำในระบบ (ไว้ทำ checkbox)
$knownPages = [
  '1' => 'Dashboard',
  '2' => 'แจ้งซ่อม-IT',
  '3' => 'แจ้งซ่อม-MT',
  '4' => 'เบิกของ',
  '5' => 'ประวัติ',
  '6' => 'รายงาน',
  '7' => 'จัดการผู้ใช้'
];

?>
<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="utf-8">
  <title>จัดการผู้ใช้</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* กล่องรวมหน้า */
    .users-wrapper {
      max-width: 1100px;
      margin: 20px auto;
      padding: 0 12px;
    }

    .card {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 16px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
    }

    .card+.card {
      margin-top: 16px;
    }

    .card h2 {
      margin: 0 0 10px;
    }

    /* ฟอร์มเพิ่มผู้ใช้ */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .form-grid .full {
      grid-column: 1 / -1;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      font-weight: 600;
      margin-bottom: 6px;
    }

    .form-group input[type="text"],
    .form-group input[type="password"],
    .form-group select,
    .form-group textarea {
      padding: 10px 12px;
      border: 2px solid #000;
      border-radius: 8px;
      font-size: 15px;
      outline: none;
    }

    .form-group textarea {
      min-height: 80px;
    }

    .ap-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 8px 14px;
      margin-top: 8px;
    }

    .ap-grid label {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn {
      border: 2px solid #000;
      background: #fff;
      color: #000;
      padding: 10px 14px;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 700;
      transition: background .2s, color .2s, transform .05s;
    }

    .btn:hover {
      background: #000;
      color: #fff;
    }

    .btn:active {
      transform: translateY(1px);
    }

    .btn-primary {
      background: #000;
      color: #fff;
    }

    .btn-primary:hover {
      background: #111;
    }

    /* ตารางผู้ใช้ */
    table.users {
      width: 100%;
      border-collapse: collapse;
      margin-top: 8px;
    }

    table.users th,
    table.users td {
      border: 1px solid #ddd;
      padding: 8px 10px;
      text-align: left;
    }

    table.users th {
      background: #f6f6f6;
    }

    .mono {
      font-size: 13px;
      color: #333;
    }

    .ellipsis {
      max-width: 360px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* pagination */
    .pagination {
      margin: 14px 0;
      text-align: center;
    }

    .pagination a,
    .pagination strong {
      display: inline-block;
      margin: 0 3px;
      padding: 6px 10px;
      text-decoration: none;
      border: 2px solid #000;
      border-radius: 6px;
      color: #000;
      background: #fff;
      font-weight: 700;
    }

    .pagination strong {
      background: #000;
      color: #fff;
    }

    .flash {
      margin: 10px 0;
      padding: 10px 12px;
      border-radius: 8px;
    }

    .flash.success {
      background: #F0FFF4;
      border: 1px solid #38A169;
      color: #22543D;
    }

    .flash.error {
      background: #FFF5F5;
      border: 1px solid #E53E3E;
      color: #742A2A;
    }

    @media (max-width: 720px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <h1 class="head">จัดการบัญชีผู้ใช้</h1>

  <div class="users-wrapper">
    <button onclick="toggle_show_hide()">+</button>
    <div class="card" id="show-hide" style="display: none;">
      <h2>เพิ่มบัญชีผู้ใช้</h2>
      <?php if ($success): ?><div class="flash success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
      <?php if ($error):   ?><div class="flash error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

      <form method="post">
        <div class="form-grid">
          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required autocomplete="username">
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required autocomplete="new-password">
          </div>
          <div class="form-group">
            <label>Role</label>
            <select name="role" required>
              <option value="admin">admin</option>
              <option value="it">it</option>
              <option value="mt">mt</option>
            </select>
          </div>
          <div class="form-group full">
            <label>Allowed pages</label>
            <div class="ap-grid">
              <?php foreach ($knownPages as $file => $label): ?>
                <label><input type="checkbox" name="allowed_pages[]" value="<?= htmlspecialchars($file) ?>"> <?= htmlspecialchars($label) ?> <span class="mono">(<?= htmlspecialchars($file) ?>)</span></label>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="full">
            <button class="btn btn-primary" type="submit" name="create_user" value="1">บันทึกผู้ใช้</button>
          </div>
        </div>
      </form>
    </div>

    <div>
      <h2>รายชื่อผู้ใช้ (ทั้งหมด <?= $totalRows ?>)</h2>
      <table class="users">
        <tr>
          <th style="width:70px;">ID</th>
          <th>Username</th>
          <th style="width:110px;">Role</th>
          <th>Allowed pages</th>
          <th>Action</th>
        </tr>
        <?php foreach ($users as $u): ?>
          <tr>
            <td class="mono"><?= (int)$u['id'] ?></td>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><strong><?= htmlspecialchars($u['role']) ?></strong></td>
            <td class="ellipsis mono" title="<?= htmlspecialchars($u['allowed_pages']) ?>">
              <?= htmlspecialchars($u['allowed_pages'] ?: '-') ?>
            </td>
            <td>
              <a href="edit_users.php?id=<?= $u['id'] ?>" style="text-decoration: none;">
                <button type="button">แก้ไข</button>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$users): ?>
          <tr>
            <td colspan="4" style="text-align:center;color:#666;">(ไม่มีข้อมูล)</td>
          </tr>
        <?php endif; ?>
      </table>

      <!-- Pagination -->
      <div class="pagination">
        <?php if ($page > 1): ?>
          <a href="?page=1">&laquo;</a>
        <?php endif; ?>

        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
          <?php if ($i === $page): ?>
            <strong><?= $i ?></strong>
          <?php else: ?>
            <a href="?page=<?= $i ?>"><?= $i ?></a>
          <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <a href="?page=<?= $totalPages ?>">&raquo;</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    function toggle_show_hide() {
      var div = document.getElementById("show-hide");
      if (div.style.display === "none") {
        div.style.display = "block";
      } else {
        div.style.display = "none";
      }
    }
  </script>

</body>

</html>