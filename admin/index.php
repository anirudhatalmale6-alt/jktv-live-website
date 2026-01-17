<?php
/**
 * JKTV Live - Admin Dashboard
 * Unified admin panel for all management tasks
 */
require_once __DIR__ . '/../includes/config.php';
requireLogin();

// Get stream status
$stream_online = false;
$viewer_count = 0;
try {
    $pdo = getDBConnection();
    if ($pdo) {
        $stmt = $pdo->query("SELECT latestNum FROM livecount ORDER BY id DESC LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $viewer_count = $row['latestNum'];
        }
    }
} catch (Exception $e) {}

// Check if stream is active by checking HLS directory
$hls_dir = '/var/www/hls/live/';
if (is_dir($hls_dir)) {
    $files = glob($hls_dir . '*.ts');
    if ($files && count($files) > 0) {
        // Check if files are recent (within last 30 seconds)
        $latest = max(array_map('filemtime', $files));
        if (time() - $latest < 30) {
            $stream_online = true;
        }
    }
}

// Get stream info
$stream_info = ['title' => 'Live Stream', 'description' => ''];
$stream_info_file = __DIR__ . '/../stream_info.json';
if (file_exists($stream_info_file)) {
    $stream_info = array_merge($stream_info, json_decode(file_get_contents($stream_info_file), true) ?? []);
}

// Get contact form submissions count
$contacts_count = 0;
$contacts_file = __DIR__ . '/../data/contacts.json';
if (file_exists($contacts_file)) {
    $contacts = json_decode(file_get_contents($contacts_file), true) ?? [];
    $contacts_count = count($contacts);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/admin/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2><?php echo SITE_NAME; ?></h2>
                <span class="admin-badge">Admin</span>
            </div>
            <nav class="sidebar-nav">
                <a href="/admin/" class="nav-item active">
                    <span class="nav-icon">&#128200;</span> Dashboard
                </a>
                <a href="/admin/stream.php" class="nav-item">
                    <span class="nav-icon">&#128250;</span> Stream Control
                </a>
                <a href="/admin/viewers.php" class="nav-item">
                    <span class="nav-icon">&#128101;</span> Viewer Count
                </a>
                <a href="/admin/push.php" class="nav-item">
                    <span class="nav-icon">&#128276;</span> Push Notifications
                </a>
                <a href="/admin/vod.php" class="nav-item">
                    <span class="nav-icon">&#127909;</span> VOD Manager
                </a>
                <a href="/admin/contacts.php" class="nav-item">
                    <span class="nav-icon">&#128233;</span> Contact Messages
                    <?php if ($contacts_count > 0): ?>
                    <span class="badge"><?php echo $contacts_count; ?></span>
                    <?php endif; ?>
                </a>
                <a href="/admin/settings.php" class="nav-item">
                    <span class="nav-icon">&#9881;</span> Settings
                </a>
                <hr class="nav-divider">
                <a href="/admin/quick-links.php" class="nav-item">
                    <span class="nav-icon">&#128279;</span> Quick Links
                </a>
                <a href="/" class="nav-item" target="_blank">
                    <span class="nav-icon">&#127760;</span> View Website
                </a>
                <a href="/admin/logout.php" class="nav-item nav-logout">
                    <span class="nav-icon">&#128682;</span> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <h1>Dashboard</h1>
                <span class="header-time"><?php echo date('M j, Y - g:i A'); ?></span>
            </header>

            <!-- Status Cards -->
            <div class="dashboard-stats">
                <div class="stat-card <?php echo $stream_online ? 'stat-success' : 'stat-warning'; ?>">
                    <div class="stat-icon">&#128250;</div>
                    <div class="stat-content">
                        <h3>Stream Status</h3>
                        <p class="stat-value"><?php echo $stream_online ? 'Online' : 'Offline'; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">&#128101;</div>
                    <div class="stat-content">
                        <h3>Current Viewers</h3>
                        <p class="stat-value"><?php echo number_format($viewer_count); ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">&#128233;</div>
                    <div class="stat-content">
                        <h3>Messages</h3>
                        <p class="stat-value"><?php echo $contacts_count; ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">&#127909;</div>
                    <div class="stat-content">
                        <h3>VOD Videos</h3>
                        <p class="stat-value">0</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <section class="dashboard-section">
                <h2 class="section-title">Quick Actions</h2>
                <div class="quick-actions">
                    <a href="/admin/stream.php" class="action-card">
                        <span class="action-icon">&#128250;</span>
                        <span>Update Stream Info</span>
                    </a>
                    <a href="/admin/viewers.php" class="action-card">
                        <span class="action-icon">&#128101;</span>
                        <span>Set Viewer Count</span>
                    </a>
                    <a href="/admin/push.php" class="action-card">
                        <span class="action-icon">&#128276;</span>
                        <span>Send Push Notification</span>
                    </a>
                    <a href="/stat" class="action-card" target="_blank">
                        <span class="action-icon">&#128202;</span>
                        <span>RTMP Statistics</span>
                    </a>
                </div>
            </section>

            <!-- Stream Info Preview -->
            <section class="dashboard-section">
                <h2 class="section-title">Current Stream Info</h2>
                <div class="card">
                    <p><strong>Title:</strong> <?php echo htmlspecialchars($stream_info['title']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($stream_info['description'] ?? 'Not set'); ?></p>
                    <a href="/admin/stream.php" class="btn btn-secondary mt-2">Edit Stream Info</a>
                </div>
            </section>

            <!-- Server Info -->
            <section class="dashboard-section">
                <h2 class="section-title">Server Information</h2>
                <div class="card">
                    <table class="info-table">
                        <tr>
                            <td><strong>RTMP Server</strong></td>
                            <td><code><?php echo RTMP_SERVER; ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>RTMPS Server</strong></td>
                            <td><code><?php echo RTMPS_SERVER; ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Stream Key</strong></td>
                            <td><code><?php echo DEFAULT_STREAM_KEY; ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>HLS URL</strong></td>
                            <td><code><?php echo STREAM_URL; ?></code></td>
                        </tr>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Auto-refresh status every 10 seconds
        setInterval(function() {
            fetch('/api/status.php')
                .then(r => r.json())
                .then(data => {
                    // Update status cards if needed
                })
                .catch(() => {});
        }, 10000);
    </script>
</body>
</html>
