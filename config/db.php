<?php
/**
 * ============================================================================
 * DATABASE CONFIGURATION & CONNECTION
 * ============================================================================
 * Purpose: Centralized database configuration using PDO with error handling
 * Database: MySQL (XAMPP/Localhost compatible)
 * ============================================================================
 */

// Database configuration constants
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tour_travel_system');
define('DB_PORT', 3306);

// Charset
define('DB_CHARSET', 'utf8mb4');

// PDO Options for secure connection
$pdo_options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
);

// Establish PDO connection
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS,
        $pdo_options
    );
    
    // Connection successful - set timezone
    $pdo->exec("SET time_zone='+00:00'");
    
} catch (PDOException $e) {
    // Log error to file instead of displaying
    error_log('Database Connection Error: ' . $e->getMessage(), 3, '../logs/db_error.log');
    
    // Display user-friendly error
    header('HTTP/1.1 503 Service Unavailable');
    echo "<h1>Service Temporarily Unavailable</h1>";
    echo "<p>We are experiencing database connection issues. Please try again later.</p>";
    exit();
}

// Function to prepare and execute queries safely
function executeQuery($query, $params = array()) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log('Query Error: ' . $e->getMessage(), 3, '../logs/db_error.log');
        return false;
    }
}

// Function to fetch single row
function fetchOne($query, $params = array()) {
    $stmt = executeQuery($query, $params);
    return $stmt ? $stmt->fetch() : false;
}

// Function to fetch all rows
function fetchAll($query, $params = array()) {
    $stmt = executeQuery($query, $params);
    return $stmt ? $stmt->fetchAll() : array();
}

// Function to insert/update/delete
function executeUpdate($query, $params = array()) {
    $stmt = executeQuery($query, $params);
    return $stmt ? $stmt->rowCount() : 0;
}

?>