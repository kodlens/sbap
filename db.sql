/*
SQLyog Ultimate v12.14 (64 bit)
MySQL - 10.4.24-MariaDB : Database - doc_tracking
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`doc_tracking` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `doc_tracking`;

/*Table structure for table `document_tracks` */

DROP TABLE IF EXISTS `document_tracks`;

CREATE TABLE `document_tracks` (
  `document_track_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned NOT NULL,
  `route_id` bigint(20) unsigned NOT NULL,
  `route_detail_id` bigint(20) unsigned NOT NULL,
  `office_id` bigint(20) unsigned NOT NULL,
  `order_no` bigint(20) NOT NULL,
  `is_origin` tinyint(4) DEFAULT 0,
  `is_last` tinyint(4) DEFAULT 0,
  `is_forward_from` tinyint(4) DEFAULT 0,
  `is_received` tinyint(4) DEFAULT 0,
  `datetime_received` datetime DEFAULT NULL,
  `receive_remarks` text DEFAULT NULL,
  `is_process` tinyint(4) DEFAULT 0,
  `datetime_process` datetime DEFAULT NULL,
  `process_remarks` text DEFAULT NULL,
  `is_done` tinyint(4) DEFAULT 0,
  `datetime_done` datetime DEFAULT NULL,
  `done_remarks` text DEFAULT NULL,
  `is_forwarded` tinyint(4) DEFAULT 0,
  `datetime_forwarded` datetime DEFAULT NULL,
  `forward_remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`document_track_id`),
  KEY `route_id` (`route_id`),
  KEY `route_detail_id` (`route_detail_id`),
  KEY `office_id` (`office_id`),
  KEY `document_id` (`document_id`),
  CONSTRAINT `document_tracks_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `document_tracks_ibfk_2` FOREIGN KEY (`route_detail_id`) REFERENCES `route_details` (`route_detail_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `document_tracks_ibfk_3` FOREIGN KEY (`office_id`) REFERENCES `offices` (`office_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `document_tracks_ibfk_4` FOREIGN KEY (`document_id`) REFERENCES `documents` (`document_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;

/*Data for the table `document_tracks` */

insert  into `document_tracks`(`document_track_id`,`document_id`,`route_id`,`route_detail_id`,`office_id`,`order_no`,`is_origin`,`is_last`,`is_forward_from`,`is_received`,`datetime_received`,`receive_remarks`,`is_process`,`datetime_process`,`process_remarks`,`is_done`,`datetime_done`,`done_remarks`,`is_forwarded`,`datetime_forwarded`,`forward_remarks`,`created_at`,`updated_at`) values 
(32,10,1,5,11,1,1,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,1,'2023-01-09 07:08:13',NULL,'2023-01-09 07:07:50','2023-01-09 07:08:13'),
(33,10,1,1,10,2,0,0,1,1,'2023-01-09 07:08:34',NULL,1,'2023-01-09 07:08:37',NULL,0,NULL,NULL,1,'2023-01-09 07:08:39',NULL,'2023-01-09 07:07:50','2023-01-09 07:08:39'),
(34,10,1,2,2,3,0,0,1,1,'2023-01-09 07:09:25',NULL,1,'2023-01-09 07:09:30',NULL,0,NULL,NULL,1,'2023-01-09 07:09:33',NULL,'2023-01-09 07:07:50','2023-01-09 07:09:33'),
(35,10,1,3,4,4,0,1,1,1,'2023-01-09 07:09:47',NULL,1,'2023-01-09 07:09:50',NULL,1,'2023-01-09 07:09:53',NULL,1,'2023-01-09 07:09:53',NULL,'2023-01-09 07:07:50','2023-01-09 07:09:53'),
(36,11,1,5,11,1,1,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,1,'2023-03-16 23:07:09',NULL,'2023-03-16 23:04:50','2023-03-16 23:07:09'),
(37,11,1,1,10,2,0,0,1,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,'2023-03-16 23:04:50','2023-03-16 23:07:09'),
(38,11,1,2,2,3,0,0,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,'2023-03-16 23:04:50','2023-03-16 23:04:50'),
(39,11,1,3,4,4,0,1,0,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,0,NULL,NULL,'2023-03-16 23:04:50','2023-03-16 23:04:50');

/*Table structure for table `documents` */

DROP TABLE IF EXISTS `documents`;

CREATE TABLE `documents` (
  `document_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `tracking_no` varchar(255) DEFAULT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `route_id` bigint(20) unsigned NOT NULL,
  `is_done` tinyint(4) DEFAULT 0,
  `datetime_done` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`document_id`),
  KEY `office_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

/*Data for the table `documents` */

insert  into `documents`(`document_id`,`user_id`,`tracking_no`,`document_name`,`route_id`,`is_done`,`datetime_done`,`created_at`,`updated_at`) values 
(10,5,'347D347F4C4FEE8','faculty salary for january 2023',0,0,NULL,'2023-01-09 07:07:50','2023-01-09 07:07:50'),
(11,5,'F43E3538748D234','Payrol for 2023 month of january',0,0,NULL,'2023-03-16 23:04:50','2023-03-16 23:04:50');

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_resets_table',1),
(3,'2019_08_19_000000_create_failed_jobs_table',1),
(4,'2019_12_14_000001_create_personal_access_tokens_table',1);

/*Table structure for table `offices` */

DROP TABLE IF EXISTS `offices`;

CREATE TABLE `offices` (
  `office_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `office` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`office_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

/*Data for the table `offices` */

insert  into `offices`(`office_id`,`office`,`created_at`,`updated_at`) values 
(2,'ACCOUNTING','2023-01-08 10:20:15','2023-01-08 10:20:15'),
(4,'TREASURY','2023-01-08 10:20:21','2023-01-08 10:25:26'),
(5,'BUDGET OFFICE','2023-01-08 10:27:30','2023-01-08 10:27:30'),
(6,'OFFICE A',NULL,NULL),
(7,'OFFICE B',NULL,NULL),
(8,'OFFICE C',NULL,NULL),
(9,'OFFICE D',NULL,NULL),
(10,'HR OFFICE (City Hall)',NULL,NULL),
(11,'HR OFFICE (GADTC)',NULL,NULL),
(13,'OFFICE E','2023-01-09 06:55:36','2023-01-09 06:55:36');

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

/*Table structure for table `route_details` */

DROP TABLE IF EXISTS `route_details`;

CREATE TABLE `route_details` (
  `route_detail_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `route_id` bigint(20) unsigned NOT NULL,
  `order_no` int(11) NOT NULL,
  `office_id` bigint(20) unsigned NOT NULL,
  `is_origin` tinyint(4) DEFAULT 0,
  `is_last` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`route_detail_id`),
  KEY `route_id` (`route_id`),
  KEY `office_id` (`office_id`),
  CONSTRAINT `route_details_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `route_details_ibfk_2` FOREIGN KEY (`office_id`) REFERENCES `offices` (`office_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

/*Data for the table `route_details` */

insert  into `route_details`(`route_detail_id`,`route_id`,`order_no`,`office_id`,`is_origin`,`is_last`,`created_at`,`updated_at`) values 
(1,1,2,10,0,0,NULL,NULL),
(2,1,3,2,0,0,NULL,NULL),
(3,1,4,4,0,1,NULL,NULL),
(5,1,1,11,1,0,NULL,NULL),
(10,4,1,6,1,0,'2023-01-09 06:57:12','2023-01-09 06:57:12'),
(11,4,2,7,0,0,'2023-01-09 06:57:12','2023-01-09 06:57:12'),
(12,4,3,8,0,0,'2023-01-09 06:57:12','2023-01-09 06:57:12'),
(13,4,4,9,0,1,'2023-01-09 06:57:12','2023-01-09 06:57:12');

/*Table structure for table `routes` */

DROP TABLE IF EXISTS `routes`;

CREATE TABLE `routes` (
  `route_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `route_name` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`route_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Data for the table `routes` */

insert  into `routes`(`route_id`,`route_name`,`remarks`,`created_at`,`updated_at`) values 
(1,'GADTC PAYROLL',NULL,NULL,NULL),
(4,'Budget and Requisition',NULL,'2023-01-09 06:57:12','2023-01-09 06:57:12');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suffix` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sex` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`user_id`,`username`,`lname`,`fname`,`mname`,`suffix`,`sex`,`office_id`,`contact_no`,`role`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`) values 
(1,'admin','PUCOT','MARJHON','','','MALE',NULL,'09655138165','ADMINISTRATOR',NULL,'$2y$10$hS4j5DZEnJwqpFuvdpmj2./mRbBwvy1DTQyLpgqk5IAtWXSiF7ZLS',NULL,NULL,NULL),
(2,'hr','LACAPAG','ROGER',NULL,NULL,'MALE','10',NULL,'STAFF',NULL,'$2y$10$hS4j5DZEnJwqpFuvdpmj2./mRbBwvy1DTQyLpgqk5IAtWXSiF7ZLS',NULL,NULL,NULL),
(3,'acct','PADAL','HARRIET CLAIRE',NULL,NULL,'FEMALE','2',NULL,'STAFF',NULL,'$2y$10$hS4j5DZEnJwqpFuvdpmj2./mRbBwvy1DTQyLpgqk5IAtWXSiF7ZLS',NULL,NULL,NULL),
(4,'treas','ABADIA','JONETHER',NULL,NULL,'MALE','4',NULL,'STAFF',NULL,'$2y$10$hS4j5DZEnJwqpFuvdpmj2./mRbBwvy1DTQyLpgqk5IAtWXSiF7ZLS',NULL,NULL,NULL),
(5,'liason','SELATONA','ARNEL',NULL,NULL,'MALE',NULL,NULL,'LIASON',NULL,'$2y$10$hS4j5DZEnJwqpFuvdpmj2./mRbBwvy1DTQyLpgqk5IAtWXSiF7ZLS',NULL,NULL,NULL),
(6,'officeb','SELATONA','ARNEL',NULL,NULL,NULL,'7',NULL,'STAFF',NULL,'$2y$10$hS4j5DZEnJwqpFuvdpmj2./mRbBwvy1DTQyLpgqk5IAtWXSiF7ZLS',NULL,NULL,NULL),
(7,'officeb1','SELATONA','ARNEL',NULL,NULL,NULL,'13',NULL,'STAFF',NULL,'$2y$10$hS4j5DZEnJwqpFuvdpmj2./mRbBwvy1DTQyLpgqk5IAtWXSiF7ZLS',NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
