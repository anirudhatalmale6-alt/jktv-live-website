<?php
/**
 * JKTV Live - Support Us Page
 */
$page_title = 'Support Us';
require_once 'includes/header.php';
?>

<div class="page-header">
    <h1 class="page-title">Support JKTV Live</h1>
    <p class="page-subtitle">Help us continue bringing you quality content</p>
</div>

<section class="section">
    <div class="card text-center" style="max-width: 800px; margin: 0 auto;">
        <h2 class="card-title mb-2">Why Support Us?</h2>
        <p class="card-text">
            Your support helps us maintain our streaming infrastructure, improve video quality,
            and continue providing free content to viewers around the world. Every contribution
            makes a difference!
        </p>
    </div>
</section>

<section class="section">
    <h2 class="section-title text-center">Ways to Support</h2>
    <div class="support-options">
        <?php if (!empty(DONATION_URL)): ?>
        <div class="card support-card">
            <div class="support-icon">&#128176;</div>
            <h3 class="card-title">Donate</h3>
            <p class="card-text mb-2">Make a one-time or recurring donation to support our work.</p>
            <a href="<?php echo htmlspecialchars(DONATION_URL); ?>" class="btn btn-primary btn-block" target="_blank" rel="noopener">
                Donate Now
            </a>
        </div>
        <?php else: ?>
        <div class="card support-card">
            <div class="support-icon">&#128176;</div>
            <h3 class="card-title">Donate</h3>
            <p class="card-text mb-2">Donation options coming soon!</p>
            <button class="btn btn-secondary btn-block" disabled>Coming Soon</button>
        </div>
        <?php endif; ?>

        <div class="card support-card">
            <div class="support-icon">&#128227;</div>
            <h3 class="card-title">Spread the Word</h3>
            <p class="card-text mb-2">Share JKTV Live with your friends and family.</p>
            <button class="btn btn-secondary btn-block" onclick="shareStream()">Share Now</button>
        </div>

        <div class="card support-card">
            <div class="support-icon">&#128172;</div>
            <h3 class="card-title">Engage With Us</h3>
            <p class="card-text mb-2">Follow us on social media and join the conversation.</p>
            <a href="/contact.php" class="btn btn-secondary btn-block">Get in Touch</a>
        </div>
    </div>
</section>

<section class="section text-center">
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <h3 class="card-title">Thank You!</h3>
        <p class="card-text">
            We appreciate every viewer who tunes in. Your viewership alone is a form of support
            that keeps us motivated to deliver great content.
        </p>
    </div>
</section>

<script>
function shareStream() {
    const shareData = {
        title: '<?php echo SITE_NAME; ?>',
        text: '<?php echo SITE_TAGLINE; ?>',
        url: '<?php echo SITE_URL; ?>'
    };

    if (navigator.share) {
        navigator.share(shareData);
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText('<?php echo SITE_URL; ?>').then(() => {
            alert('Link copied to clipboard!');
        });
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>
