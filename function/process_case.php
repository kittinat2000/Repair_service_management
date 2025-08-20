<?php
require '../config.php';

$id = $_POST['id'] ?? 0;
$action = $_POST['action'] ?? '';

if(!$id || !$action) {
    die("Invalid request");
}

if($action == 'change_target') {
    $new_target = $_POST['new_target'] ?? '';
    if($new_target) {
        $stmt = $conn->prepare("UPDATE repair_case SET target = ? WHERE id = ?");
        $stmt->bind_param("si", $new_target, $id);
        $stmt->execute();
        $stmt->close();
    }
} elseif($action == 'delete') {
    $stmt = $conn->prepare("DELETE FROM repair_case WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: ../manage_cases.php");
exit;
?>
