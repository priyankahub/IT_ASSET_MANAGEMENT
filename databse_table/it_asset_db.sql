-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2026 at 12:23 PM
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
  `status` enum('Serviceable','Under Repair','Unserviceable','Condemned') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `type`, `make`, `model`, `serial_no`, `purchase_date`, `warranty_end`, `cost`, `status`) VALUES
(1, 'Artilery', 'Indian', 'IND001', 'XYZ001', '2026-01-01', '2026-12-31', 10001.00000, 'Condemned'),
(2, 'Eqip1', 'France', 'F002', 'F23123', '2023-06-30', '2026-01-29', 99999.99999, 'Condemned'),
(3, 'Gun', 'China', 'CH991', 'CH76552', '2026-01-01', '2026-01-31', 99999.99999, 'Serviceable'),
(4, 'Tank', '342345', 'asdf', '2134', '2026-01-01', '2026-01-30', 98797.00000, 'Condemned'),
(5, 'Tank', '342345', 'asdf', '2134', '2026-01-01', '2026-01-30', 98797.00000, 'Serviceable'),
(6, 'Tank', 'America', 'AM00987', '3456789tyu', '2026-01-01', '2026-02-28', 2365.00000, 'Condemned'),
(7, 'Bomb', 'Russia', 'RS92892', '678tyu', '2025-12-31', '2026-02-28', 10000.00000, 'Condemned');

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
  `rank` enum('ADMIN','CO','ITJCO','CLERK','USER') NOT NULL,
  `army_no` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `rank`, `army_no`, `created_at`) VALUES
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
(14, 'pryy', 'pryy', 'Password@#123', 'CO', 'pryy', '2026-02-20 10:48:11');

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
  `rank` enum('ADMIN','CO','ITJCO','CLERK','USER') DEFAULT NULL,
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

INSERT INTO `user_requests` (`id`, `request_type`, `full_name`, `username`, `password`, `army_no`, `rank`, `requested_by`, `request_date`, `status`, `approved_by`, `approval_date`, `remarks`) VALUES
(1, 'CREATE', 'Karan Negi', 'negi001', 'negi001', 'jk6789', 'USER', 'clerk', '2026-02-18', 'Approved', 'admin', '2026-02-18', NULL),
(2, 'CREATE', 'karan bisht', 'bisht001', 'bisht001', 'ki98765', 'ADMIN', 'clerk', '2026-02-18', 'Rejected', 'admin', '2026-02-18', 'Retired'),
(4, 'CREATE', 'Priyanka Rawat', 'priyanka.rawat', 'Priyanka#27', 'COL098765', 'CLERK', 'SELF', '2026-02-20', 'Approved', 'amit.sharma', '2026-02-20', NULL),
(5, 'CREATE', 'pryy', 'pr', 'Password@#123', 'pryy', 'CO', 'amit.sharma', '2026-02-20', 'Approved', 'amit.sharma', '2026-02-20', NULL),
(6, 'CREATE', 'priy', 'pri', 'Password@#123', 'priy', 'USER', 'amit.sharma', '2026-02-20', 'Approved', 'amit.sharma', '2026-02-20', NULL);

--
-- Indexes for dumped tables
--

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
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `army_no` (`army_no`),
  ADD UNIQUE KEY `army_no_2` (`army_no`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disposal`
--
ALTER TABLE `disposal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_requests`
--
ALTER TABLE `user_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `allocation`
--
ALTER TABLE `allocation`
  ADD CONSTRAINT `allocation_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`);

--
-- Constraints for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
