-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2025 at 09:27 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `barangay_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `announcement_title` varchar(255) NOT NULL,
  `announcement_content` text NOT NULL,
  `announcement_category` enum('General','Emergency','Event','Health','Other') DEFAULT 'General',
  `announcement_location` varchar(255) DEFAULT NULL,
  `announcement_image` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `status` enum('Draft','Published','Archived') DEFAULT 'Published',
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL,
  `priority` enum('Low','Normal','High','Urgent') DEFAULT 'Normal',
  `audience` enum('Public','Officials','Residents','Staff') DEFAULT 'Public',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `archived_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `approvals`
--

CREATE TABLE `approvals` (
  `approval_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('Approved','Rejected') NOT NULL,
  `remarks` text DEFAULT NULL,
  `approved_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_requests`
--

CREATE TABLE `document_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_name` varchar(100) NOT NULL,
  `purpose` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Denied') NOT NULL DEFAULT 'Pending',
  `requested_at` datetime NOT NULL DEFAULT current_timestamp(),
  `processed_by` int(11) DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `event_description` text NOT NULL,
  `event_start` datetime NOT NULL,
  `event_end` datetime NOT NULL,
  `event_location` varchar(255) NOT NULL,
  `event_type` enum('Community','Cultural','Health','Sports','Emergency','Other') DEFAULT 'Other',
  `event_image` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `audience` enum('Public','Residents','Officials','Staff','Admin') DEFAULT 'Public',
  `status` enum('Upcoming','Ongoing','Completed','Cancelled') DEFAULT 'Upcoming',
  `is_archived` tinyint(1) DEFAULT 0,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officials`
--

CREATE TABLE `officials` (
  `official_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `position` varchar(255) NOT NULL,
  `start_of_term` date NOT NULL,
  `end_of_term` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officials`
--

INSERT INTO `officials` (`official_id`, `user_id`, `position`, `start_of_term`, `end_of_term`) VALUES
(2, 2, 'Barangay Secretary', '2025-09-07', '2027-06-14');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `password_reset_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `password_reset_token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_sent_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `reminder_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reminder_type` varchar(100) DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Resident','Official') NOT NULL DEFAULT 'Resident',
  `is_alive` tinyint(1) DEFAULT 1,
  `status` enum('Pending','Approved','Rejected','Verified') DEFAULT 'Pending',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `archived_at` datetime DEFAULT NULL,
  `dead_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `role`, `is_alive`, `status`, `is_deleted`, `deleted_at`, `is_archived`, `created_at`, `updated_at`, `archived_at`, `dead_at`) VALUES
(2, 'poblacionsur648@gmail.com', '$2y$10$Tp1wUH.oBLHntccJ0FjvkuP2ebA0uuDFMPa76PAC3Xe6LfcOOduFe', 'Official', 1, 'Approved', 0, NULL, 0, '2025-09-07 16:55:23', '2025-09-14 13:49:47', NULL, NULL),
(5, 'poblacionsur648+3@gmail.com', '$2y$10$m5DRLRuL09egiA3MsNUC2eLTHX7fQBGFbEjMKGBcoXNBx9Q0Jjvw2', 'Admin', 1, 'Approved', 0, NULL, 0, '2025-09-07 18:25:46', '2025-09-14 13:50:58', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_birthdates`
--

CREATE TABLE `user_birthdates` (
  `user_birthdate_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_birthdates`
--

INSERT INTO `user_birthdates` (`user_birthdate_id`, `user_id`, `birth_date`, `birth_place`) VALUES
(1, 2, NULL, NULL),
(4, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `user_details_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `f_name` varchar(50) NOT NULL,
  `m_name` varchar(50) DEFAULT NULL,
  `l_name` varchar(50) NOT NULL,
  `ext_name` varchar(50) DEFAULT NULL,
  `gender` enum('Male','Female','Other','Prefer not to say') DEFAULT NULL,
  `photo` varchar(255) NOT NULL,
  `contact_no` varchar(50) DEFAULT NULL,
  `civil_status` varchar(50) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `voter_status` enum('Yes','No') DEFAULT NULL,
  `valid_id_path` varchar(255) DEFAULT NULL,
  `pwd_status` enum('Yes','No') DEFAULT NULL,
  `senior_citizen_status` enum('Yes','No') DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `educational_attainment` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`user_details_id`, `user_id`, `f_name`, `m_name`, `l_name`, `ext_name`, `gender`, `photo`, `contact_no`, `civil_status`, `occupation`, `nationality`, `voter_status`, `valid_id_path`, `pwd_status`, `senior_citizen_status`, `religion`, `blood_type`, `educational_attainment`) VALUES
(2, 2, 'CHRISTAL JOY', 'S.', 'PALILIO', NULL, 'Female', '', '9123456789', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 5, 'CRIZEL', 'VALENZUELA', 'GALVEZ', '', NULL, '', '9123456789', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_family_info`
--

CREATE TABLE `user_family_info` (
  `family_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fathers_name` varchar(255) DEFAULT NULL,
  `fathers_birthplace` varchar(255) DEFAULT NULL,
  `mothers_name` varchar(255) DEFAULT NULL,
  `mothers_birthplace` varchar(255) DEFAULT NULL,
  `spouse_name` varchar(255) DEFAULT NULL,
  `num_dependents` int(11) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `emergency_contact_no` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_health_info`
--

CREATE TABLE `user_health_info` (
  `health_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `health_condition` varchar(100) DEFAULT NULL,
  `common_health_issue` varchar(100) DEFAULT NULL,
  `vaccination_status` varchar(50) DEFAULT NULL,
  `height_cm` decimal(5,2) DEFAULT NULL,
  `weight_kg` decimal(5,2) DEFAULT NULL,
  `last_medical_checkup` date DEFAULT NULL,
  `health_remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_identity_docs`
--

CREATE TABLE `user_identity_docs` (
  `identity_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `id_type` varchar(100) DEFAULT NULL,
  `front_valid_id_path` varchar(255) DEFAULT NULL,
  `back_valid_id_path` varchar(255) DEFAULT NULL,
  `selfie_with_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_income_info`
--

CREATE TABLE `user_income_info` (
  `income_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `monthly_income` decimal(10,2) DEFAULT NULL,
  `income_source` varchar(100) DEFAULT NULL,
  `household_members` int(11) DEFAULT NULL,
  `additional_income_sources` varchar(255) DEFAULT NULL,
  `household_head_occupation` varchar(255) DEFAULT NULL,
  `income_proof` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_residency`
--

CREATE TABLE `user_residency` (
  `user_residency_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `house_no` varchar(255) DEFAULT NULL,
  `purok` varchar(100) DEFAULT NULL,
  `barangay` varchar(100) NOT NULL DEFAULT 'POBLACION SUR',
  `municipality` varchar(100) NOT NULL DEFAULT 'TALAVERA',
  `province` varchar(100) NOT NULL DEFAULT 'NUEVA ECIJA',
  `years_residency` int(11) DEFAULT NULL,
  `household_head` enum('Yes','No') DEFAULT NULL,
  `house_type` varchar(100) DEFAULT NULL,
  `ownership_status` varchar(100) DEFAULT NULL,
  `previous_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_residency`
--

INSERT INTO `user_residency` (`user_residency_id`, `user_id`, `house_no`, `purok`, `barangay`, `municipality`, `province`, `years_residency`, `household_head`, `house_type`, `ownership_status`, `previous_address`) VALUES
(3, 5, '13', 'PUROK3', 'POBLACION SUR', 'TALAVERA', 'NUEVA ECIJA', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `verifications`
--

CREATE TABLE `verifications` (
  `verification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `verified_at` datetime DEFAULT NULL,
  `session_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `verifications`
--

INSERT INTO `verifications` (`verification_id`, `user_id`, `token`, `expires_at`, `verified_at`, `session_token`) VALUES
(1, 2, '8b4e7f1d11bdc519c67c6cb27b77b401', '2025-09-08 16:55:23', '2025-09-07 16:56:01', 'a00b4243318b332c9f9f7e5c6f0d1032d201838b5e83c619a44904a9ad49f07f'),
(4, 5, '2c920e5caf01a259022d7f68856d5223', '2025-09-08 18:25:46', '2025-09-07 18:26:11', '8ed742b4528abb4194c923ed343746cac03645fd7819d485f90608e385a3e6e3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`approval_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `document_requests`
--
ALTER TABLE `document_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `fk_user_request` (`user_id`),
  ADD KEY `fk_processed_by` (`processed_by`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `officials`
--
ALTER TABLE `officials`
  ADD PRIMARY KEY (`official_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`password_reset_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`reminder_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_birthdates`
--
ALTER TABLE `user_birthdates`
  ADD PRIMARY KEY (`user_birthdate_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`user_details_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_family_info`
--
ALTER TABLE `user_family_info`
  ADD PRIMARY KEY (`family_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_health_info`
--
ALTER TABLE `user_health_info`
  ADD PRIMARY KEY (`health_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_identity_docs`
--
ALTER TABLE `user_identity_docs`
  ADD PRIMARY KEY (`identity_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_income_info`
--
ALTER TABLE `user_income_info`
  ADD PRIMARY KEY (`income_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_residency`
--
ALTER TABLE `user_residency`
  ADD PRIMARY KEY (`user_residency_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `verifications`
--
ALTER TABLE `verifications`
  ADD PRIMARY KEY (`verification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `approvals`
--
ALTER TABLE `approvals`
  MODIFY `approval_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `document_requests`
--
ALTER TABLE `document_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `officials`
--
ALTER TABLE `officials`
  MODIFY `official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `password_reset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_birthdates`
--
ALTER TABLE `user_birthdates`
  MODIFY `user_birthdate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `user_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_family_info`
--
ALTER TABLE `user_family_info`
  MODIFY `family_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_health_info`
--
ALTER TABLE `user_health_info`
  MODIFY `health_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_identity_docs`
--
ALTER TABLE `user_identity_docs`
  MODIFY `identity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_income_info`
--
ALTER TABLE `user_income_info`
  MODIFY `income_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_residency`
--
ALTER TABLE `user_residency`
  MODIFY `user_residency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `verifications`
--
ALTER TABLE `verifications`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `approvals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `document_requests`
--
ALTER TABLE `document_requests`
  ADD CONSTRAINT `fk_processed_by` FOREIGN KEY (`processed_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_user_request` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `officials`
--
ALTER TABLE `officials`
  ADD CONSTRAINT `officials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `reminders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_birthdates`
--
ALTER TABLE `user_birthdates`
  ADD CONSTRAINT `user_birthdates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_details`
--
ALTER TABLE `user_details`
  ADD CONSTRAINT `user_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_family_info`
--
ALTER TABLE `user_family_info`
  ADD CONSTRAINT `user_family_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_health_info`
--
ALTER TABLE `user_health_info`
  ADD CONSTRAINT `user_health_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_identity_docs`
--
ALTER TABLE `user_identity_docs`
  ADD CONSTRAINT `user_identity_docs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_income_info`
--
ALTER TABLE `user_income_info`
  ADD CONSTRAINT `user_income_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_residency`
--
ALTER TABLE `user_residency`
  ADD CONSTRAINT `user_residency_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `verifications`
--
ALTER TABLE `verifications`
  ADD CONSTRAINT `verifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
