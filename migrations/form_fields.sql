-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Jul 03, 2025 at 07:52 AM
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
-- Table structure for table `form_fields`
--

CREATE TABLE `form_fields` (
  `id` int(11) NOT NULL,
  `forms_id` int(11) NOT NULL,
  `field_type` varchar(250) NOT NULL,
  `field_label` varchar(25) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `field_value` varchar(250) DEFAULT NULL,
  `option` enum('mandatory','optional') NOT NULL DEFAULT 'optional',
  `status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  `dropdown_options` text,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `form_fields`
--

INSERT INTO `form_fields` (`id`, `forms_id`, `field_type`, `field_label`, `name`, `field_value`, `option`, `status`, `dropdown_options`, `updated_at`, `created_at`) VALUES
(4, 7, 'input', NULL, 'name', NULL, 'mandatory', 'enabled', NULL, '2025-06-25 08:17:49', '2025-06-25 08:17:49'),
(5, 7, 'dropdown', NULL, 'gender', NULL, 'optional', 'enabled', '[\"male\",\"female\"]', '2025-06-25 08:17:49', '2025-06-25 08:17:49'),
(6, 7, 'checkbox', NULL, 'available', NULL, 'mandatory', 'enabled', NULL, '2025-06-25 08:17:49', '2025-06-25 08:17:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `form_fields`
--
ALTER TABLE `form_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_foreign_key` (`forms_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `form_fields`
--
ALTER TABLE `form_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `form_fields`
--
ALTER TABLE `form_fields`
  ADD CONSTRAINT `form_foreign_key` FOREIGN KEY (`forms_id`) REFERENCES `forms` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
