<?php

// Include the configuration file
require_once 'config/config.php';

/**
 * Create a new PDO database connection
 *
 * @return PDO|null
 */
function getConnection() {
    try {
        // Create a new PDO instance
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $pdo; // Return the PDO instance
    } catch (PDOException $e) {
        // Handle connection errors
        echo 'Database connection failed: ' . $e->getMessage();
        return null; // Return null if the connection fails
    }
}

/**
 * Execute a query and return the result set
 *
 * @param string $sql
 * @param array $params
 * @return PDOStatement|null
 */
function executeQuery($sql, $params = []) {
    $pdo = getConnection();
    if ($pdo) {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt; // Return the prepared statement
    }
    return null; // Return null if the connection fails
}

/**
 * Fetch all results from a query
 *
 * @param string $sql
 * @param array $params
 * @return array|null
 */
function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : null; // Fetch all results as an associative array
}

/**
 * Fetch a single result from a query
 *
 * @param string $sql
 * @param array $params
 * @return array|null
 */
function fetchOne($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null; // Fetch a single result as an associative array
}
