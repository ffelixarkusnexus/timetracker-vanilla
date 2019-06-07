<?php
require '../vendor/autoload.php';

use Slixa\Database\DatabaseFactory;

$database = DatabaseFactory::getInstance("pdo");
$databaseName = "timetracker_vanilla";

$hashedPassword = password_hash("secret!ong", PASSWORD_BCRYPT, ['cost' => 12]);
$database->query("INSERT INTO `$databaseName`.`users` (`name`, `email`, `password`, `start_time_hour`,
          `start_time_minute`, `start_time_meridiem`, `end_time_hour`, `end_time_minute`, `end_time_meridiem`, `timezone`, 
          `created_at`, `updated_at`) VALUES ('Francisco Felix', 'ffelix@desveladisimo.com', '$hashedPassword', 
          5, 10, 'AM', 2, 20, 'PM', 192, NOW(), NOW())");

// Get the list of supported timezones.
$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

// Build the string to insert multiple rows at once (for performance)
$values = "";
foreach ($tzlist as $index => $timezone) {
    $values .= "('$timezone'),";
}
$values = rtrim($values, ',');

// Insert the data into the timezones table.
$database->query("INSERT INTO `$databaseName`.`timezones` (`name`) VALUES $values");
