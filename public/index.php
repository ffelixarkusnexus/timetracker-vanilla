<?php
session_start();
require '../vendor/autoload.php';

use Slx\Config\ConfigFactory;
use Slx\Database\DatabaseFactory;
use Slx\Template\TemplateFactory;
use Carbon\Carbon;

$config = ConfigFactory::getInstance("dotenv");
$database = DatabaseFactory::getInstance("pdo");
$twig = TemplateFactory::getInstance("twig");

// Connect to the database and pull the user list
$stmt = $database->query("SELECT 
    `users`.`id`,
    `users`.`name`,
    `users`.`email`,
    `users`.`password`,
    `users`.`start_time_hour`,
    `users`.`start_time_minute`,
    `users`.`start_time_meridiem`,
    `users`.`end_time_hour`,
    `users`.`end_time_minute`,
    `users`.`end_time_meridiem`,
    `users`.`timezone`,
    `timezones`.`name` as timezone_name
FROM
    `users`
        INNER JOIN
    `timezones` ON `users`.`timezone` = `timezones`.`id`
WHERE
    `users`.`timezone` IS NOT NULL");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $index => $user) {
    $hoursDiff = ($user["end_time_hour"] - $user["start_time_hour"]);
    $sameMeridiem = $user["end_time_meridiem"] == $user["start_time_meridiem"];

    if ($hoursDiff < 0) {
        if ($sameMeridiem) {
            $baseHours = 24;
        } else {
            $baseHours = 12;
        }
    } else {
        if ($sameMeridiem) {
            $baseHours = 0;
        } else {
            $baseHours = 12;
        }
    }
    $users[$index]["shift_in_hours"] = $baseHours + $hoursDiff;
    $users[$index]["formula"] = "$baseHours + $hoursDiff = " . $users[$index]["shift_in_hours"];


    date_default_timezone_set("America/Tijuana");
    $time = time();
    $users[$index]["date_guest_time_zone"] = date('Y-m-d H:i:s', $time);
    $startTimeHours = $user["start_time_hour"];
    $startTimeHours += ($user["start_time_meridiem"] == "PM") ? 12 : 0;
    $startTimeHoursPadded = sprintf("%02d", $startTimeHours);
    $startTimeMinutesPadded = sprintf("%02d", $user["start_time_minute"]);
    $startDateGuestZone = date('Y-m-d ', $time) . $startTimeHoursPadded . ":" . $startTimeMinutesPadded . ":00";


    date_default_timezone_set($user["timezone_name"]);
    $time = time();
    $users[$index]["date_user_time_zone"] = date('Y-m-d H:i:s', $time);
    $startDateUserZone = date('Y-m-d ', $time) . $startTimeHoursPadded . ":" . $startTimeMinutesPadded . ":00";
    // start and end time converted
    $users[$index]["start_date_user_time_zone"] = $startDateUserZone;
    $users[$index]["end_date_user_time_zone"] = date('Y-m-d H:i:s',strtotime('+' . $users[$index]["shift_in_hours"] .' hour',strtotime($startDateUserZone)));

}

echo $twig->render('index.html', ['user_id' => $_SESSION["user_id"], 'users' => $users]);
