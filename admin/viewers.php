<?php
/**
 * JKTV Live - Viewer Count Control
 * Manage fake/simulated viewer counts
 */
require_once __DIR__ . '/../includes/config.php';
requireLogin();

$success = '';
$error = '';
$current_min = 100;
$current_max = 500;
$current_count = 0;

// Get current values from database
try {
    $pdo = getDBConnection();
    if ($pdo) {
        $stmt = $pdo->query("SELECT mincount, maxcount, latestNum FROM livecount ORDER BY id DESC LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $current_min = $row['mincount'];
            $current_max = $row['maxcount'];
            $current_count = $row['latestNum'];
        }
    }
} catch (Exception $e) {
    $error = 'Database connection failed: ' . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    $min = intval($_POST['min_count'] ?? 100);
    $max = intval($_POST['max_count'] ?? 500);

    if ($min < 0 || $max < 0) {
        $error = 'Values must be positive numbers.';
    } elseif ($min > $max) {
        $error = 'Minimum cannot be greater than maximum.';
    } else {
        try {
            // Generate a random number in range
            $new_count = rand($min, $max);

            // Update or insert
            $stmt = $pdo->prepare("INSERT INTO livecount (mincount, maxcount, latestNum) VALUES (?, ?, ?)
                                   ON DUPLICATE KEY UPDATE mincount = ?, maxcount = ?, latestNum = ?");
            $stmt->execute([$min, $max, $new_count, $min, $max, $new_count]);

            $current_min = $min;
            $current_max = $max;
            $current_count = $new_count;
            $success = 'Viewer count settings updated! Current count: ' . number_format($new_count);
        } catch (Exception $e) {
            $error = 'Failed to update: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viewer Count Control - <?php echo SITE_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/admin/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <?php include 'sidebar.php'; ?>

        <main class="admin-main">
            <div class="page-header-admin">
                <h1>Viewer Count Control</h1>
                <p>Set the range for simulated viewer counts</p>
            </div>

            <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <!-- Current Count Display -->
            <div class="dashboard-stats" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 2rem;">
                <div class="stat-card">
                    <div class="stat-icon">&#128101;</div>
                    <div class="stat-content">
                        <h3>Current Viewers</h3>
                        <p class="stat-value" id="currentCount"><?php echo number_format($current_count); ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">&#128317;</div>
                    <div class="stat-content">
                        <h3>Minimum</h3>
                        <p class="stat-value"><?php echo number_format($current_min); ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">&#128316;</div>
                    <div class="stat-content">
                        <h3>Maximum</h3>
                        <p class="stat-value"><?php echo number_format($current_max); ?></p>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 class="card-title mb-2">Set Viewer Range</h3>
                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="min_count" class="form-label">Minimum Viewers</label>
                        <input type="number" id="min_count" name="min_count" class="form-control"
                               value="<?php echo $current_min; ?>" min="0" max="999999">
                        <p class="form-help">The lowest number of viewers that will be displayed</p>
                    </div>

                    <div class="form-group">
                        <label for="max_count" class="form-label">Maximum Viewers</label>
                        <input type="number" id="max_count" name="max_count" class="form-control"
                               value="<?php echo $current_max; ?>" min="0" max="999999">
                        <p class="form-help">The highest number of viewers that will be displayed</p>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Settings</button>
                </form>
            </div>

            <section class="dashboard-section mt-3">
                <h2 class="section-title">How It Works</h2>
                <div class="card">
                    <p class="card-text">
                        The viewer count displayed on the website randomly fluctuates between your minimum and maximum values.
                        This happens automatically every 5 seconds to create a realistic viewing experience.
                    </p>
                    <p class="card-text mt-2">
                        <strong>Tip:</strong> For a more realistic look, set a reasonable range. For example:
                        <br>- Small stream: 50-200 viewers
                        <br>- Medium stream: 200-1,000 viewers
                        <br>- Large stream: 1,000-5,000 viewers
                    </p>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Update counter every 5 seconds to match the website
        setInterval(function() {
            const min = <?php echo $current_min; ?>;
            const max = <?php echo $current_max; ?>;
            const count = Math.floor(Math.random() * (max - min + 1)) + min;
            document.getElementById('currentCount').textContent = count.toLocaleString();
        }, 5000);
    </script>
</body>
</html>
