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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<style>
  /* ฟอนต์ */
  @font-face {
    font-family: 'FC Minimal';
    src: url('assets/fonts/FCMinimal-Regular.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
  }

  body {
    font-family: 'FC Minimal', sans-serif;
    font-size: 22px;
    line-height: 1.6;
    background: #f8f9fa;
  }

  input,
  select,
  textarea,
  button {
    font-size: 22px !important;
  }

  h4 {
    font-size: 26px;
    font-weight: bold;
  }

  /* ✅ ปรับ Select2 ให้สวยขึ้น */
  .select2-container--default .select2-selection--single {
    height: 55px !important;
    padding: 10px 16px;
    font-size: 22px;
    border: 1px solid #ced4da;
    border-radius: 12px;
    display: flex;
    align-items: center;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #212529;
    font-size: 22px;
    line-height: 1.6;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100%;
    right: 10px;
  }

  /* dropdown สาขา */
  .select2-container--default .select2-results__option {
    padding: 12px;
    font-size: 22px;
  }

  .select2-dropdown {
    border-radius: 12px;
    border: 1px solid #ced4da;
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
            <select name="branch_id" id="branchSelect" class="form-select" required>
              <option value="">-- เลือกสาขา --</option>
              <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $id = htmlspecialchars($row["id"]);
                  $label = htmlspecialchars($row["branch_type"]) . " สาขาที่ " . htmlspecialchars($row["branch_order"]) . " " . htmlspecialchars($row["branch_name"]);
                  echo "<option value=\"$id\">$label</option>";
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
            <textarea name="description" class="form-control" rows="4"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">ผู้แจ้ง</label>
            <input type="text" name="reporter" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">เบอร์ติดต่อ</label>
            <input type="text" name="contact" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">อัปโหลดรูปภาพ</label>
            <input type="file" name="image1" class="form-control mb-2"> <input type="file" name="image2" class="form-control mb-2"> <input type="file" name="image3" class="form-control">
          </div> <button type="submit" class="btn btn-success w-100">✅ บันทึกการแจ้งซ่อม</button>
        </form>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      $('#branchSelect').select2({
        placeholder: "EX: suki , สาขาที่ 1 , อ่อนนุช",
        allowClear: true,
        width: '100%'
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
</body>

</html>