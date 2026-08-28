<?php
/**
 * ============================================================================
 * SESSION CONFIGURATION & MANAGEMENT
 * ============================================================================
 * Purpose: Secure session handling for authentication
 * ============================================================================
 */

// Set secure session configuration
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Set to 1 in production with HTTPS
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.gc_maxlifetime', 3600); // 1 hour

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Function to check if user is admin
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['admin_id']);
}

// Function to get current admin ID
function getAdminId() {
    return isLoggedIn() ? $_SESSION['admin_id'] : null;
}

// Function to get current admin username
function getAdminUsername() {
    return isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : null;
}

// Function to set admin session
function setAdminSession($admin_id, $username, $email) {
    $_SESSION['admin_id'] = $admin_id;
    $_SESSION['admin_username'] = $username;
    $_SESSION['admin_email'] = $email;
    $_SESSION['login_time'] = time();
}

// Function to destroy session (logout)
function destroySession() {
    session_destroy();
    unset($_SESSION);
}

// Function to check session timeout (1 hour)
function checkSessionTimeout() {
    $timeout = 3600; // 1 hour
    
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $timeout) {
        destroySession();
        return false;
    }
    
    // Refresh login time
    $_SESSION['login_time'] = time();
    return true;
}

// Regenerate session ID for security
function regenerateSessionId() {
    if (!isset($_SESSION['_SESSION_REGENERATED'])) {
        session_regenerate_id(true);
        $_SESSION['_SESSION_REGENERATED'] = true;
    }
}

?>