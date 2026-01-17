<?php
/**
 * JKTV Live - Home Page
 * Main landing page with live stream player
 */
$page_title = 'Live Stream';
require_once 'includes/header.php';

// Get stream info if exists
$stream_info = [];
$stream_info_file = __DIR__ . '/stream_info.json';
if (file_exists($stream_info_file)) {
    $stream_info = json_decode(file_get_contents($stream_info_file), true) ?? [];
}

$stream_title = $stream_info['title'] ?? 'Live Stream';
$stream_description = $stream_info['description'] ?? 'Watch our live broadcast';

// Get viewer count from database
$viewer_count = '0';
try {
    $pdo = getDBConnection();
    if ($pdo) {
        $stmt = $pdo->query("SELECT latestNum FROM livecount ORDER BY id DESC LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $viewer_count = number_format($row['latestNum']);
        }
    }
} catch (Exception $e) {
    // Silently fail
}
?>

<!-- Hero Section with Player -->
<section class="hero">
    <div class="player-container">
        <div class="player-wrapper">
            <video id="live-player" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" poster="/assets/images/poster.jpg">
                <source src="<?php echo htmlspecialchars(CDN_STREAM_URL ?: STREAM_URL); ?>" type="application/x-mpegURL">
                <p class="vjs-no-js">
                    To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video.
                </p>
            </video>
        </div>
        <div class="stream-info">
            <h1 class="stream-title" id="streamTitle"><?php echo htmlspecialchars($stream_title); ?></h1>
            <div class="stream-meta">
                <span class="viewer-count"><span id="viewerCount"><?php echo $viewer_count; ?></span> watching</span>
                <span id="streamStatus">Live</span>
            </div>
        </div>
    </div>
</section>

<!-- Quick Links Section -->
<section class="section">
    <div class="grid grid-3">
        <a href="/about.php" class="card" style="text-decoration: none;">
            <h3 class="card-title">About Us</h3>
            <p class="card-text">Learn more about JKTV Live and our mission.</p>
        </a>
        <a href="/vod.php" class="card" style="text-decoration: none;">
            <h3 class="card-title">VOD Library</h3>
            <p class="card-text">Watch our past broadcasts and highlights.</p>
        </a>
        <a href="/support.php" class="card" style="text-decoration: none;">
            <h3 class="card-title">Support Us</h3>
            <p class="card-text">Help us continue broadcasting quality content.</p>
        </a>
    </div>
</section>

<!-- Video.js Library -->
<link href="https://vjs.zencdn.net/8.6.1/video-js.css" rel="stylesheet">
<script src="https://vjs.zencdn.net/8.6.1/video.min.js"></script>

<script>
// Initialize Video.js player
var player = videojs('live-player', {
    liveui: true,
    html5: {
        vhs: {
            overrideNative: true
        },
        nativeAudioTracks: false,
        nativeVideoTracks: false
    },
    controls: true,
    autoplay: 'muted',
    preload: 'auto',
    fluid: true,
    responsive: true
});

// Handle errors gracefully
player.on('error', function() {
    document.getElementById('streamStatus').textContent = 'Offline';
});

player.on('playing', function() {
    document.getElementById('streamStatus').textContent = 'Live';
});

// Update viewer count periodically
function updateViewerCount() {
    fetch('/api/viewer_count.php')
        .then(response => response.json())
        .then(data => {
            if (data.count) {
                document.getElementById('viewerCount').textContent = data.count;
            }
        })
        .catch(() => {});
}

// Update every 5 seconds
setInterval(updateViewerCount, 5000);

// Update stream info periodically
function updateStreamInfo() {
    fetch('/stream_info.json?t=' + Date.now())
        .then(response => response.json())
        .then(data => {
            if (data.title) {
                document.getElementById('streamTitle').textContent = data.title;
            }
        })
        .catch(() => {});
}

// Update every 30 seconds
setInterval(updateStreamInfo, 30000);
</script>

<?php require_once 'includes/footer.php'; ?>
