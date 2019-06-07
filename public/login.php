<?php
require 'inc.session_cfg.php';
require '../vendor/autoload.php';

use Slixa\Config\ConfigFactory;
use Slixa\Database\DatabaseFactory;
use Slixa\Template\TemplateFactory;
use Slixa\Model\User;
use Slixa\Validator\FormFieldValidator;

$config = ConfigFactory::getInstance("dotenv");
$database = DatabaseFactory::getInstance("pdo");
$twig = TemplateFactory::getInstance("twig");

include("inc.redirect_if_logged.php");

// Define variables and initialize with empty values
$email = "";
$password = "";
$warnings = [];
$userRow = [];
$pageData = [   "header" => "Login",
                "tag" => "login",
                "message" => "Please fill in your credentials to login.",
];

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $validator = new FormFieldValidator();
    $email = (!empty($_POST["email"]) ? trim($_POST["email"]) : "");
    $password = (!empty($_POST["password"]) ? trim($_POST["password"]) : "");

    if (!$validator->validEmail($email)) {
        $warnings[] = $validator::ERROR_MESSAGE_VALID_EMAIL;
    }

    // Validate credentials
    if (count($warnings) == 0) {
        // Prepare a select statement
        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        $stmt = $database->prepare($sql);
        $stmt->execute([$email]);
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if username exists, if yes then verify password
        if ($userRow) {
            if (password_verify($password, $userRow["password"])) {
                // Store data in session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["user_id"] = $userRow["id"];
                $_SESSION["user_email"] = $email;
                // Redirect user to index page
                header("location: index.php");
            } else {
                $warnings[] = "Wrong credentials.";
            }
        } else {
            $warnings[] = "Email not found. Please register to get access.";
        }
    }
}

echo $twig->render('login.html', ['email' => $email, 'user_id' => $userRow["id"], 'pageData' => $pageData, 'warnings' => $warnings]);