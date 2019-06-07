<?php
require 'inc.session_cfg.php';
require '../vendor/autoload.php';

use Slx\Config\ConfigFactory;
use Slx\Database\DatabaseFactory;
use Slx\Template\TemplateFactory;
use Slx\Model\User;
use Slx\Validator\FormFieldValidator;

$config = ConfigFactory::getInstance("dotenv");
$database = DatabaseFactory::getInstance("pdo");
$twig = TemplateFactory::getInstance("twig");

include("inc.redirect_if_not_logged.php");

// Define variables and initialize with empty values
$email = "";
$password = "";
$warnings = [];
$userRow = [];
$pageData = [
    "header" => "Settings",
    "tag" => "settings",
    "submit_btn" => "Save",
    "message" => "",
];
$formFields = [];
$postedFieldKeys = [ "id", "name", "email", "password", "start_time_hour", "start_time_minute", "start_time_meridiem",
    "end_time_hour", "end_time_minute", "end_time_meridiem", "timezone" ];

$sql = "SELECT id, name FROM timezones ORDER by name ASC";
$timezones = $database->query($sql);



if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] > 0) {
    $sql = "SELECT `id`, `name`, `email`, `password`, `start_time_hour`, `start_time_minute`, `start_time_meridiem`,
                      `end_time_hour`, `end_time_minute`, `end_time_meridiem`, `timezone`
                FROM `users` WHERE `id` = ?";
    $stmt = $database->prepare($sql);
    $stmt->execute([intval($_SESSION["user_id"])]);
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $validator = new FormFieldValidator();
        foreach ($postedFieldKeys as $postedFieldKey) {
            $formFields[$postedFieldKey] = (!empty($_POST[$postedFieldKey]) ? $_POST[$postedFieldKey] : "");
        }

        // Is the user attempting to change the email?
        if (!empty($_POST["email"]) && ($_POST["email"] != $userRow["email"])) {
            // Check if the email exists already
            $sql = "SELECT `id` FROM `users` WHERE `email` = ?";
            $stmt = $database->prepare($sql);
            $stmt->execute([$_POST["email"]]);
            $userWithSameEmail = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($userWithSameEmail) {
                $warnings[] = $validator::ERROR_MESSAGE_DUPLICATED_EMAIL;
                $formFields["email"] = $userRow["email"];
            }
            if (($userRow["email"] != $formFields["email"]) && !$validator->validEmail($formFields["email"])) {
                $warnings[] = $validator::ERROR_MESSAGE_VALID_EMAIL;
            }
        }

        if ((!empty($formFields["password"])) && !$validator->validPassword($formFields["password"])) {
            $warnings[] = $validator::ERROR_MESSAGE_VALID_PASSWORD;
            $formFields["password"] = "";
        }



        if (count($warnings) == 0) {
            // Did the user changed the password
            if (!empty($formFields["password"])) {
                $hashedPassword = password_hash($formFields["password"], PASSWORD_BCRYPT, ['cost' => 12]);
            } else {
                $hashedPassword = $userRow["password"];
            }
            $sql = "UPDATE `$config->DatabaseName`.`users` SET `name` = ?, `email` = ?, `password` = ?, `start_time_hour` = ?,
                      `start_time_minute` = ?, `start_time_meridiem` = ?, `end_time_hour` = ?, `end_time_minute` = ?, `end_time_meridiem` = ?,
                       `timezone` = ? WHERE `id` = ?";

            $stmt = $database->prepare($sql);
            $stmt->execute([$formFields["name"], $formFields["email"], $hashedPassword, $formFields["start_time_hour"],
                $formFields["start_time_minute"], $formFields["start_time_meridiem"], $formFields["end_time_hour"],
                $formFields["end_time_minute"], $formFields["end_time_meridiem"], $formFields["timezone"], $userRow["id"]]);
            $_SESSION["user_email"] = $formFields["email"];
            $formFields["id"] = $userRow["id"];
        }
    } else {
        $formFields = $userRow;
    }
}

echo $twig->render('settings.html',
    [
        'email' => $email,
        'user_id' => $_SESSION["user_id"],
        'user' => $formFields,
        'timezones' => $timezones,
        'pageData' => $pageData,
        'warnings' => $warnings
    ]);