<?php

// Load Composer's autoloader
require '../vendor/autoload.php';

// Start a session
session_start();

// Load environment variables (if using vlucas/phpdotenv)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Set error reporting level
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Include the main application file
require '../src/App.php';

// Initialize the application
$app = new App();

// Define routes
$app->router->get('/', function() {
    // Home page logic
    include '../src/views/home.php';
});

$app->router->get('/players', 'PlayerController@list');
$app->router->get('/players/profile/{id}', 'PlayerController@profile');
$app->router->get('/teams', 'TeamController@list');
$app->router->get('/teams/roster/{id}', 'TeamController@roster');
$app->router->get('/matches/results', 'MatchController@results');
$app->router->get('/matches/schedule', 'MatchController@schedule');

// Handle the request
$app->run();
