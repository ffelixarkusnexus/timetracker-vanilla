<?php
require '../vendor/autoload.php';

use Slixa\Database\DatabaseFactory;

/*
 * IMPORTANT: Before running this script you need to have the database and the username created. Use the following
 * MySQL statements as examples.
 * - CREATE DATABASE IF NOT EXISTS `timetracker_vanilla` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_unicode_ci;
 * - GRANT ALL PRIVILEGES ON timetracker_vanilla.* TO 'slxusername'@'localhost' IDENTIFIED BY 'slxpassword';
 */

$database = DatabaseFactory::getInstance("pdo");
$databaseName = "timetracker_vanilla";

$database->query("CREATE TABLE IF NOT EXISTS `$databaseName`.`users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_time_hour` TINYINT unsigned NULL DEFAULT NULL,
  `start_time_minute` TINYINT unsigned NULL DEFAULT NULL,
  `start_time_meridiem` ENUM('AM', 'PM') COLLATE utf8_unicode_ci DEFAULT NULL,
  `end_time_hour` TINYINT unsigned NULL DEFAULT NULL,
  `end_time_minute` TINYINT unsigned NULL DEFAULT NULL,
  `end_time_meridiem` ENUM('AM', 'PM') COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` SMALLINT unsigned NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

$database->query("CREATE TABLE `$databaseName`.`timezones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

