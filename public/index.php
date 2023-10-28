<?php
session_start();
require '../vendor/autoload.php';
require '../src/routes.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$router->run( $router->routes );