<?php
// Start the session
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    // If not, display the login form
    include 'admin_login.php';
} else {
    // If the user is logged in, display the admin dashboard
    include 'admin_dashboard.php';
}
?>