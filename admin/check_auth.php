<?php
/**
 * ============================================================================
 * AUTHENTICATION CHECK - Include in all admin pages
 * ============================================================================
 */

session_start();

require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

// Check session timeout (1 hour)
$timeout = 3600;
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $timeout) {
    session_destroy();
    header('Location: login.php?timeout=1');
    exit();
}

// Refresh login time
$_SESSION['login_time'] = time();

// Function to check admin existence
function verifyAdminExists($admin_id) {
    global $pdo;
    $query = 'SELECT id FROM admin WHERE id = ? AND is_active = 1 LIMIT 1';
    $result = fetchOne($query, [$admin_id]);
    return $result ? true : false;
}

// Verify admin still exists and is active
if (!verifyAdminExists($_SESSION['admin_id'])) {
    session_destroy();
    header('Location: login.php?error=Account%20inactive');
    exit();
}

?>