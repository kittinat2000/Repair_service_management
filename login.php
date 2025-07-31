<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
  $stmt->execute([$username]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = $user;
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
  }
}
?>

<!-- CSS เฉพาะสำหรับหน้า login -->
<link rel="stylesheet" href="assets/style_login.css">

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Login</title>
</head>

<body>
  <div class="bg-blur"></div>
  <div class="login wrap">
    <div class="h1">Login</div>
    <div class="logo">
      <img src="assets/logo_luckybbq.png" alt="Logo">
      <img src="assets/miracleplanet.jpeg" alt="Logo">
      <img src="assets/logo_luckysuki.png" alt="Logo">
    </div>
    <form action="" method="post">
      <input placeholder="Username" id="username" name="username" type="text">
      <input placeholder="Password" id="password" name="password" type="password">
      <input value="Login" class="btn" type="submit">
    </form>
  </div>
  <?php if (!empty($error)): ?>
    <script>
      window.addEventListener('DOMContentLoaded', function() {
        alert(<?= json_encode($error, JSON_UNESCAPED_UNICODE) ?>);
      });
    </script>
  <?php endif; ?>
</body>

</html>