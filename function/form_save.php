<?php
require '../config.php';
date_default_timezone_set("Asia/Bangkok"); // ตั้ง timezone

// รับค่าจากฟอร์ม
$visibility  = 'SHOW';
$case_number = $_POST['case_number'] ?? ''; // เช่น 20250821-001
$target      = $_POST['target'] ?? '';
$branch_id   = $_POST['branch_id'] ?? '';
$title       = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$reporter    = $_POST['reporter'] ?? '';
$contact     = $_POST['contact'] ?? '';

// --------------------
// อัปโหลดไฟล์รูป
// --------------------
$uploadDir = "../uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

function uploadFile($fileInput, $case_number, $index, $uploadDir) {
    if (!empty($_FILES[$fileInput]['name'])) {
        $ext = pathinfo($_FILES[$fileInput]['name'], PATHINFO_EXTENSION);
        $newName = $case_number . "-" . $index . "." . $ext; // เช่น 20250811-001-1.jpg
        $path = $uploadDir . $newName;
        if (move_uploaded_file($_FILES[$fileInput]['tmp_name'], $path)) {
            return $newName; // เก็บเฉพาะชื่อไฟล์
        }
    }
    return null;
}

$image1 = uploadFile("image1", $case_number, 1, $uploadDir);
$image2 = uploadFile("image2", $case_number, 2, $uploadDir);
$image3 = uploadFile("image3", $case_number, 3, $uploadDir);

// --------------------
// ตรวจสอบ case_number ซ้ำ + หาเลขถัดไป
// --------------------
list($datePart, $numPart) = explode("-", $case_number);
$numPart = (int)$numPart; // เช่น 001 -> 1

while (true) {
    $tryCase = $datePart . "-" . str_pad($numPart, 3, "0", STR_PAD_LEFT);
    $checkSql = "SELECT id FROM repair_case WHERE case_number = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $tryCase);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows == 0) {
        $case_number = $tryCase;
        $checkStmt->close();
        break;
    }
    $checkStmt->close();
    $numPart++;
}

// --------------------
// Insert ลง DB
// --------------------
$sql = "INSERT INTO repair_case 
(visibility_status, case_number, target, branch_id, title, description, reporter, contact, image1, image2, image3) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssssssss",
    $visibility,
    $case_number,
    $target,
    $branch_id,
    $title,
    $description,
    $reporter,
    $contact,
    $image1,
    $image2,
    $image3
);

if ($stmt->execute()):
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>แจ้งซ่อมเรียบร้อย</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f8f9fa;
    text-align: center;
  }
  .card {
    max-width: 500px;
    width: 100%;
    padding: 20px;
  }
  .uploaded-image {
    max-width: 100%;
    margin-bottom: 15px;
    border-radius: 8px;
  }
</style>
</head>
<body>
  <div class="card shadow">
    <img src="../assets/img/icon_success.png" alt="">

    <h4 class="mb-3">✅ แจ้งซ่อมเรียบร้อย!</h4>
    <p>เลขที่เคสของคุณคือ:</p>
    <h5 class="fw-bold"><?= htmlspecialchars($case_number) ?></h5>

    <a href="../form.php" class="btn btn-primary mt-3 w-100">แจ้งซ่อมใหม่</a>
  </div>
</body>
</html>
<?php
else:
    echo "❌ เกิดข้อผิดพลาด: " . $stmt->error;
endif;

$stmt->close();
$conn->close();
?>
