<?php
/**
 * JKTV Live - About Us Page
 */
$page_title = 'About Us';
require_once 'includes/header.php';
?>

<div class="page-header">
    <h1 class="page-title"><?php echo htmlspecialchars(ABOUT_TITLE); ?></h1>
    <p class="page-subtitle">Learn more about who we are</p>
</div>

<section class="section">
    <div class="card">
        <div class="card-text" style="font-size: 1.1rem; line-height: 1.8;">
            <?php echo nl2br(htmlspecialchars(ABOUT_TEXT)); ?>
        </div>
    </div>
</section>

<section class="section">
    <h2 class="section-title">What We Offer</h2>
    <div class="grid grid-3">
        <div class="card text-center">
            <div class="support-icon">&#128250;</div>
            <h3 class="card-title">Live Streaming</h3>
            <p class="card-text">24/7 live broadcasts bringing you the latest content.</p>
        </div>
        <div class="card text-center">
            <div class="support-icon">&#127909;</div>
            <h3 class="card-title">Video On Demand</h3>
            <p class="card-text">Access our library of past broadcasts anytime.</p>
        </div>
        <div class="card text-center">
            <div class="support-icon">&#127760;</div>
            <h3 class="card-title">Global Access</h3>
            <p class="card-text">Watch from anywhere in the world on any device.</p>
        </div>
    </div>
</section>

<section class="section text-center">
    <h2 class="section-title">Get In Touch</h2>
    <p class="text-muted mb-2">Have questions or feedback? We'd love to hear from you.</p>
    <a href="/contact.php" class="btn btn-primary">Contact Us</a>
</section>

<?php require_once 'includes/footer.php'; ?>
