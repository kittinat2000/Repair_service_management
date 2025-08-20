<?php
include "navbar.php";

$id = $_GET['id'] ?? null;
$redirect = $_GET['redirect'] ?? 'users.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $username = $_POST['username'];
  $role = $_POST['role'];

  $stmt = $pdo->prepare("UPDATE users SET username=?, role=? WHERE id=?");
  $stmt->execute([$username, $role, $id]);

  // ✅ กลับไปหน้าก่อนหน้า
  header("Location: $redirect");
  exit;
}

// ดึงข้อมูลผู้ใช้
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$id]);
$user = $stmt->fetch();
?>

<!-- แบบฟอร์มแก้ไข -->
<form method="post">
  <input type="hidden" name="id" value="<?= $user['id'] ?>">
  <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">
  
  ชื่อผู้ใช้: <input type="text" name="username" value="<?= $user['username'] ?>"><br>
  บทบาท: 
  <select name="role">
    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
    <option value="it" <?= $user['role'] === 'it' ? 'selected' : '' ?>>IT</option>
    <option value="mt" <?= $user['role'] === 'mt' ? 'selected' : '' ?>>MT</option>
  </select><br>

  <button type="submit">บันทึก</button>
</form>
