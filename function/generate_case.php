<?php
function generateCaseNumber($conn) {
    $today = date("Ymd"); // เช่น 20250820
    $prefix = $today . "-";

    // นับจำนวนเคสของวันนั้น
    $sql = "SELECT COUNT(*) as count FROM repair_case WHERE DATE(created_at) = CURDATE()";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $count = $row['count'] + 1;

    return $prefix . str_pad($count, 3, "0", STR_PAD_LEFT); // เช่น 20250820-001
}
?>
