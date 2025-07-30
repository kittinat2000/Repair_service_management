-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2025 at 06:08 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `repair_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `repairs`
--

CREATE TABLE `repairs` (
  `id` int(11) NOT NULL,
  `case_number` varchar(20) NOT NULL,
  `target` enum('A','B','C') NOT NULL,
  `branch_prefix` enum('bbq','suki') NOT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `reporter` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `status` enum('แจ้งซ่อม','กำลังดำเนินการ','เสร็จสิ้น') DEFAULT 'แจ้งซ่อม',
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `repairs`
--

INSERT INTO `repairs` (`id`, `case_number`, `target`, `branch_prefix`, `branch`, `title`, `description`, `reporter`, `contact`, `image1`, `image2`, `image3`, `note`, `status`, `created_at`, `deleted`) VALUES
(1, '20250607-1', 'A', 'bbq', 'จรัญ', 'ups ร้อง', 'ups ดังค้างยาว', 'นนท์', '1215456', '68441fc8ba4a5_BBQ.jpg', '68441fc8bab4c_miracleplanet-removebg-preview.png', '68441fc8baf54_icon_windows.png', NULL, 'แจ้งซ่อม', '2025-06-07 18:17:28', 0),
(2, '20250608-1', 'B', 'suki', 'อ้อมน้อย', 'เครื่องปริ้นใช้งานไม่ได้', 'ปริ้นไม่ออก', 'สมหมาย', '54898787979', '20250608-1-1.jpg', NULL, NULL, NULL, 'แจ้งซ่อม', '2025-06-08 18:23:40', 0),
(33, '20250609-1', 'A', 'suki', 'สาขาสยาม', 'เครื่องชำรุด', 'เครื่อง BBQ ไม่ทำงาน', 'สมชาย', '0812345678', NULL, NULL, NULL, 'แจ้งครั้งแรก', 'แจ้งซ่อม', '2025-06-09 12:00:37', 0),
(34, '20250609-2', 'A', 'suki', 'สาขาอโศก', 'น้ำรั่ว', 'น้ำรั่วที่ซิงค์', 'สมหญิง', '0898765432', NULL, NULL, NULL, '', 'กำลังดำเนินการ', '2025-06-09 12:00:37', 0),
(35, '20250609-3', 'A', 'bbq', 'สาขาเซ็นทรัล', 'แอร์เสีย', 'แอร์เย็นไม่ออก', 'มนตรี', '0801234567', NULL, NULL, NULL, 'รอตรวจสอบ', 'แจ้งซ่อม', '2025-06-09 12:00:37', 1),
(36, '20250609-4', 'A', 'suki', 'สาขาแฟชั่น', 'ไฟฟ้าขัดข้อง', 'ไฟฟ้าดับบางจุด', 'ปาน', '0876543210', NULL, NULL, NULL, 'แจ้งด่วน', 'กำลังดำเนินการ', '2025-06-09 12:00:37', 0),
(37, '20250609-5', 'A', 'suki', 'สาขาเอ็มโพเรียม', 'เตาไม่ติด', 'เตา BBQ จุดไม่ติด', 'จิตร', '0861234567', NULL, NULL, NULL, '', 'เสร็จสิ้น', '2025-06-09 12:00:37', 0),
(38, '20250609-6', 'B', 'suki', 'สาขาเซ็นทรัลลาดพร้าว', 'เครื่องซักผ้าชำรุด', 'เครื่องซักผ้าไม่หมุน', 'กมล', '0845678910', NULL, NULL, NULL, 'ซ่อมเสร็จแล้ว', 'เสร็จสิ้น', '2025-06-09 12:00:37', 0),
(40, '20250609-8', 'B', 'suki', 'สาขาสีลม', 'ประตูชำรุด', 'ประตูไม่ปิด', 'สุนิตย์', '0834567890', NULL, NULL, NULL, '', 'กำลังดำเนินการ', '2025-06-09 12:00:37', 0),
(41, '20250609-9', 'B', 'bbq', 'สาขาสาทร', 'กล้องวงจรปิดเสีย', 'กล้องวงจรปิดไม่ทำงาน', 'เกษม', '0856789012', NULL, NULL, NULL, '', 'แจ้งซ่อม', '2025-06-09 12:00:37', 0),
(42, '20250609-10', 'A', 'suki', 'สาขาอโศก', 'เครื่องกรองน้ำเสีย', 'เครื่องกรองน้ำรั่ว', 'อร', '0810987654', NULL, NULL, NULL, 'เปลี่ยนอะไหล่แล้ว', 'เสร็จสิ้น', '2025-06-09 12:00:37', 0),
(44, '20250609-12', 'A', 'suki', 'สาขาเอ็มบีเค', 'น้ำไม่ร้อน', 'เครื่องทำน้ำร้อนไม่ทำงาน', 'สมหญิง', '0822222222', NULL, NULL, NULL, '', 'กำลังดำเนินการ', '2025-06-09 12:00:37', 0),
(46, '20250609-14', 'B', 'suki', 'สาขาเซ็นทรัลปิ่นเกล้า', 'แอร์เสีย', 'แอร์ไม่เย็น', 'ปาน', '0844444444', NULL, NULL, NULL, '', 'แจ้งซ่อม', '2025-06-09 12:00:37', 0),
(47, '20250609-15', 'B', 'bbq', 'สาขาเซ็นทรัลลาดพร้าว', 'เครื่องปิ้งเสีย', 'เครื่องปิ้งทำงานผิดปกติ', 'จิตร', '0855555555', NULL, NULL, NULL, 'ตรวจสอบ', 'กำลังดำเนินการ', '2025-06-09 12:00:37', 0),
(48, '20250609-16', 'C', 'suki', 'สาขารัชโยธิน', 'กล้องวงจรปิดชำรุด', 'กล้องวงจรปิดไม่บันทึกภาพ', 'กมล', '0866666666', NULL, NULL, NULL, 'ddddd', 'เสร็จสิ้น', '2025-06-09 12:00:37', 0),
(50, '20250609-18', 'A', 'suki', 'สาขาอโศก', 'ประตูอัตโนมัติไม่เปิด', 'ประตูไฟฟ้าไม่ตอบสนอง', 'สุนิตย์', '0888888888', NULL, NULL, NULL, '', 'แจ้งซ่อม', '2025-06-09 12:00:37', 0),
(51, '20250609-19', 'B', 'bbq', 'สาขาสีลม', 'น้ำประปาไหลช้า', 'น้ำประปาไหลไม่แรง', 'เกษม', '0899999999', NULL, NULL, NULL, '', 'เสร็จสิ้น', '2025-06-09 12:00:37', 0),
(52, '20250609-20', 'B', 'suki', 'สาขาราชเทวี', 'เครื่องกรองน้ำเสีย', 'เครื่องกรองน้ำรั่ว', 'อร', '0800000000', NULL, NULL, NULL, 'ซ่อมเสร็จแล้ว', 'เสร็จสิ้น', '2025-06-09 12:00:37', 0),
(54, '20250609-22', 'B', 'suki', 'สาขาเซ็นทรัลแจ้งวัฒนะ', 'แอร์ไม่เย็น', 'แอร์ไม่ทำงานตามปกติ', 'สมหญิง', '0822345678', NULL, NULL, NULL, '', 'กำลังดำเนินการ', '2025-06-09 12:00:37', 0),
(56, '20250609-24', 'B', 'suki', 'สาขาเซ็นทรัลรัตนาธิเบศร์', 'น้ำรั่ว', 'น้ำรั่วที่ท่อหลัก', 'ปาน', '0844567890', NULL, NULL, NULL, '', 'กำลังดำเนินการ', '2025-06-09 12:00:37', 0),
(57, '20250609-25', 'B', 'bbq', 'สาขาเซ็นทรัลพระราม 3', 'เครื่องปิ้งเสีย', 'เครื่องปิ้งไม่ทำงาน', 'จิตร', '0855678901', NULL, NULL, NULL, '', 'แจ้งซ่อม', '2025-06-09 12:00:37', 0),
(58, '20250609-26', 'C', 'suki', 'สาขาเซ็นทรัลพระราม 2', 'กล้องวงจรปิดเสีย', 'กล้องไม่บันทึกภาพ', 'กมล', '0866789012', NULL, NULL, NULL, '', 'เสร็จสิ้น', '2025-06-09 12:00:37', 0),
(59, '20250609-27', 'B', 'bbq', 'สาขาเซ็นทรัลอีสต์วิลล์', 'เครื่องซักผ้าเสีย', 'เครื่องซักผ้าไม่ทำงาน', 'วิชัย', '0877890123', NULL, NULL, NULL, '', 'กำลังดำเนินการ', '2025-06-09 12:00:37', 0),
(60, '20250609-28', 'C', 'suki', 'สาขาพาราไดซ์พาร์ค', 'ประตูอัตโนมัติชำรุด', 'ประตูเปิดไม่ออก', 'สุนิตย์', '0888901234', NULL, NULL, NULL, '', 'แจ้งซ่อม', '2025-06-09 12:00:37', 0),
(61, '20250609-29', 'B', 'bbq', 'สาขาเซ็นทรัลเวสต์เกต', 'น้ำไม่ไหล', 'น้ำประปาไม่ไหล', 'เกษม', '0899012345', NULL, NULL, NULL, '', 'เสร็จสิ้น', '2025-06-09 12:00:37', 0),
(62, '20250609-30', 'B', 'suki', 'สาขาเซ็นทรัลลาดกระบัง', 'เครื่องกรองน้ำเสีย', 'เครื่องกรองน้ำไม่ทำงาน', 'อร', '0801234567', NULL, NULL, NULL, '', 'กำลังดำเนินการ', '2025-06-09 12:00:37', 0);

-- --------------------------------------------------------

--
-- Table structure for table `repair_history`
--

CREATE TABLE `repair_history` (
  `id` int(11) NOT NULL,
  `repair_id` int(11) NOT NULL,
  `action` text DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `repair_history`
--

INSERT INTO `repair_history` (`id`, `repair_id`, `action`, `updated_by`, `updated_at`) VALUES
(6, 57, 'ลบข้อมูล', 'admin', '2025-06-09 14:28:47'),
(13, 35, 'ลบข้อมูล', 'admin', '2025-06-09 14:43:47'),
(14, 36, 'โยนข้อมูลไปให้ A', 'admin', '2025-06-09 14:45:00'),
(15, 34, 'โยนข้อมูลไปให้ A', 'admin', '2025-06-09 14:45:07'),
(16, 33, 'โยนข้อมูลไปให้ B', 'admin', '2025-06-09 14:45:15'),
(17, 41, 'โยนข้อมูลไปให้ B', 'admin', '2025-06-09 14:45:52'),
(18, 51, 'โยนข้อมูลไปให้ B', 'admin', '2025-06-09 14:49:32'),
(19, 37, 'โยนข้อมูลไปให้ B', 'admin', '2025-06-09 15:04:29'),
(20, 61, 'โยนข้อมูลไปให้ B', 'admin', '2025-06-09 15:04:40'),
(21, 47, 'โยนข้อมูลไปให้ B', 'admin', '2025-06-09 15:09:43'),
(22, 50, 'โยนข้อมูลไปให้ B', 'admin', '2025-06-10 16:45:39'),
(23, 57, 'โยนข้อมูลไปให้ B', 'admin', '2025-06-10 16:45:48'),
(24, 44, 'โยนข้อมูลไปให้ B', 'admin', '2025-06-10 16:46:36'),
(25, 59, 'โยนข้อมูลไปให้ B', 'admin', '2025-06-10 16:46:47'),
(26, 42, 'โยนข้อมูลไปให้ B', 'admin', '2025-06-10 16:46:54'),
(27, 36, 'โยนข้อมูลไปให้ B', 'admin', '2025-07-07 13:42:00'),
(28, 48, 'โยนข้อมูลไปให้ B', 'admin', '2025-07-07 13:42:16'),
(29, 46, 'โยนข้อมูลไปให้ B', 'admin', '2025-07-07 13:48:15'),
(30, 34, 'โยนข้อมูลไปให้ B', 'admin', '2025-07-07 13:48:49'),
(31, 36, 'โยนข้อมูลไปให้ B', 'admin', '2025-07-07 13:58:44'),
(32, 34, 'โยนข้อมูลไปให้ B', 'admin', '2025-07-07 14:32:36'),
(33, 34, 'โยนข้อมูลไปให้ B', 'admin', '2025-07-07 14:32:41'),
(34, 36, 'โยนข้อมูลไปให้ A', 'admin', '2025-07-07 17:03:50'),
(35, 46, 'โยนข้อมูลไปให้ B', 'admin', '2025-07-07 17:03:58'),
(36, 33, 'โยนข้อมูลไปให้ A', 'admin', '2025-07-07 17:05:38'),
(37, 37, 'โยนข้อมูลไปให้ A', 'admin', '2025-07-07 17:10:56'),
(38, 48, 'อัพเดท', '1', '2025-07-07 17:18:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `allowed_pages` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `allowed_pages`) VALUES
(1, 'admin', '$2y$10$RmVMFis7ZN03s2J3Rcmu/egwKqDFWIY95PQbQU6a69Yp.JPvVf6FK', 'admin', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `repairs`
--
ALTER TABLE `repairs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `case_number` (`case_number`);

--
-- Indexes for table `repair_history`
--
ALTER TABLE `repair_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `repair_id` (`repair_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `repairs`
--
ALTER TABLE `repairs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `repair_history`
--
ALTER TABLE `repair_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `repair_history`
--
ALTER TABLE `repair_history`
  ADD CONSTRAINT `repair_history_ibfk_1` FOREIGN KEY (`repair_id`) REFERENCES `repairs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
