<?php
require 'config.php';
require 'functions.php';
if (!isLoggedIn() || $_SESSION['user']['role'] !== 'admin') redirect('login.php');

// เพิ่มผู้ใช้
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $allowed_pages = ($role === 'user') ? implode(',', $_POST['allowed_pages'] ?? []) : null;

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, allowed_pages) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $password, $role, $allowed_pages]);

    header("Location: users.php");
    exit;
}

// ลบผู้ใช้
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$id]);
    header("Location: users.php");
    exit;
}

$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>จัดการผู้ใช้</title></head>
<body>

<?php include 'navbar.php'; ?>

<h1>จัดการผู้ใช้</h1>
<a href="dashboard.php">กลับ</a>

<h3>เพิ่มผู้ใช้</h3>
<form method="post">
    <input type="hidden" name="add_user" value="1">
    <input type="text" name="username" required placeholder="Username">
    <input type="password" name="password" required placeholder="Password">
    <select name="role" id="role" onchange="toggleAllowedPages()">
        <option value="user">user</option>
        <option value="admin">admin</option>
    </select><br><br>

    <div id="allowedPagesDiv">
        <label>เลือกสิทธิ์การเข้าถึง:</label><br>
        <input type="checkbox" name="allowed_pages[]" value="details_a.php"> details_a<br>
        <input type="checkbox" name="allowed_pages[]" value="details_b.php"> details_b<br>
        <input type="checkbox" name="allowed_pages[]" value="history.php"> history<br>
    </div><br>

    <button type="submit">เพิ่มผู้ใช้</button>
</form>

<h3>รายชื่อผู้ใช้</h3>
<table border="1" cellpadding="5">
<tr><th>Username</th><th>Role</th><th>Allowed Pages</th><th>ลบ</th></tr>
<?php foreach ($users as $u): ?>
<tr>
    <td><?= htmlspecialchars($u['username']) ?></td>
    <td><?= htmlspecialchars($u['role']) ?></td>
    <td><?= htmlspecialchars($u['allowed_pages'] ?? 'ทั้งหมด') ?></td>
    <td>
        <?php if ($u['username'] !== 'admin'): ?>
            <a href="?delete=<?= $u['id'] ?>" onclick="return confirm('ลบผู้ใช้นี้?')">ลบ</a>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>

<script>
function toggleAllowedPages() {
    const role = document.getElementById('role').value;
    document.getElementById('allowedPagesDiv').style.display = (role === 'user') ? 'block' : 'none';
}
toggleAllowedPages();
</script>
</body>
</html>
