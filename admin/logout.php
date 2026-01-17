<?php
/**
 * JKTV Live - Admin Logout
 */
require_once __DIR__ . '/../includes/config.php';

// Destroy session
$_SESSION = [];
session_destroy();

// Redirect to login
header('Location: /admin/login.php');
exit;
