
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <!-- import icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="topnav">
        <a href="dashboard.php">Dashboard</a>
        <a href="details_a.php">แจ้งซ่อม - IT</a>
        <a href="details_b.php">แจ้งซ่อม - MT</a>
        <a href="details_c.php">เบิกของ</a>
        <a href="history.php">ประวัติ</a>
        <a href="report.php">รายงาน</a>
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <a href="users.php">จัดการบัญชีผู้ใช้</a>
        <?php endif; ?>
        <a class="logout" href="logout.php">Logout</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // เอา path ปัจจุบัน 
            const currentPath = window.location.pathname.split('/').pop();

            // ดึง link ทั้งหมดใน nav
            const links = document.querySelectorAll('.topnav a');

            links.forEach(link => {
                // เอา href ของแต่ละ link
                const linkPath = link.getAttribute('href');

                // ถ้า path ตรงกัน
                if (linkPath === currentPath) {
                    // ลบ active เก่า (กันพลาดซ้ำ)
                    links.forEach(l => l.classList.remove('active'));

                    // ใส่ active กับปุ่มนี้
                    link.classList.add('active');
                }
            });
        });
    </script>

</body>

</html>