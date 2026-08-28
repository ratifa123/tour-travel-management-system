<?php
/**
 * ============================================================================
 * ADMIN LOGOUT
 * ============================================================================
 */

session_start();

// Destroy session
if (isset($_SESSION['admin_id'])) {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
    unset($_SESSION['admin_email']);
    unset($_SESSION['login_time']);
}

session_destroy();

// Redirect to login
header('Location: login.php?logout=1');
exit();
?>