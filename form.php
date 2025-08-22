<?php
require "config.php";
include "function/generate_case.php";

// ฟังก์ชั้นดึงเลขเคส
$case_number = generateCaseNumber($conn);

// ดึงข้อมูลสาขา
$sql = "SELECT * FROM branches ORDER BY branch_order ASC";
$result = $conn->query($sql);

$conn->close();

?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>📋 ฟอร์มแจ้งซ่อม</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    @font-face {
    font-family: 'FC Minimal';
    src: url('assets/fonts/FCMinimal-Regular.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
}
    /* ฟอนต์พื้นฐานทั้งเว็บ */
    body {
      font-family: 'FC Minimal', sans-serif;
      font-size: 40px;
      line-height: 3;
    }

    /* ป้อง กัน Zoom input */
    input,
    select,
    textarea,
    button {
      font-size: 40px !important;
    }

    /* เฉพาะหัวข้อใหญ่ */
    h4 {
      font-size: 40px;
    }
  </style>
</head>

<body class="bg-light">
  <div class="container-fluid py-4">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white text-center">
        <h4 class="mb-0">📋 ฟอร์มแจ้งซ่อม</h4>
      </div>
      <div class="card-body">
        <form action="function/form_save.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">เลขที่เคส</label>
            <input type="text" name="case_number" class="form-control" value="<?= $case_number ?>" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">ประเภทงาน</label>
            <select name="target" class="form-select" required>
              <option value="">-- เลือกประเภทงาน --</option>
              <option value="IT">เทคโนโลยี</option>
              <option value="MT">ช่าง</option>
              <option value="Withdraw">เบิกของ</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">สาขา</label>
            <select name="branch_id" class="form-select" required>
              <option value="">-- เลือกสาขา --</option>
              <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<option value='" . htmlspecialchars($row["id"]) . "'>" . htmlspecialchars($row["branch_type"]) . " สาขาที่ " . htmlspecialchars($row["branch_order"]) . " " . htmlspecialchars($row["branch_name"]) . "</option>";
                }
              } ?>
            </select>
          </div>
          <div class="mb-3"> 
            <label class="form-label">หัวข้อแจ้งซ่อม</label> 
            <input type="text" name="title" class="form-control" required> 
          </div>
          <div class="mb-3"> 
            <label class="form-label">รายละเอียด</label> 
            <textarea name="description" class="form-control" rows="4"></textarea> </div>
          <div class="mb-3"> 
            <label class="form-label">ผู้แจ้ง</label> 
            <input type="text" name="reporter" class="form-control"> </div>
          <div class="mb-3"> 
            <label class="form-label">เบอร์ติดต่อ</label> 
            <input type="text" name="contact" class="form-control"> </div>
          <div class="mb-3"> 
            <label class="form-label">อัปโหลดรูปภาพ</label> 
            <input type="file" name="image1" class="form-control mb-2"> <input type="file" name="image2" class="form-control mb-2"> <input type="file" name="image3" class="form-control"> </div> <button type="submit" class="btn btn-success w-100">✅ บันทึกการแจ้งซ่อม</button>
        </form>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>