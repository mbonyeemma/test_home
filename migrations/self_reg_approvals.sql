-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Jul 03, 2025 at 07:53 AM
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
-- Table structure for table `self_reg_approvals`
--

CREATE TABLE `self_reg_approvals` (
  `id` int(11) NOT NULL,
  `self_reg_id` int(11) NOT NULL,
  `approved_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `self_reg_approvals`
--

INSERT INTO `self_reg_approvals` (`id`, `self_reg_id`, `approved_by`, `updated_at`, `created_at`) VALUES
(1, 1977, 2, '2025-06-24 20:37:08', '2025-06-24 20:37:08'),
(10, 1976, 2, '2025-06-25 09:02:59', '2025-06-25 09:02:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `self_reg_approvals`
--
ALTER TABLE `self_reg_approvals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `self_reg_approvals`
--
ALTER TABLE `self_reg_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
