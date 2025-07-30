<?php
$plain = "admin";
$hash = '$2y$10$RmVMFis7ZN03s2J3Rcmu/egwKqDFWIY95PQbQU6a69Yp.JPvVf6FK';

if (password_verify($plain, $hash)) {
    echo "OK! Password ตรงกัน";
} else {
    echo "รหัสผ่านผิด!";
}


echo password_hash("admin", PASSWORD_DEFAULT);


?>
