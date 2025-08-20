<?php
function generateCaseNumber($pdo) {
    $date = date('Ymd');
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM repairs WHERE DATE(created_at) = CURDATE()");
    $stmt->execute();
    $count = $stmt->fetchColumn() + 1;
    return $date . '-' . $count;
}

function isLoggedIn(): bool {
  return !empty($_SESSION['user']); // แค่มี user ใน session ก็ถือว่าอยู่
}

function checkRole($role) {
    return isLoggedIn() && $_SESSION['user']['role'] === $role;
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function checkAccess($page) {
    if (!isLoggedIn()) redirect('login.php');

    if ($_SESSION['user']['role'] === 'admin') return; // admin เห็นได้ทุกหน้า

    $allowed = $_SESSION['user']['allowed_pages'];
    $allowed_pages = explode(',', $allowed);

    if (!in_array($page, $allowed_pages)) {
        echo "คุณไม่มีสิทธิ์เข้าถึงหน้านี้";
        exit;
    }
}

?>