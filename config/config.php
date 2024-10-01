<?php

// Database configuration
define('DB_HOST', 'localhost');      // Database host
define('DB_NAME', 'cricket_db');      // Database name
define('DB_USER', 'root');            // Database username
define('DB_PASS', 'your_password');    // Database password

// Application configuration
define('APP_NAME', 'Cricket DBMS');   // Application name
define('APP_URL', 'http://localhost/cricket_dbms'); // Base URL of the application

// Error reporting settings
define('DEBUG_MODE', true); // Set to true for development, false for production
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Session configuration
session_start(); // Start the session

// Include additional configuration files if needed
// require_once 'additional_config.php';
