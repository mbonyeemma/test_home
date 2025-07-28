-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Jul 03, 2025 at 07:51 AM
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
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `form_id` varchar(250) NOT NULL,
  `form_submission_url` varchar(255) DEFAULT NULL,
  `publish_status` enum('draft','pending_approval','approved') NOT NULL DEFAULT 'draft',
  `submitted_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `name`, `form_id`, `form_submission_url`, `publish_status`, `submitted_by`, `approved_by`, `updated_at`, `created_at`) VALUES
(3, 'form2', 'FORM-685B8035A61C1', NULL, 'pending_approval', 2, NULL, '2025-07-02 17:37:23', '2025-06-25 07:51:09'),
(4, 'form2', 'FORM-685B806811EFF', 'http://localhost:8091/index.php?route=/sql&pos=0&db=hub_db&table=users', 'pending_approval', 2, NULL, '2025-07-02 17:34:43', '2025-06-25 07:52:01'),
(7, 'form3', 'FORM-685B862AD6917', 'http://localhost:8091/index.php?route=/sql&pos=0&db=hub_db&table=users', 'approved', NULL, NULL, '2025-07-02 16:55:39', '2025-06-25 08:16:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Unique_form_id` (`form_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
