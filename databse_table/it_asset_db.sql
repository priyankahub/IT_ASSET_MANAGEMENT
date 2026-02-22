-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 21, 2026 at 09:07 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `it_asset_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_username` varchar(100) DEFAULT NULL,
  `action_tag` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `action_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_username`, `action_tag`, `description`, `action_date`) VALUES
(1, 'test_clerk', 'Test Action', 'Testing log entry', '2026-02-21 21:33:33'),
(2, 'neha.verma', 'New User Creation', 'Requested CREATE for user: karan bisht (Army No: ik9876543)', '2026-02-21 21:37:13'),
(3, 'neha.verma', 'Equipment Status Request', 'Registered Gun | Serial: 87654 | Requested Status: Under-Maintenance', '2026-02-21 21:44:08'),
(4, 'sneha.kapoor', 'New User Creation', 'Requested CREATE for user: ererr (Army No: errrr)', '2026-02-21 22:44:50'),
(5, 'sneha.kapoor', 'New User Creation', 'Requested CREATE for user: werr (Army No: weee)', '2026-02-21 22:45:29'),
(6, 'sneha.kapoor', 'New User Creation', 'Requested CREATE for user: yuuu (Army No: uiiii)', '2026-02-21 22:47:39'),
(7, 'sneha.kapoor', 'New User Creation', 'Requested CREATE for user: Jyoti Sharma (Army No: JC098765)', '2026-02-22 00:37:07'),
(8, 'sneha.kapoor', 'Edit User', 'Requested EDIT for user: prakash.singh → prakash.singh | Role: ITJCO', '2026-02-22 01:05:34'),
(9, 'sneha.kapoor', 'Delete User', 'Requested DELETE for user: Himnegi | Role: USER', '2026-02-22 01:36:54');

-- --------------------------------------------------------

--
-- Table structure for table `allocation`
--

CREATE TABLE `allocation` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `allotted_to` varchar(100) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('Issued','Returned') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allocation`
--

INSERT INTO `allocation` (`id`, `equipment_id`, `allotted_to`, `issue_date`, `return_date`, `status`) VALUES
(1, 3, 'Negi Tunna', '2026-02-01', '2026-02-01', 'Returned'),
(2, 3, 'user1', '2026-02-01', '2026-02-01', 'Returned'),
(3, 5, 'user1', '2026-02-01', '2026-02-08', 'Returned'),
(4, 3, 'user', '2026-02-01', '2026-02-08', 'Returned'),
(5, 3, 'user1', '2026-02-08', NULL, 'Issued'),
(6, 3, 'karan001', '2026-02-09', NULL, 'Issued');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `action_type` varchar(100) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `performed_by` varchar(100) DEFAULT NULL,
  `performed_role` varchar(50) DEFAULT NULL,
  `action_date` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `condemnation_requests`
--

CREATE TABLE `condemnation_requests` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `requested_by` varchar(100) DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `approved_by` varchar(100) DEFAULT NULL,
  `approval_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `condemnation_requests`
--

INSERT INTO `condemnation_requests` (`id`, `equipment_id`, `requested_by`, `request_date`, `status`, `approved_by`, `approval_date`, `remarks`) VALUES
(1, 3, 'neha.verma', '2026-02-21', 'Pending', NULL, NULL, NULL),
(6, 5, 'sneha.kapoor', '2026-02-21', 'Pending', NULL, NULL, NULL),
(7, 8, 'sneha.kapoor', '2026-02-21', 'Pending', NULL, NULL, NULL),
(8, 9, 'neha.verma', '2026-02-21', 'Pending', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `disposal`
--

CREATE TABLE `disposal` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `disposal_date` date DEFAULT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disposal`
--

INSERT INTO `disposal` (`id`, `equipment_id`, `disposal_date`, `reason`) VALUES
(1, 1, '2026-01-31', 'Condemned due to expiry / condition'),
(2, 2, '2026-01-31', 'Condemned due to expiry / condition'),
(3, 4, '2026-01-31', 'Condemned due to expiry / condition'),
(4, 6, '2026-02-01', 'Condemned due to expiry / condition'),
(5, 7, '2026-02-01', 'Condemned due to expiry / condition');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `make` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `serial_no` varchar(50) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `warranty_end` date DEFAULT NULL,
  `cost` decimal(10,5) DEFAULT NULL,
  `status` enum('Serviceable','Non-Serviceable','Under-Maintenance','Condemned','Pending Approval') NOT NULL DEFAULT 'Serviceable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `type`, `make`, `model`, `serial_no`, `purchase_date`, `warranty_end`, `cost`, `status`) VALUES
(1, 'Artilery', 'Indian', 'IND001', 'XYZ001', '2026-01-01', '2026-12-31', 10001.00000, 'Condemned'),
(2, 'Eqip1', 'France', 'F002', 'F23123', '2023-06-30', '2026-01-29', 99999.99999, 'Condemned'),
(3, 'Gun', 'China', 'CH991', 'CH76552', '2026-01-01', '2026-01-31', 99999.99999, 'Serviceable'),
(4, 'Tank', '342345', 'asdf', '2134', '2026-01-01', '2026-01-30', 98797.00000, 'Condemned'),
(5, 'Tank', '342345', 'asdf', '21345', '2026-01-01', '2026-01-30', 98797.00000, 'Serviceable'),
(6, 'Tank', 'America', 'AM00987', '3456789tyu', '2026-01-01', '2026-02-28', 2365.00000, 'Condemned'),
(7, 'Bomb', 'Russia', 'RS92892', '678tyu', '2025-12-31', '2026-02-28', 10000.00000, 'Condemned'),
(8, 'Tank', 'France', 'IND002', '1234t5y5', '2026-02-10', '2026-06-25', 99999.99999, 'Serviceable'),
(9, 'Bomb', '2345', '234', '2334', '2026-02-05', '2026-03-06', 99999.99999, 'Serviceable'),
(10, 'Tank', 'Indian', 'IND003', '12345667890', '2026-02-04', '2026-06-09', 99999.99999, 'Serviceable'),
(11, 'Gun', 'tyui', 'werty', 'qwer6', '2023-02-07', '2026-02-21', 99999.99999, 'Serviceable'),
(12, 'Gun', 'China', 'fgh', '8765432', '2022-02-08', '2026-02-21', 43556.00000, 'Pending Approval'),
(13, 'Gun', 'uytre', 'wer', '87654', '2026-02-12', '2026-02-27', 99999.99999, 'Pending Approval');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_status_requests`
--

CREATE TABLE `equipment_status_requests` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `requested_status` enum('Non-Serviceable','Under-Maintenance','Condemned') NOT NULL,
  `requested_by` varchar(100) DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `approved_by` varchar(100) DEFAULT NULL,
  `approval_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment_status_requests`
--

INSERT INTO `equipment_status_requests` (`id`, `equipment_id`, `requested_status`, `requested_by`, `request_date`, `status`, `approved_by`, `approval_date`) VALUES
(1, 12, 'Non-Serviceable', 'neha.verma', '2026-02-21', 'Pending', NULL, NULL),
(2, 13, 'Under-Maintenance', 'neha.verma', '2026-02-21', 'Pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `equipment_types`
--

CREATE TABLE `equipment_types` (
  `id` int(11) NOT NULL,
  `type_name` varchar(100) NOT NULL,
  `is_active` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment_types`
--

INSERT INTO `equipment_types` (`id`, `type_name`, `is_active`) VALUES
(1, 'Gun', 1),
(2, 'Tank', 1),
(3, 'Bomb', 1),
(4, 'Artillery', 1);

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `maintenance_type` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance`
--

INSERT INTO `maintenance` (`id`, `equipment_id`, `maintenance_type`, `start_date`, `end_date`, `remarks`, `status`) VALUES
(1, 1, 'Preventive', '2026-02-01', NULL, 'Routine check', 'Completed'),
(2, 1, 'Breakdown', '2026-02-01', NULL, 'Repaired', NULL),
(3, 3, 'Preventive', '2026-02-01', NULL, 'faulty, sent to workshop for repair', 'In Progress');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_schedule`
--

CREATE TABLE `maintenance_schedule` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `schedule_type` varchar(50) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `status` varchar(30) DEFAULT 'Scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `role` enum('ADMIN','CO','ITJCO','CLERK','USER') NOT NULL,
  `army_no` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `army_no`, `created_at`) VALUES
(1, 'Amit Sharma', 'amit.sharma', 'Admin@123', 'ADMIN', 'IC-1001', '2026-02-20 05:30:32'),
(2, 'Col Rajiv Malhotra', 'rajiv.malhotra', 'CO@123A', 'CO', 'IC-1002', '2026-02-20 05:30:32'),
(3, 'Col Ankit Chauhan', 'ankit.chauhan', 'CO@456A', 'CO', 'IC-1003', '2026-02-20 05:30:32'),
(4, 'Subedar Prakash Singh', 'prakash.singh', 'ITJCO@1A', 'ITJCO', 'JC-2001', '2026-02-20 05:30:32'),
(5, 'Subedar Manoj Kumar', 'manoj.kumar', 'ITJCO@2A', 'ITJCO', 'JC-2002', '2026-02-20 05:30:32'),
(6, 'Neha Verma', 'neha.verma', 'Clerk@1A', 'CLERK', 'CL-3001', '2026-02-20 05:30:32'),
(7, 'Sneha Kapoor', 'sneha.kapoor', 'Clerk@2A', 'CLERK', 'CL-3002', '2026-02-20 05:30:32'),
(8, 'Rahul Mehta', 'rahul.mehta', 'User@1A', 'USER', 'OR-4001', '2026-02-20 05:30:32'),
(9, 'Priya Nair', 'priya.nair', 'User@2A', 'USER', 'OR-4002', '2026-02-20 05:30:32'),
(10, 'Arjun Singh', 'arjun.singh', 'User@3A', 'USER', 'OR-4003', '2026-02-20 05:30:32'),
(14, 'pryy', 'pryy', 'Password@#123', 'CO', 'pryy', '2026-02-20 10:48:11'),
(15, 'Himanshu Negi', 'Himnegi', 'Password@3123456', 'USER', 'JK098765L', '2026-02-21 12:29:03');

-- --------------------------------------------------------

--
-- Table structure for table `user_requests`
--

CREATE TABLE `user_requests` (
  `id` int(11) NOT NULL,
  `request_type` enum('CREATE','EDIT','DELETE') DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `army_no` varchar(50) DEFAULT NULL,
  `role` enum('ADMIN','CO','ITJCO','CLERK','USER') DEFAULT NULL,
  `requested_by` varchar(100) DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `approved_by` varchar(100) DEFAULT NULL,
  `approval_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_requests`
--

INSERT INTO `user_requests` (`id`, `request_type`, `full_name`, `username`, `password`, `army_no`, `role`, `requested_by`, `request_date`, `status`, `approved_by`, `approval_date`, `remarks`) VALUES
(1, 'CREATE', 'Karan Negi', 'negi001', 'negi001', 'jk6789', 'USER', 'clerk', '2026-02-18', 'Approved', 'admin', '2026-02-18', NULL),
(2, 'CREATE', 'karan bisht', 'bisht001', 'bisht001', 'ki98765', 'ADMIN', 'clerk', '2026-02-18', 'Rejected', 'admin', '2026-02-18', 'Retired'),
(4, 'CREATE', 'Priyanka Rawat', 'priyanka.rawat', 'Priyanka#27', 'COL098765', 'CLERK', 'SELF', '2026-02-20', 'Approved', 'amit.sharma', '2026-02-20', NULL),
(5, 'CREATE', 'pryy', 'pr', 'Password@#123', 'pryy', 'CO', 'amit.sharma', '2026-02-20', 'Approved', 'amit.sharma', '2026-02-20', NULL),
(6, 'CREATE', 'priy', 'pri', 'Password@#123', 'priy', 'USER', 'amit.sharma', '2026-02-20', 'Approved', 'amit.sharma', '2026-02-20', NULL),
(7, 'CREATE', 'Himanshu Negi', 'Himnegi', 'Password@3123456', 'JK098765L', 'USER', 'SELF', '2026-02-21', 'Approved', 'amit.sharma', '2026-02-21', NULL),
(8, 'CREATE', 'karan bisht', 'kra.dishtt', 'Password@#123', 'ik9876543', 'CO', 'neha.verma', '2026-02-21', 'Pending', NULL, NULL, NULL),
(9, 'DELETE', 'ererr', 'errrr', 'Password@#123', 'errrr', 'ADMIN', 'sneha.kapoor', '2026-02-21', 'Pending', NULL, NULL, NULL),
(10, 'DELETE', 'werr', 'wee', 'Password@#123', 'weee', 'CO', 'sneha.kapoor', '2026-02-21', 'Pending', NULL, NULL, NULL),
(11, 'EDIT', 'yuuu', 'uii', 'Password@#123', 'uiiii', 'ITJCO', 'sneha.kapoor', '2026-02-21', 'Pending', NULL, NULL, NULL),
(12, 'CREATE', 'Jyoti Sharma', 'jyoti.sharma', 'Password@#123', 'JC098765', 'CO', 'sneha.kapoor', '2026-02-21', 'Pending', NULL, NULL, NULL),
(13, 'EDIT', 'Subedar Singh Prakash ', 'prakash.singh', '', 'JC-2007', 'ITJCO', 'sneha.kapoor', '2026-02-22', 'Pending', NULL, NULL, NULL),
(18, 'DELETE', 'Himanshu Negi', 'Himnegi', '', 'JK098765L', 'USER', 'sneha.kapoor', '2026-02-22', 'Pending', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allocation`
--
ALTER TABLE `allocation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `condemnation_requests`
--
ALTER TABLE `condemnation_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disposal`
--
ALTER TABLE `disposal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial_no` (`serial_no`),
  ADD UNIQUE KEY `serial_no_2` (`serial_no`);

--
-- Indexes for table `equipment_status_requests`
--
ALTER TABLE `equipment_status_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `equipment_types`
--
ALTER TABLE `equipment_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `maintenance_schedule`
--
ALTER TABLE `maintenance_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `army_no` (`army_no`);

--
-- Indexes for table `user_requests`
--
ALTER TABLE `user_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `allocation`
--
ALTER TABLE `allocation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `condemnation_requests`
--
ALTER TABLE `condemnation_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `disposal`
--
ALTER TABLE `disposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `equipment_status_requests`
--
ALTER TABLE `equipment_status_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `equipment_types`
--
ALTER TABLE `equipment_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `maintenance_schedule`
--
ALTER TABLE `maintenance_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_requests`
--
ALTER TABLE `user_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `allocation`
--
ALTER TABLE `allocation`
  ADD CONSTRAINT `allocation_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`);

--
-- Constraints for table `equipment_status_requests`
--
ALTER TABLE `equipment_status_requests`
  ADD CONSTRAINT `equipment_status_requests_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`);

--
-- Constraints for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
