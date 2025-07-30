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

<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: url('assets/bg.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    /* สร้างพื้นหลังเบลอเฉพาะเลเยอร์ */
    .bg-blur {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(10px);
        /* เอฟเฟกต์เบลอ */
        background-color: rgba(0, 0, 0, 0.3);
        /* สีดำโปร่งแสงเล็กน้อย */
        z-index: -1;
        /* ให้พื้นหลังอยู่ด้านหลัง */
    }

    /* ฟอร์มล็อคอิน */
    .login-card {
        position: relative;
        z-index: 1;
        /* ให้อยู่เหนือพื้นหลัง */
        background-color: rgba(255, 255, 255, 0.95);
        /* สีขาวโปร่งแสง */
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    /* จัดตำแหน่งฟอร์มให้อยู่กึ่งกลาง */
    .content-center {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    /* โลโก้ */
    .logo img {
        display: flex;
        justify-content: center;
        width: 100px;
        /* ขนาดโลโก้ */
        margin: 10px;
    }
</style>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login</title>
</head>

<body>
    <div class="bg-blur"></div>
    <div>
        <h1>Login</h1>
        <div class="logo">
            <img src="assets/logo_luckybbq.png" alt="Logo">
            <img src="assets/logo_luckysuki.png" alt="Logo">
            <img src="assets/miracleplanet.jpeg" alt="Logo">
        </div>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" required placeholder="Username"><br>
            <input type="password" name="password" required placeholder="Password"><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>