<?php
session_start();
require '../vendor/autoload.php';

use Slixa\Config\ConfigFactory;
use Slixa\Database\DatabaseFactory;
use Slixa\Template\TemplateFactory;
use Carbon\Carbon;

$config = ConfigFactory::getInstance("dotenv");
$database = DatabaseFactory::getInstance("pdo");
$twig = TemplateFactory::getInstance("twig");

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    echo $twig->render('index.html', ['user_id' => $_SESSION["user_id"], 'go' => 'here']);
} else {
    header("location: login.php");
}

// printf("Now: %s", Carbon::now());