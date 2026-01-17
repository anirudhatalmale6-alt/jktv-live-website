<?php
/**
 * JKTV Live - Viewer Count API
 * Returns current viewer count as JSON
 */
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

require_once __DIR__ . '/../includes/config.php';

$count = 0;
$min = 100;
$max = 500;

try {
    $pdo = getDBConnection();
    if ($pdo) {
        $stmt = $pdo->query("SELECT mincount, maxcount, latestNum FROM livecount ORDER BY id DESC LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $min = (int)$row['mincount'];
            $max = (int)$row['maxcount'];
            // Generate new random count
            $count = rand($min, $max);

            // Update the latest count
            $pdo->exec("UPDATE livecount SET latestNum = $count ORDER BY id DESC LIMIT 1");
        }
    }
} catch (Exception $e) {
    // Silently fail, return default
    $count = rand($min, $max);
}

echo json_encode([
    'count' => number_format($count),
    'raw' => $count,
    'timestamp' => time()
]);
