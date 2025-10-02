-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `approval_settings`;
CREATE TABLE `approval_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_of_approval` int(25) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `assessment`;
CREATE TABLE `assessment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `inputype` tinyint(3) NOT NULL,
  `category` tinyint(3) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `createdby` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueIndex` (`question`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `assessmentanswer`;
CREATE TABLE `assessmentanswer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `assessmentid` int(11) NOT NULL,
  `facilityid` int(3) NOT NULL,
  `answeroption` tinyint(3) DEFAULT NULL,
  `answer` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `createdby` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `assessmentoption`;
CREATE TABLE `assessmentoption` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `assessmentid` int(11) unsigned NOT NULL,
  `optionevalue` varchar(50) NOT NULL,
  `optiondescription` varchar(255) NOT NULL,
  `createdby` int(11) unsigned NOT NULL,
  `datecreated` datetime NOT NULL,
  `lastupdatedate` datetime DEFAULT NULL,
  `lastupdatedby` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `backend_facilities`;
CREATE TABLE `backend_facilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facility` varchar(128) NOT NULL,
  `hub_facility` tinyint(1) NOT NULL,
  `facility_contact` varchar(64) DEFAULT NULL,
  `facility_email` varchar(128) DEFAULT NULL,
  `physical_address` varchar(128) DEFAULT NULL,
  `return_address` varchar(128) DEFAULT NULL,
  `coordinator_name` varchar(64) DEFAULT NULL,
  `coordinator_contact` varchar(64) DEFAULT NULL,
  `coordinator_email` varchar(128) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `district_id` int(11) DEFAULT NULL,
  `hub_id` int(11) DEFAULT NULL,
  `dhis2_name` varchar(128) DEFAULT NULL,
  `dhis2_uid` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `backend_facilities_facility_4fe8b8cb_uniq` (`facility`),
  KEY `backend_facilities_97469368` (`hub_id`),
  KEY `backend_facilities_district_id_376390b7_fk_backend_districts_id` (`district_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `checklogin`;
CREATE TABLE `checklogin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hubid` int(11) unsigned DEFAULT NULL,
  `facilityid` int(11) unsigned DEFAULT NULL,
  `bikeid` int(11) unsigned DEFAULT NULL,
  `thedate` date NOT NULL,
  `place_name` varchar(250) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `staffid` int(11) NOT NULL,
  `lastupdatedby` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `organizationid` int(11) unsigned DEFAULT NULL,
  `hubid` int(11) unsigned DEFAULT NULL,
  `districtid` int(11) unsigned DEFAULT NULL,
  `dlfpdistrictid` int(11) unsigned DEFAULT NULL,
  `category` tinyint(3) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `isactive` tinyint(3) unsigned NOT NULL,
  `designation` tinyint(3) unsigned DEFAULT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `othernames` varchar(255) DEFAULT NULL,
  `emailaddress` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `telephonenumber` varchar(50) NOT NULL,
  `phone2` varchar(50) DEFAULT NULL,
  `phone3` varchar(50) DEFAULT NULL,
  `phone4` varchar(50) DEFAULT NULL,
  `createdby` int(11) NOT NULL,
  `lastupdatedby` int(11) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `datedeactivated` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `covid`;
CREATE TABLE `covid` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hubid` int(11) unsigned DEFAULT NULL,
  `facilityid` int(11) unsigned DEFAULT NULL,
  `barcodeid` varchar(50) DEFAULT NULL,
  `dailyroutingreasonid` int(11) unsigned DEFAULT NULL,
  `transporterid` int(11) unsigned DEFAULT NULL,
  `sample_type` int(11) NOT NULL,
  `numberofsamples` int(11) unsigned DEFAULT NULL,
  `transactiondate` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `createdby` int(11) DEFAULT NULL,
  `lastupdatedby` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `dailyrouting`;
CREATE TABLE `dailyrouting` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `hubid` int(11) unsigned NOT NULL,
  `bikeid` int(11) unsigned NOT NULL,
  `transporterid` int(11) unsigned NOT NULL,
  `thedate` date DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  `lastupdatedby` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `dailyroutingdetail`;
CREATE TABLE `dailyroutingdetail` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `hubid` int(11) unsigned NOT NULL,
  `facilityid` int(11) unsigned DEFAULT NULL,
  `bikeid` int(11) unsigned DEFAULT NULL,
  `dailyroutingreasonid` int(11) unsigned DEFAULT NULL,
  `transporterid` int(11) unsigned DEFAULT NULL,
  `samplecategory` tinyint(3) unsigned DEFAULT NULL,
  `numberofsamples` int(11) unsigned DEFAULT NULL,
  `numberofresults` int(11) unsigned DEFAULT NULL,
  `dailyroutingid` int(11) unsigned DEFAULT NULL,
  `distanceatstart` decimal(10,6) unsigned DEFAULT NULL,
  `distanceatend` decimal(10,6) unsigned DEFAULT NULL,
  `thedate` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  `lastupdatedby` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `dailyroutingreason`;
CREATE TABLE `dailyroutingreason` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `hubid` int(11) unsigned DEFAULT NULL,
  `facilityid` int(11) unsigned DEFAULT NULL,
  `reason` tinyint(3) unsigned DEFAULT NULL,
  `thedate` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  `lastupdatedby` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `district`;
CREATE TABLE `district` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `nr` mediumint(8) unsigned NOT NULL,
  `districtcode` varchar(20) NOT NULL,
  `name` varchar(234) NOT NULL,
  `regionid` tinyint(3) unsigned NOT NULL,
  `province` int(3) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  `health_region_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `regionIDIndex` (`regionid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `equipment`;
CREATE TABLE `equipment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `facilityid` int(11) unsigned DEFAULT NULL,
  `breakdownid` int(11) unsigned DEFAULT NULL,
  `enginenumber` varchar(50) NOT NULL,
  `sampletransporterid` int(11) DEFAULT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `isassigned` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `chasisnumber` varchar(50) NOT NULL,
  `modelnumber` varchar(50) DEFAULT NULL,
  `hubid` int(11) unsigned DEFAULT NULL,
  `brand` varchar(50) NOT NULL,
  `yearofmanufacture` int(4) unsigned DEFAULT NULL,
  `enginecapacity` varchar(50) DEFAULT NULL,
  `insurance` varchar(50) DEFAULT NULL,
  `numberplate` varchar(50) NOT NULL,
  `maintenanceschedule` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `purchasedon` date DEFAULT NULL,
  `deliveredtohubon` date DEFAULT NULL,
  `warrantyperiod` smallint(5) unsigned DEFAULT '1',
  `warrantyperiodunits` varchar(25) DEFAULT NULL,
  `distancebeforeservice` int(11) DEFAULT '1',
  `recommendedservicefrequency` int(11) DEFAULT '1',
  `servicefrequencyunits` tinyint(3) unsigned DEFAULT '1',
  `hasservicecontract` tinyint(3) DEFAULT '1',
  `reasonfornoserviceid` int(11) DEFAULT '1',
  `color` varchar(20) DEFAULT NULL,
  `createdby` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `equipmentbreakdown`;
CREATE TABLE `equipmentbreakdown` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `hubid` int(11) unsigned DEFAULT NULL,
  `bikeid` int(11) unsigned DEFAULT NULL,
  `mechanicid` int(11) unsigned DEFAULT NULL,
  `datebrokendown` date NOT NULL,
  `reportingdate` date NOT NULL,
  `brokendownenddate` date DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `reportedby` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  `lastupdatedby` int(11) DEFAULT NULL,
  `closedby` int(11) DEFAULT NULL,
  `closingnotes` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `equipmentbreakdownaction`;
CREATE TABLE `equipmentbreakdownaction` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `action` tinyint(3) unsigned NOT NULL,
  `equipmentbreakdownid` int(11) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `equipmentbreakdownreason`;
CREATE TABLE `equipmentbreakdownreason` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reason` tinyint(3) unsigned DEFAULT NULL,
  `equipmentbreakdownid` int(11) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `facility`;
CREATE TABLE `facility` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` mediumint(8) unsigned DEFAULT '0',
  `ipid` mediumint(8) unsigned DEFAULT NULL,
  `code` varchar(250) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `old_name` varchar(250) DEFAULT NULL,
  `dhis2_name` varchar(250) DEFAULT NULL,
  `facilitylevelid` smallint(2) NOT NULL,
  `type` tinyint(3) unsigned zerofill DEFAULT NULL,
  `isactive` tinyint(3) unsigned DEFAULT NULL,
  `districtid` mediumint(8) unsigned NOT NULL,
  `regionid` int(11) unsigned DEFAULT NULL,
  `hubid` mediumint(8) unsigned NOT NULL,
  `healthregionid` mediumint(8) unsigned DEFAULT NULL,
  `hubname` varchar(250) DEFAULT NULL,
  `inchargephonenumber` varchar(20) DEFAULT NULL,
  `distancefromhub` decimal(10,2) unsigned DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `incharge` varchar(250) DEFAULT NULL,
  `labmanager` varchar(250) DEFAULT NULL,
  `labmanagerphonenumber` varchar(50) DEFAULT NULL,
  `address` text,
  `returnaddress` text,
  `phoneserialno` varchar(30) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `latitude` decimal(10,6) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `createdby` varchar(250) DEFAULT NULL,
  `dhis2_uid` varchar(250) DEFAULT NULL,
  `is_mini_hub` mediumint(9) DEFAULT '0',
  `is_ref_lab_and_hub` mediumint(9) DEFAULT '0',
  `deleted` datetime DEFAULT NULL,
  `is_active` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000',
  `health_region` int(10) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `districtIDIndex` (`districtid`),
  KEY `hubIDIndex` (`hubid`),
  KEY `facilityLevelIDIndex` (`facilitylevelid`),
  KEY `uniqueIndex` (`name`,`districtid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `facilitylabequipment`;
CREATE TABLE `facilitylabequipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hubid` int(11) NOT NULL,
  `labequipment_id` int(11) NOT NULL,
  `model` varchar(30) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `breakdownid` int(11) unsigned DEFAULT NULL,
  `serial_number` varchar(30) NOT NULL,
  `location` int(11) NOT NULL,
  `procurement_type` int(11) NOT NULL,
  `purchase_date` date NOT NULL,
  `delivery_date` date NOT NULL,
  `verification_date` date NOT NULL,
  `installation_date` date NOT NULL,
  `spare_parts` tinyint(3) NOT NULL,
  `warranty` int(11) NOT NULL,
  `life_span` int(11) NOT NULL,
  `service_frequency` tinyint(3) NOT NULL,
  `service_contract` tinyint(3) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdby` varchar(250) DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `facilitylevel`;
CREATE TABLE `facilitylevel` (
  `id` smallint(2) unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueIndex` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `field_changes`;
CREATE TABLE `field_changes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_field_id` int(25) DEFAULT NULL,
  `form_id` int(25) NOT NULL,
  `maker_id` int(25) unsigned NOT NULL,
  `checker_id` int(25) unsigned DEFAULT NULL,
  `action` enum('create','update','delete') NOT NULL,
  `field_data` json NOT NULL,
  `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `checked_at` timestamp NULL DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `form_fk` (`form_id`),
  KEY `form_field_fk` (`form_field_id`),
  CONSTRAINT `form_field_fk` FOREIGN KEY (`form_field_id`) REFERENCES `form_fields` (`id`) ON DELETE SET NULL,
  CONSTRAINT `form_fk` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `forms`;
CREATE TABLE `forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `form_id` varchar(250) NOT NULL,
  `form_submission_url` varchar(255) DEFAULT NULL,
  `publish_status` enum('draft','pending_approval','approved') NOT NULL DEFAULT 'draft',
  `submitted_by` int(10) unsigned DEFAULT NULL,
  `approved_by` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Unique_form_id` (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `form_fields`;
CREATE TABLE `form_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forms_id` int(11) NOT NULL,
  `field_type` varchar(250) NOT NULL,
  `field_label` varchar(25) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `field_value` varchar(250) DEFAULT NULL,
  `option` enum('mandatory','optional') NOT NULL DEFAULT 'optional',
  `status` enum('enabled','disabled') NOT NULL DEFAULT 'enabled',
  `dropdown_options` text,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `form_foreign_key` (`forms_id`),
  CONSTRAINT `form_foreign_key` FOREIGN KEY (`forms_id`) REFERENCES `forms` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


SET NAMES utf8mb4;

DROP TABLE IF EXISTS `gps_location_details`;
CREATE TABLE `gps_location_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `driver_id` bigint(20) DEFAULT NULL,
  `latitude` varchar(250) DEFAULT NULL,
  `longitude` varchar(250) DEFAULT NULL,
  `place_name` varchar(250) DEFAULT NULL,
  `date_captured` varchar(250) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `healthregion`;
CREATE TABLE `healthregion` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  `Is Active` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueIndex` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `hub`;
CREATE TABLE `hub` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `facility` varchar(250) DEFAULT NULL,
  `facilityid` int(11) unsigned DEFAULT NULL,
  `email` varchar(250) NOT NULL,
  `address` varchar(250) NOT NULL,
  `healthregionid` int(11) unsigned DEFAULT NULL,
  `ipid` int(11) unsigned DEFAULT NULL,
  `createdby` int(11) unsigned DEFAULT NULL,
  `coordinatorid` int(11) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueIndex` (`name`,`email`),
  KEY `emailIndex` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ips`;
CREATE TABLE `ips` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(250) NOT NULL,
  `classification` varchar(5) NOT NULL,
  `full_name` varchar(125) NOT NULL,
  `address` varchar(125) NOT NULL,
  `focal_person` varchar(100) NOT NULL,
  `focal_person_contact` varchar(100) NOT NULL,
  `description` varchar(150) NOT NULL,
  `funding_source` varchar(150) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniqueIndex` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ips_facilities`;
CREATE TABLE `ips_facilities` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ipID` mediumint(8) unsigned NOT NULL,
  `facilityID` mediumint(8) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `stopped` smallint(1) unsigned NOT NULL,
  `stop_date` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ip_regions`;
CREATE TABLE `ip_regions` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ipID` mediumint(8) unsigned NOT NULL,
  `healthregionid` mediumint(8) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `stopped` smallint(1) unsigned NOT NULL,
  `stop_date` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `labequipment_inventory`;
CREATE TABLE `labequipment_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facility_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `model` varchar(30) NOT NULL,
  `serial_number` varchar(30) NOT NULL,
  `location` int(11) NOT NULL,
  `procurement_type` int(11) NOT NULL,
  `purchase_date` date NOT NULL,
  `delivery_date` date NOT NULL,
  `verification_date` date NOT NULL,
  `installation_date` date NOT NULL,
  `spare_parts` tinyint(3) NOT NULL,
  `warranty` int(11) NOT NULL,
  `life_span` int(11) NOT NULL,
  `service_frequency` tinyint(3) NOT NULL,
  `service_contract` tinyint(3) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createdby` varchar(250) DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `logistic_package`;
CREATE TABLE `logistic_package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package` varchar(30) NOT NULL,
  `source_hubid` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `final_destination_hubid` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `logistic_packagemovement`;
CREATE TABLE `logistic_packagemovement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `packageid` varchar(40) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `source` int(11) NOT NULL,
  `destination` int(11) NOT NULL,
  `taken_by` int(11) NOT NULL,
  `taken_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `recieved_by` int(11) DEFAULT NULL,
  `recieved_at` timestamp NULL DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lookupquery`;
CREATE TABLE `lookupquery` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `querystring` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lookuptype`;
CREATE TABLE `lookuptype` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `datecreated` datetime NOT NULL,
  `createdby` int(11) unsigned NOT NULL,
  `lastupdatedate` datetime DEFAULT NULL,
  `lastupdatedby` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `lookuptypevalue`;
CREATE TABLE `lookuptypevalue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lookuptypeid` int(11) unsigned NOT NULL,
  `lookuptypevalue` varchar(50) NOT NULL,
  `lookupvaluedescription` varchar(255) NOT NULL,
  `createdby` int(11) unsigned NOT NULL,
  `datecreated` datetime NOT NULL,
  `lastupdatedate` datetime DEFAULT NULL,
  `lastupdatedby` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `meetingreport`;
CREATE TABLE `meetingreport` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `facilityid` int(11) unsigned NOT NULL,
  `filename` varchar(100) NOT NULL,
  `title` varchar(500) DEFAULT NULL,
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `mimetype` varchar(75) NOT NULL,
  `filesize` int(10) unsigned NOT NULL,
  `extension` varchar(4) NOT NULL,
  `slug` varchar(500) NOT NULL,
  `createdby` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `lastupdatedby` int(11) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `originalfilename` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `nft_activities`;
CREATE TABLE `nft_activities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `unique_id` varchar(250) CHARACTER SET utf8 NOT NULL,
  `activity_start_date` datetime NOT NULL,
  `from_location_name` varchar(250) CHARACTER SET utf8mb4 DEFAULT NULL,
  `from_location_id` varchar(250) DEFAULT NULL,
  `to_location_name` varchar(100) DEFAULT NULL,
  `to_location_id` varchar(250) DEFAULT NULL,
  `sample_description` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `status` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `riders_name` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `delivered_on` timestamp NULL DEFAULT NULL,
  `entered_by` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE `oauth_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_personal_access_clients_client_id_index` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `organization`;
CREATE TABLE `organization` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `healthregionid` mediumint(8) unsigned DEFAULT NULL,
  `supportagencyid` mediumint(8) unsigned DEFAULT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `isactive` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `telephonenumber` varchar(50) DEFAULT NULL,
  `emailaddress` varchar(50) DEFAULT NULL,
  `createdby` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `package`;
CREATE TABLE `package` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned DEFAULT '0',
  `barcode` varchar(30) NOT NULL,
  `barcode_id` varchar(50) DEFAULT NULL,
  `facilityid` int(11) NOT NULL,
  `case_id` varchar(100) DEFAULT NULL,
  `hubid` int(11) NOT NULL,
  `latest_event_id` int(11) DEFAULT '0',
  `test_type` tinyint(3) DEFAULT '0',
  `sample_type` tinyint(3) DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '1',
  `is_merged` int(3) DEFAULT '0',
  `is_batch` tinyint(1) DEFAULT '0',
  `first_received_at` int(11) NOT NULL DEFAULT '0',
  `place_name` varchar(250) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `numberofsamples` int(11) unsigned DEFAULT '0',
  `numberofpackages` tinyint(11) unsigned DEFAULT '0',
  `numberofsamplesreceived` tinyint(11) unsigned DEFAULT NULL,
  `current_holder` int(11) unsigned DEFAULT '0',
  `delivered_on` timestamp NULL DEFAULT NULL,
  `delivered_by` int(11) DEFAULT '0',
  `received_at_destination_on` timestamp NULL DEFAULT NULL,
  `received_by` int(11) unsigned NOT NULL DEFAULT '0',
  `final_destination` int(11) DEFAULT NULL,
  `is_tracked_from_facility` tinyint(3) DEFAULT NULL,
  `date_picked` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `packagedetail`;
CREATE TABLE `packagedetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `big_barcodeid` int(11) NOT NULL,
  `small_barcodeid` int(11) NOT NULL,
  `final_destination` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `packagemovement`;
CREATE TABLE `packagemovement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `packageid` varchar(40) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `source` int(11) NOT NULL,
  `destination` int(11) NOT NULL,
  `taken_by` int(11) NOT NULL,
  `taken_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `recieved_by` int(11) DEFAULT NULL,
  `recieved_at` timestamp NULL DEFAULT NULL,
  `type_of_movement` int(11) NOT NULL,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `scaned_by_transporter` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `packagemovement_events`;
CREATE TABLE `packagemovement_events` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `source` int(11) DEFAULT NULL,
  `destination` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `place_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `location` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `packagerecipt`;
CREATE TABLE `packagerecipt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `packageid` int(11) NOT NULL,
  `packagetype` int(11) NOT NULL,
  `received_by` int(11) NOT NULL,
  `previous_status` tinyint(3) unsigned NOT NULL,
  `numberofsamples` int(11) NOT NULL,
  `delivered_by` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `reflabsamples_type`;
CREATE TABLE `reflabsamples_type` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(50) NOT NULL,
  `hubid` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `region`;
CREATE TABLE `region` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `pprefix` varchar(10) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueIndex` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `restrackself_reg`;
CREATE TABLE `restrackself_reg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hubid` int(10) unsigned NOT NULL,
  `facilityid` int(10) unsigned DEFAULT '0',
  `ref_lab` int(10) unsigned DEFAULT '0',
  `healthregionid` int(10) unsigned DEFAULT NULL,
  `isactive` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `staff_id` int(10) unsigned DEFAULT NULL,
  `organisation_id` int(10) unsigned DEFAULT NULL,
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
  `role` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `users_email_unique` (`email`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `results`;
CREATE TABLE `results` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `hubid` int(11) NOT NULL,
  `facilityid` int(11) DEFAULT NULL,
  `locator_id` varchar(6000) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `delivered_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `received_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `routingschedule`;
CREATE TABLE `routingschedule` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `hubid` int(11) unsigned DEFAULT NULL,
  `facilityid` int(11) unsigned DEFAULT NULL,
  `dayoftheweek` tinyint(3) unsigned NOT NULL,
  `thedate` date DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `isactive` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  `lastupdatedby` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `samplereferral`;
CREATE TABLE `samplereferral` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sampleid` int(11) unsigned NOT NULL,
  `sourceid` int(11) unsigned NOT NULL,
  `destinationid` int(11) unsigned NOT NULL,
  `sample_number` int(11) unsigned NOT NULL,
  `status` tinyint(3) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `createdby` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `samples`;
CREATE TABLE `samples` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hubid` int(11) unsigned NOT NULL,
  `facilityid` int(11) unsigned DEFAULT NULL,
  `latest_event_id` int(11) unsigned DEFAULT '0',
  `package_id` int(11) unsigned NOT NULL DEFAULT '0',
  `barcode` varchar(50) DEFAULT NULL,
  `sample_type` tinyint(3) unsigned DEFAULT NULL,
  `test_type` tinyint(3) DEFAULT '0',
  `suspected_disease` varchar(30) DEFAULT NULL,
  `surveillance_code` varchar(255) DEFAULT NULL,
  `numberofsamples` int(11) unsigned DEFAULT NULL,
  `date_picked` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `createdby` int(11) DEFAULT NULL,
  `lastupdatedby` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `self_reg_approvals`;
CREATE TABLE `self_reg_approvals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `self_reg_id` int(11) NOT NULL,
  `approved_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `facilityid` int(11) unsigned DEFAULT NULL,
  `hubid` int(11) unsigned DEFAULT NULL,
  `motorbikeid` int(11) unsigned DEFAULT '0',
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `othernames` varchar(50) DEFAULT NULL,
  `emailaddress` varchar(255) DEFAULT NULL,
  `telephonenumber` varchar(255) NOT NULL,
  `telephonenumber2` varchar(255) DEFAULT NULL,
  `telephonenumber3` varchar(255) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `hasdrivingpermit` int(11) DEFAULT NULL,
  `designation` tinyint(3) unsigned DEFAULT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `isactive` tinyint(3) unsigned NOT NULL,
  `hasbbtraining` tinyint(3) unsigned DEFAULT '0',
  `isimmunizedforhb` tinyint(3) unsigned DEFAULT '0',
  `hasdefensiveriding` tinyint(3) unsigned DEFAULT '0',
  `nationalid` varchar(255) DEFAULT NULL,
  `createdby` int(11) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `permitexpirydate` date DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `supportagency`;
CREATE TABLE `supportagency` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(50) DEFAULT NULL,
  `telephonenumber` varchar(50) DEFAULT NULL,
  `emailaddress` varchar(50) DEFAULT NULL,
  `createdby` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `supportagencyperiod`;
CREATE TABLE `supportagencyperiod` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `organizationid` mediumint(8) unsigned DEFAULT NULL,
  `supportagencyid` mediumint(8) unsigned NOT NULL,
  `createdby` int(11) DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `notes` blob,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `testtypes`;
CREATE TABLE `testtypes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `ref_lab` int(11) unsigned DEFAULT '0',
  `require_destination` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `snomed_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `untracked_packages`;
CREATE TABLE `untracked_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barcode` varchar(30) NOT NULL,
  `facilityid` int(11) DEFAULT NULL,
  `hubid` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hubid` int(11) unsigned DEFAULT NULL,
  `facilityid` int(11) unsigned DEFAULT '0',
  `ref_lab` int(11) unsigned DEFAULT '0',
  `healthregionid` int(11) unsigned DEFAULT NULL,
  `isactive` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `staff_id` int(11) unsigned DEFAULT NULL,
  `organisation_id` int(11) unsigned DEFAULT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `idp_key` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `vl_samples`;
CREATE TABLE `vl_samples` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_unique_id` varchar(128) DEFAULT NULL,
  `locator_category` varchar(1) DEFAULT NULL,
  `locator_position` varchar(4) DEFAULT NULL,
  `vl_sample_id` varchar(128) DEFAULT NULL,
  `form_number` varchar(64) DEFAULT NULL,
  `pregnant` varchar(1) DEFAULT NULL,
  `anc_number` varchar(64) DEFAULT NULL,
  `breast_feeding` varchar(1) DEFAULT NULL,
  `consented_sample_keeping` varchar(1) DEFAULT NULL,
  `active_tb_status` varchar(1) DEFAULT NULL,
  `date_collected` date DEFAULT NULL,
  `date_received` datetime DEFAULT NULL,
  `treatment_initiation_date` date DEFAULT NULL,
  `sample_type` varchar(1) DEFAULT NULL,
  `treatment_indication_other` varchar(64) DEFAULT NULL,
  `last_test_date` date DEFAULT NULL,
  `last_value` varchar(64) DEFAULT NULL,
  `last_sample_type` varchar(1) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '1',
  `in_worksheet` tinyint(1) DEFAULT NULL,
  `data_entered_by_id` int(11) DEFAULT NULL,
  `data_entered_at` datetime(6) DEFAULT NULL,
  `created_at` datetime(6) NOT NULL,
  `updated_at` datetime(6) NOT NULL,
  `arv_adherence_id` int(11) DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  `current_regimen_id` int(11) DEFAULT NULL,
  `facility_id` int(11) DEFAULT NULL,
  `data_facility_id` int(11) DEFAULT NULL,
  `facility_patient_id` int(11) DEFAULT NULL,
  `failure_reason_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `tb_treatment_phase_id` int(11) DEFAULT NULL,
  `treatment_indication_id` int(11) DEFAULT NULL,
  `treatment_line_id` int(11) DEFAULT NULL,
  `updated_by_id` int(11) DEFAULT NULL,
  `verifier_id` int(11) DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `viral_load_testing_id` int(11) DEFAULT NULL,
  `envelope_id` int(11) DEFAULT NULL,
  `other_regimen` varchar(128) DEFAULT NULL,
  `clinician_id` int(11) DEFAULT NULL,
  `lab_tech_id` int(11) DEFAULT NULL,
  `treatment_duration` smallint(5) unsigned DEFAULT NULL,
  `treatment_care_approach` smallint(5) unsigned DEFAULT NULL,
  `current_who_stage` smallint(5) unsigned DEFAULT NULL,
  `is_study_sample` tinyint(1) NOT NULL DEFAULT '0',
  `barcode` varchar(250) DEFAULT NULL,
  `original_patient_id` int(10) unsigned DEFAULT '0',
  `barcode2` varchar(250) DEFAULT NULL,
  `barcode3` varchar(250) DEFAULT NULL,
  `barcode4` varchar(250) DEFAULT NULL,
  `barcode5` varchar(250) DEFAULT NULL,
  `sample_reception_id` int(11) DEFAULT NULL,
  `tracking_code_id` int(11) DEFAULT NULL,
  `is_data_entered` tinyint(4) NOT NULL DEFAULT '0',
  `facility_reference` varchar(128) DEFAULT NULL,
  `reception_art_number` varchar(40) DEFAULT NULL,
  `data_art_number` varchar(40) DEFAULT NULL,
  `stage` tinyint(4) DEFAULT NULL,
  `required_verification` tinyint(4) NOT NULL,
  `current_regimen_initiation_date` date DEFAULT NULL,
  `received_by_id` int(11) DEFAULT NULL,
  `hie_data_created_at` datetime DEFAULT NULL,
  `studies` int(11) DEFAULT NULL,
  `study_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2025-10-01 16:58:32
