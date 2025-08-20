CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action_type ENUM('ลงทะเบียน', 'เปลี่ยนนายจ้าง', 'moni') NOT NULL,
    emp_code VARCHAR(50) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    photo_path VARCHAR(255),
    work_permit_path VARCHAR(255),
    passport_path VARCHAR(255),
    receipt_path VARCHAR(255),
    wp_book_path VARCHAR(255),
    work_accept_path VARCHAR(255),
    notice_file_path VARCHAR(255),
    pink_card_front_path VARCHAR(255),
    pink_card_back_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;