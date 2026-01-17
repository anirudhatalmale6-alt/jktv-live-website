<?php
/**
 * JKTV Live - Header Template
 * Included at the top of every page
 */
require_once __DIR__ . '/config.php';

// Get current page for active nav highlighting
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="<?php echo SITE_TAGLINE; ?>">
    <meta name="theme-color" content="#e63946">

    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL; ?>">
    <meta property="og:title" content="<?php echo SITE_NAME; ?>">
    <meta property="og:description" content="<?php echo SITE_TAGLINE; ?>">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/assets/images/og-image.jpg">

    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME; ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/images/favicon.png">
</head>
<body>
    <header class="header">
        <nav class="nav-container">
            <a href="/" class="logo">
                <?php
                $logo_png = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/logo.png';
                $logo_jpg = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/logo.jpg';
                if (file_exists($logo_png)): ?>
                    <img src="/assets/images/logo.png" alt="<?php echo SITE_NAME; ?>">
                <?php elseif (file_exists($logo_jpg)): ?>
                    <img src="/assets/images/logo.jpg" alt="<?php echo SITE_NAME; ?>">
                <?php else: ?>
                    <?php echo SITE_NAME; ?>
                <?php endif; ?>
                <span class="live-badge">LIVE</span>
            </a>

            <button class="nav-toggle" aria-label="Toggle navigation" onclick="toggleNav()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>

            <ul class="nav-menu" id="navMenu">
                <li><a href="/" class="<?php echo $current_page === 'index' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="/about.php" class="<?php echo $current_page === 'about' ? 'active' : ''; ?>">About Us</a></li>
                <li><a href="/vod.php" class="<?php echo $current_page === 'vod' ? 'active' : ''; ?>">VOD</a></li>
                <li><a href="/support.php" class="<?php echo $current_page === 'support' ? 'active' : ''; ?>">Support Us</a></li>
                <li><a href="/contact.php" class="<?php echo $current_page === 'contact' ? 'active' : ''; ?>">Contact</a></li>
            </ul>
        </nav>
    </header>

    <main>
