<?php

/**
 * Sanitize input data to prevent XSS attacks
 *
 * @param string $data
 * @return string
 */
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Format a date for display
 *
 * @param string $date
 * @return string
 */
function formatDate($date) {
    return date("F j, Y", strtotime($date));
}

/**
 * Generate a random string for unique identifiers
 *
 * @param int $length
 * @return string
 */
function generateRandomString($length = 10) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Redirect to a specific URL
 *
 * @param string $url
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Flash a message to the session
 *
 * @param string $message
 */
function flashMessage($message) {
    if (!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }
    $_SESSION['flash_messages'][] = $message;
}

/**
 * Get flash messages from the session
 *
 * @return array
 */
function getFlashMessages() {
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    return $messages;
}

/**
 * Check if the request method is POST
 *
 * @return bool
 */
function isPostRequest() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}
