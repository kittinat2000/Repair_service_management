<?php
require 'config.php'; // ต้องอยู่ก่อนใช้งาน $_SESSION

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

// กำหนดหน้าที่สามารถเข้าถึงได้ โดยระบุเป็นตัวเลข และชื่อที่จะแสดง
$allMenus = [
  1 => ['file' => 'dashboard.php', 'label' => 'Dashboard'],
  2 => ['file' => 'details_a.php', 'label' => 'แจ้งซ่อม-IT'],
  3 => ['file' => 'details_b.php', 'label' => 'แจ้งซ่อม-MT'],
  4 => ['file' => 'details_c.php', 'label' => 'เบิกของ'],
  5 => ['file' => 'history.php',    'label' => 'ประวัติ'],
  6 => ['file' => 'report.php',     'label' => 'รายงาน'],
  7 => ['file' => 'users.php',      'label' => 'บัญชีผู้ใช้'],
];

$allowedPages = isset($_SESSION['user']['allowed_pages'])
  ? array_map('intval', explode(',', $_SESSION['user']['allowed_pages']))
  : [];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php print_r($_SESSION); ?>

  <div class="topnav">
    <?php foreach ($allMenus as $pageNum => $menu): ?>
      <?php if (in_array($pageNum, $allowedPages)): ?>
        <a href="<?= $menu['file'] ?>"><?= $menu['label'] ?></a>
      <?php endif; ?>
    <?php endforeach; ?>
    <a class="logout" href="logout.php">Logout</a>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const currentPath = window.location.pathname.split('/').pop();
      const links = document.querySelectorAll('.topnav a');

      links.forEach(link => {
        const linkPath = link.getAttribute('href');
        if (linkPath === currentPath) {
          links.forEach(l => l.classList.remove('active'));
          link.classList.add('active');
        }
      });
    });
  </script>

</body>
</html>
