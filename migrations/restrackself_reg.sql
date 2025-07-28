-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Jul 28, 2025 at 05:13 AM
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
-- Table structure for table `restrackself_reg`
--

CREATE TABLE `restrackself_reg` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hubid` int(10) UNSIGNED NOT NULL,
  `facilityid` int(10) UNSIGNED DEFAULT '0',
  `ref_lab` int(10) UNSIGNED DEFAULT '0',
  `healthregionid` int(10) UNSIGNED DEFAULT NULL,
  `isactive` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `staff_id` int(10) UNSIGNED DEFAULT NULL,
  `organisation_id` int(10) UNSIGNED DEFAULT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `idp_key` text COLLATE utf8mb4_unicode_ci,
  `telephone_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driving_permit` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `defensive_driving` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bb_training` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hep_b_immunisation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `role` enum('rider','data_collector','driver','hub_cordinator') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'rider'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `restrackself_reg`
--
ALTER TABLE `restrackself_reg`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `users_email_unique` (`email`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `restrackself_reg`
--
ALTER TABLE `restrackself_reg`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
