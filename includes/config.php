<?php
/**
 * JKTV Live - Configuration File
 *
 * Edit this file to configure your website settings.
 * This is the SINGLE POINT for all configuration.
 */

// =============================================================================
// SITE SETTINGS
// =============================================================================
define('SITE_NAME', 'JKTV Live');
define('SITE_TAGLINE', 'Live Streaming 24/7');
define('SITE_URL', 'https://jktv.live');

// =============================================================================
// CONTACT FORM EMAIL - Change this to your email
// =============================================================================
define('CONTACT_EMAIL', 'contact@jktv.live');

// =============================================================================
// ADMIN CREDENTIALS - Change these for security!
// =============================================================================
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', '123456');

// =============================================================================
// STREAM SETTINGS
// =============================================================================
define('STREAM_URL', 'https://jktv.live/hls/live/index.m3u8');
define('CDN_STREAM_URL', 'https://jktv.b-cdn.net/hls/live/index.m3u8');
define('RTMP_SERVER', 'rtmp://jktv.live:1935/live');
define('RTMPS_SERVER', 'rtmps://jktv.live:1936/live');
define('DEFAULT_STREAM_KEY', 'live?secret=StreamSecret2024');

// =============================================================================
// DATABASE (for viewer count)
// =============================================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'jktv_live');
define('DB_USER', 'jktv_live');
define('DB_PASS', 'Letmein2020');

// =============================================================================
// PUSH NOTIFICATION SETTINGS (Firebase)
// =============================================================================
define('FIREBASE_SERVER_KEY', ''); // Add your Firebase server key
define('FIREBASE_SENDER_ID', '');  // Add your Firebase sender ID

// =============================================================================
// DONATION/SUPPORT SETTINGS
// =============================================================================
define('DONATION_URL', ''); // e.g., 'https://paypal.me/yourusername'
define('SUPPORT_TEXT', 'Support our stream!');

// =============================================================================
// SOCIAL MEDIA LINKS
// =============================================================================
$social_links = [
    'facebook' => '',
    'twitter' => '',
    'instagram' => '',
    'youtube' => '',
    'telegram' => '',
];

// =============================================================================
// ABOUT US CONTENT
// =============================================================================
define('ABOUT_TITLE', 'About JKTV Live');
define('ABOUT_TEXT', 'JKTV Live is your destination for quality live streaming content. We broadcast 24/7, bringing you the best entertainment and news.');

// =============================================================================
// DATABASE CONNECTION FUNCTION
// =============================================================================
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        return $pdo;
    } catch (PDOException $e) {
        return null;
    }
}

// =============================================================================
// HELPER FUNCTIONS
// =============================================================================
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
