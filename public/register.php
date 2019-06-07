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

include("inc.redirect_if_logged.php");

// Define variables and initialize with empty values
$email = "";
$password = "";
$warnings = [];
$userRow = [];
$pageData = [
    "header" => "Register",
    "tag" => "register",
    "message" => "Please provide the following information to create your account.",
];

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['token'])) {
        if (hash_equals($_SESSION['token'], $_POST['token'])) {
            $validator = new FormFieldValidator();
            $email = (!empty($_POST["email"]) ? trim($_POST["email"]) : "");
            $password = (!empty($_POST["password"]) ? trim($_POST["password"]) : "");

            if (!$validator->validEmail($email)) {
                $warnings[] = $validator::ERROR_MESSAGE_VALID_EMAIL;
            }

            // Check if the email already exists
            if (count($warnings) == 0) {
                // Prepare a select statement
                $sql = "SELECT id, email, password FROM users WHERE email = ?";
                $stmt = $database->prepare($sql);
                $stmt->execute([$email]);
                $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

                // Check if username exists, if so request to log in instead
                if (!$userRow) {
                    if ($validator->validPassword($password)) {
                        // Register the user
                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                        $database->query("INSERT INTO `$config->DatabaseName`.`users` (`email`, `password`, `created_at`, `updated_at`) VALUES ('$email', '$hashedPassword', NOW(), NOW())");
                        $sql = "SELECT id, email, password FROM users WHERE email = ?";
                        $stmt = $database->prepare($sql);
                        $stmt->execute([$email]);
                        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["user_id"] = $userRow["id"];
                        $_SESSION["user_email"] = $email;
                        header("location: index.php");
                        exit;
                    } else {
                        $warnings[] = $validator::ERROR_MESSAGE_VALID_PASSWORD;
                    }
                } else {
                    $warnings[] = "Email already registered. Please login using your credentials to get access.";
                }
            }
        } else {
            // TODO: Log attempt
        }
    }
}

echo $twig->render('login.html',
    ['email' => $email, 'token' => $token, 'user_id' => $userRow["id"], 'pageData' => $pageData, 'warnings' => $warnings]);