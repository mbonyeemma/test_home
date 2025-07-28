-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Jul 28, 2025 at 05:17 AM
-- Server version: 5.7.44
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hub_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `field_changes`
--

CREATE TABLE `field_changes` (
  `id` int(11) NOT NULL,
  `form_field_id` int(25) DEFAULT NULL,
  `form_id` int(25) NOT NULL,
  `maker_id` int(25) UNSIGNED NOT NULL,
  `checker_id` int(25) UNSIGNED DEFAULT NULL,
  `action` enum('create','update','delete') NOT NULL,
  `field_data` json NOT NULL,
  `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `checked_at` timestamp NULL DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `field_changes`
--

INSERT INTO `field_changes` (`id`, `form_field_id`, `form_id`, `maker_id`, `checker_id`, `action`, `field_data`, `approval_status`, `checked_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 7, 3, 3, 'create', '{\"name\": \"testing\", \"option\": \"mandatory\", \"status\": \"enabled\", \"maker_id\": 3, \"field_type\": \"input\", \"field_label\": \"testing\", \"field_value\": null, \"approval_status\": \"pending\", \"dropdown_options\": null}', 'approved', '2025-07-10 12:16:12', '2025-07-10 12:15:23', '2025-07-10 12:16:12'),
(2, NULL, 7, 3, NULL, 'create', '{\"name\": \"example\", \"option\": \"mandatory\", \"status\": \"enabled\", \"maker_id\": 3, \"field_type\": \"input\", \"field_label\": \"example\", \"field_value\": null, \"approval_status\": \"pending\", \"dropdown_options\": null}', 'pending', NULL, '2025-07-10 12:15:56', '2025-07-10 12:15:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `field_changes`
--
ALTER TABLE `field_changes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_fk` (`form_id`),
  ADD KEY `form_field_fk` (`form_field_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `field_changes`
--
ALTER TABLE `field_changes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `field_changes`
--
ALTER TABLE `field_changes`
  ADD CONSTRAINT `form_field_fk` FOREIGN KEY (`form_field_id`) REFERENCES `form_fields` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `form_fk` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
