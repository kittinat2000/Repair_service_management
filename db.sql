CREATE TABLE repair_case (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visibility_status ENUM('SHOW','HIDE') DEFAULT 'SHOW',
    case_number VARCHAR(20) NOT NULL UNIQUE,
    target VARCHAR(50) NOT NULL,
    branch_id VARCHAR(100),
    title VARCHAR(255),
    description TEXT,
    reporter VARCHAR(100),
    contact VARCHAR(20),
    image1 VARCHAR(255),
    image2 VARCHAR(255),
    image3 VARCHAR(255),
    note TEXT DEFAULT NULL,
    status ENUM('แจ้งซ่อม', 'กำลังดำเนินการ', 'เสร็จสิ้น') DEFAULT 'แจ้งซ่อม',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE branches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    branch_type ENUM('Suki','BBQ') NOT NULL, 
    branch_order INT NOT NULL, 
    branch_name VARCHAR(255) NOT NULL UNIQUE,
    phonenumber VARCHAR(25) NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(2, 'admin', '$2y$10$s9DMj7z1pqwA2Tzfk9ILB.fOpCrULdAFLRqfo2CbWgIiOJCNRBhYC', 'admin', '2025-08-14 16:54:06'),
(3, 'user', '$2y$10$fxVx21vXmy2fWe35haGRe.wlQTHHOgSdw8EoH.JR6JAH/20PCCqMy', 'user', '2025-08-14 16:54:06');
