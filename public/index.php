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

echo $twig->render('index.html', ['user_id' => $_SESSION["user_id"], 'go' => 'here']);

// printf("Now: %s", Carbon::now());