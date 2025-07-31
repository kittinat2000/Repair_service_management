<?php
include 'navbar.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
  $id = $_GET['id'];

  $stmt = $pdo->prepare("SELECT * FROM repairs WHERE id = ?");
  $stmt->execute([$id]);
  $repair = $stmt->fetch();
  $image1 = $repair['image1'];
  $image2 = $repair['image2'];
  $image3 = $repair['image3'];

  if (!$repair) {
    echo "ไม่พบข้อมูล";
    exit;
  }
}
// เมื่อกดปุ่มในฟอร์ม 
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
  $id = $_POST['id'];
  $status = $_POST['status'];
  $note = $_POST['note'];

  $stmt = $pdo->prepare("UPDATE repairs SET status=?, note=? WHERE id=?");
  $stmt->execute([$status, $note, $id]);

  $stmt2 = $pdo->prepare("INSERT INTO repair_history (repair_id, action, updated_by) VALUES (?, 'อัพเดท'$note, ?)");
  $stmt2->execute([$id, $_SESSION['user']['id']]);

  redirect('details_a.php');
  exit;
} else {
  echo "การเข้าถึงไม่ถูกต้อง";
  exit;
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <title>อัพเดทข้อมูล</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="container" style="margin-top: 30px;">
    <div class="card">
      <a class="header">Update Service</a>
      <!-- case_number -->
      <form action="" method="post">
        <!-- ใช้สำหรับส่งค่า id -->
        <input type="hidden" name="id" value="<?= htmlspecialchars($repair['id']) ?>">
        <div class="inputBox">
          <input name="case_number" type="text" placeholder=" " value="<?php echo htmlspecialchars($repair['case_number']); ?>" readonly>
          <span class="user">Case No.</span>
        </div>
        <!-- branch -->
        <div class="inputBox">
          <input name="branch" type="text" placeholder=" " value="<?php echo htmlspecialchars($repair['branch']); ?>" readonly>
          <span class="user">สาขา</span>
        </div>
        <!-- title -->
        <div class="inputBox">
          <input name="title" type="text" placeholder=" " value="<?php echo htmlspecialchars($repair['title']); ?>" readonly>
          <span class="user">เรื่อง</span>
        </div>
        <!-- description -->
        <div class="inputBox">
          <input name="description" type="text" placeholder=" " value="<?php echo htmlspecialchars($repair['description']); ?>" readonly>
          <span class="user">อาการที่พบ</span>
        </div>
        <!-- reporter -->
        <div class="inputBox">
          <input name="reporter" type="text" placeholder=" " value="<?php echo htmlspecialchars($repair['reporter']); ?>" readonly>
          <span class="user">ผู้แจ้ง</span>
        </div>
        <!-- contact -->
        <div class="inputBox">
          <input name="contact" type="text" placeholder=" " value="<?php echo htmlspecialchars($repair['contact']); ?>" readonly>
          <span class="user">เบอร์ติดต่อ</span>
        </div>
        <!-- img -->
        <div class="img">
          <?php if ($image1): ?>
            <img src="uploads/<?= htmlspecialchars($image1) ?>" alt="รูปที่ 1" width="200" onclick="showImage(this.src)">
          <?php endif; ?>
          <?php if ($image2): ?>
            <img src="uploads/<?= htmlspecialchars($image2) ?>" alt="รูปที่ 2" width="200" onclick="showImage(this.src)">
          <?php endif; ?>
          <?php if ($image3): ?>
            <img src="uploads/<?= htmlspecialchars($image3) ?>" alt="รูปที่ 3" width="200" onclick="showImage(this.src)">
          <?php endif; ?>
        </div>
        <!-- Lightbox Container -->
        <div id="lightbox" onclick="closeLightbox()">
          <span id="lightbox-close">&times;</span>
          <img id="lightbox-img" src="" alt="รูปขยาย">
        </div>
        <!-- note -->
        <div class="inputBox">
          <textarea name="note" type="text" placeholder=" " required></textarea>
          <span>รายละเอียดการแก้ไข</span>
        </div>
        <!-- status -->
        <div class="inputBox">
          <select name="status" required>
            <option value="" disabled selected>-- สถานะ --</option>
            <option value="กำลังดำเนินการ">กำลังดำเนินการ</option>
            <option value="เสร็จสิ้น">เสร็จสิ้น</option>
          </select>
        </div>

        <!-- button -->
        <center><button class="enter" name="update"">Submit</button></center>
      </form>
    </div>
  </div>

<script src=" assets/script.js""></script>

</body>

</html>