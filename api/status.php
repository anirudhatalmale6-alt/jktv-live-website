<?php
/**
 * JKTV Live - Status API
 * Returns stream and server status as JSON
 */
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

require_once __DIR__ . '/../includes/config.php';

$status = [
    'stream_online' => false,
    'viewer_count' => 0,
    'database_connected' => false,
    'timestamp' => time()
];

// Check stream status
$hls_dir = '/var/www/hls/live/';
if (is_dir($hls_dir)) {
    $files = glob($hls_dir . '*.ts');
    if ($files && count($files) > 0) {
        $latest = max(array_map('filemtime', $files));
        if (time() - $latest < 30) {
            $status['stream_online'] = true;
        }
    }
}

// Get viewer count
try {
    $pdo = getDBConnection();
    if ($pdo) {
        $status['database_connected'] = true;
        $stmt = $pdo->query("SELECT latestNum FROM livecount ORDER BY id DESC LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $status['viewer_count'] = (int)$row['latestNum'];
        }
    }
} catch (Exception $e) {
    // Silently fail
}

echo json_encode($status);
