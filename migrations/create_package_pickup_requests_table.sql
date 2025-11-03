CREATE TABLE IF NOT EXISTS `package_pickup_requests` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `package_id` int unsigned NOT NULL,
  `requested_by` int unsigned NOT NULL,
  `hub_id` int unsigned NOT NULL,
  `riders_notified` int DEFAULT '0',
  `emails_sent` int DEFAULT '0',
  `sms_sent` int DEFAULT '0',
  `app_notifications_sent` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `package_id` (`package_id`),
  KEY `hub_id` (`hub_id`),
  KEY `requested_by` (`requested_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

