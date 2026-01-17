<?php
/**
 * JKTV Live - Stream Control Panel
 * Manage stream info, title, description
 */
require_once __DIR__ . '/../includes/config.php';
requireLogin();

$success = '';
$error = '';

// Load current stream info
$stream_info = ['title' => 'Live Stream', 'description' => '', 'subtitle' => ''];
$stream_info_file = __DIR__ . '/../stream_info.json';
if (file_exists($stream_info_file)) {
    $stream_info = array_merge($stream_info, json_decode(file_get_contents($stream_info_file), true) ?? []);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_info = [
        'title' => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'subtitle' => trim($_POST['subtitle'] ?? ''),
        'updated' => date('Y-m-d H:i:s')
    ];

    if (empty($new_info['title'])) {
        $error = 'Stream title is required.';
    } else {
        if (file_put_contents($stream_info_file, json_encode($new_info, JSON_PRETTY_PRINT))) {
            $stream_info = $new_info;
            $success = 'Stream info updated successfully!';
        } else {
            $error = 'Failed to save stream info. Check file permissions.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stream Control - <?php echo SITE_NAME; ?></title>
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
                <h1>Stream Control</h1>
                <p>Update stream information displayed to viewers</p>
            </div>

            <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="card">
                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="title" class="form-label">Stream Title *</label>
                        <input type="text" id="title" name="title" class="form-control"
                               value="<?php echo htmlspecialchars($stream_info['title']); ?>"
                               placeholder="e.g., Live News Broadcast" required maxlength="200">
                        <p class="form-help">The main title shown on the player and social shares</p>
                    </div>

                    <div class="form-group">
                        <label for="subtitle" class="form-label">Subtitle / Topic</label>
                        <input type="text" id="subtitle" name="subtitle" class="form-control"
                               value="<?php echo htmlspecialchars($stream_info['subtitle'] ?? ''); ?>"
                               placeholder="e.g., Today's Topic: Breaking News" maxlength="200">
                        <p class="form-help">Optional subtitle or current topic</p>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control"
                                  placeholder="Brief description of the stream..." maxlength="500"><?php echo htmlspecialchars($stream_info['description']); ?></textarea>
                        <p class="form-help">Shown when the stream is shared on social media</p>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>

            <section class="dashboard-section mt-3">
                <h2 class="section-title">OBS/Streaming Software Settings</h2>
                <div class="card">
                    <table class="info-table">
                        <tr>
                            <td><strong>RTMP Server</strong></td>
                            <td><code><?php echo RTMP_SERVER; ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>RTMPS Server (Secure)</strong></td>
                            <td><code><?php echo RTMPS_SERVER; ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Stream Key</strong></td>
                            <td>
                                <code id="streamKey"><?php echo DEFAULT_STREAM_KEY; ?></code>
                                <button type="button" class="btn btn-secondary" style="margin-left: 1rem; padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="copyKey()">Copy</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        function copyKey() {
            const key = document.getElementById('streamKey').textContent;
            navigator.clipboard.writeText(key).then(() => {
                alert('Stream key copied to clipboard!');
            });
        }
    </script>
</body>
</html>
