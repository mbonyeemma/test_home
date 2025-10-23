-- Drop and recreate password_resets table with correct structure
DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `phone_number` varchar(20) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `reset_token` varchar(100) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_phone_number` (`phone_number`),
  KEY `idx_reset_token` (`reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

