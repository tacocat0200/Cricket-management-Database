<?php
// src/views/templates/header.php

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Your Website Title'; ?></title>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/bootstrap.min.css"> <!-- Example Bootstrap -->
    <script src="/js/scripts.js" defer></script> <!-- Example script -->
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="/index.php">Your Website Logo</a></h1>
            <nav>
                <ul>
                    <li><a href="/index.php">Home</a></li>
                    <li><a href="/teams.php">Teams</a></li>
                    <li><a href="/players.php">Players</a></li>
                    <li><a href="/about.php">About</a></li>
                    <li><a href="/contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <li><a href="/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
