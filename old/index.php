<?php
require 'config.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target = $_POST['target'];
    $branch_prefix = $_POST['branch_prefix'];
    $branch = $_POST['branch'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $reporter = $_POST['reporter'];
    $contact = $_POST['contact'];
    $caseNumber = generateCaseNumber($pdo);

    // อัพโหลดไฟล์ (จำกัด 3 รูป)
    $uploaded = [];
    for ($i = 1; $i <= 3; $i++) {
        if (isset($_FILES["image$i"]) && $_FILES["image$i"]['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES["image$i"]['name'], PATHINFO_EXTENSION);
            $filename = "{$caseNumber}-{$i}." . $ext;
            move_uploaded_file($_FILES["image$i"]['tmp_name'], "uploads/$filename");
            $uploaded[$i] = $filename;
        } else {
            $uploaded[$i] = null;
        }
    }

    // สถานะเริ่มต้น: แจ้งซ่อม
    $status = 'แจ้งซ่อม';

    // บันทึกลง DB
    $stmt = $pdo->prepare("
        INSERT INTO repairs (
            case_number, target, branch_prefix, branch, title, description,
            reporter, contact, image1, image2, image3, status
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $caseNumber,
        $target,
        $branch_prefix,
        $branch,
        $title,
        $description,
        $reporter,
        $contact,
        $uploaded[1],
        $uploaded[2],
        $uploaded[3],
        $status
    ]);

    header("Location: index.php?success=1");
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>แจ้งซ่อม</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    
    <?php if (isset($_GET['success'])): ?>
        <script>
            alert("แจ้งซ่อมสำเร็จ!");
        </script>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="container" style="margin-top: 30px;">
            <div class="card">
                <a class="header">แจ้งซ่อม</a>
                <div class="inputBox">
                    <select name="target" required>
                        <option value="" disabled selected>-- ปัญหาด้านใด --</option>
                        <option value="A">เทคโนโลยี</option>
                        <option value="B">ช่าง</option>
                        <option value="C">เบิกของ</option>
                    </select>
                    <select name="branch_prefix" required>
                        <option value="" disabled selected>-- สาขา --</option>
                        <option value="Suki">Suki</option>
                        <option value="BBQ">BBQ</option>
                    </select>
                </div>
                <div class="inputBox">
                    <input type="text" placeholder=" " name="branch" required>
                    <span class="user">ชื่อสาขา</span>
                </div>
                <div class="inputBox">
                    <input type="text" placeholder=" " name="title" required>
                    <span class="user">เรื่อง</span>
                </div>
                <div class="inputBox">
                    <textarea type="text" placeholder=" " name="description" required></textarea>
                    <span class="user">อาการที่พบ</span>
                </div>
                <div class="inputBox">
                    <input type="text" placeholder=" " name="reporter" required>
                    <span class="user">ผู้แจ้ง</span>
                </div>
                <div class="inputBox">
                    <input type="text" placeholder=" " name="contact" required>
                    <span class="user">เบอร์ติดต่อ</span>
                </div>

                <label for="">ภาพประกอบ (จำกัด 3 รูป)</label>
                <input type="file" name="image1" accept="image/*" id="file1">
                <img id="preview1" src="#" alt="Preview" style="max-width: 300px; display: none; margin-top: 10px;">
                <input type="file" name="image2" accept="image/*" id="file2">
                <img id="preview2" src="#" alt="Preview" style="max-width: 300px; display: none; margin-top: 10px;">
                <input type="file" name="image3" accept="image/*" id="file3">
                <img id="preview3" src="#" alt="Preview" style="max-width: 300px; display: none; margin-top: 10px;">

                <button class="enter">Submit</button>
            </div>
        </div>
    </form>

    <script>
        function previewImage(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            input.addEventListener("change", function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = "block"; // แสดงรูป
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = "none"; // ถ้ายกเลิก ให้ซ่อนรูป
                }
            });
        }

        previewImage("file1", "preview1");
        previewImage("file2", "preview2");
        previewImage("file3", "preview3");
    </script>

</body>

</html>