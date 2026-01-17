<?php
/**
 * JKTV Live - VOD (Video On Demand) Page
 */
$page_title = 'Video On Demand';
require_once 'includes/header.php';

// Load VOD items from JSON file (can be managed via admin)
$vod_items = [];
$vod_file = __DIR__ . '/data/vod.json';
if (file_exists($vod_file)) {
    $vod_items = json_decode(file_get_contents($vod_file), true) ?? [];
}
?>

<div class="page-header">
    <h1 class="page-title">Video On Demand</h1>
    <p class="page-subtitle">Watch our past broadcasts and highlights</p>
</div>

<section class="section">
    <?php if (empty($vod_items)): ?>
    <!-- Placeholder content when no videos -->
    <div class="card text-center" style="max-width: 600px; margin: 0 auto; padding: 3rem;">
        <div class="support-icon">&#127909;</div>
        <h2 class="card-title mt-2">Coming Soon</h2>
        <p class="card-text">
            We're working on building our video library. Check back soon for past broadcasts,
            highlights, and exclusive content!
        </p>
        <a href="/" class="btn btn-primary mt-2">Watch Live Instead</a>
    </div>
    <?php else: ?>
    <!-- VOD Grid -->
    <div class="grid grid-3">
        <?php foreach ($vod_items as $video): ?>
        <article class="vod-card">
            <a href="<?php echo htmlspecialchars($video['url'] ?? '#'); ?>">
                <div class="vod-thumbnail">
                    <?php if (!empty($video['thumbnail'])): ?>
                    <img src="<?php echo htmlspecialchars($video['thumbnail']); ?>" alt="<?php echo htmlspecialchars($video['title'] ?? 'Video'); ?>">
                    <?php endif; ?>
                    <?php if (!empty($video['duration'])): ?>
                    <span class="vod-duration"><?php echo htmlspecialchars($video['duration']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="vod-info">
                    <h3 class="vod-title"><?php echo htmlspecialchars($video['title'] ?? 'Untitled'); ?></h3>
                    <p class="vod-meta">
                        <?php if (!empty($video['date'])): ?>
                        <?php echo date('M j, Y', strtotime($video['date'])); ?>
                        <?php endif; ?>
                        <?php if (!empty($video['views'])): ?>
                        &bull; <?php echo number_format($video['views']); ?> views
                        <?php endif; ?>
                    </p>
                </div>
            </a>
        </article>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<?php require_once 'includes/footer.php'; ?>
