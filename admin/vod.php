<?php
/**
 * JKTV Live - VOD Manager
 * Manage Video on Demand content
 */
require_once __DIR__ . '/../includes/config.php';
requireLogin();

$success = '';
$error = '';

// Ensure data directory exists
$data_dir = __DIR__ . '/../data';
if (!is_dir($data_dir)) {
    mkdir($data_dir, 0755, true);
}

$vod_file = $data_dir . '/vod.json';
$vod_items = [];
if (file_exists($vod_file)) {
    $vod_items = json_decode(file_get_contents($vod_file), true) ?? [];
}

// Handle add video
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $new_video = [
            'id' => uniqid(),
            'title' => trim($_POST['title'] ?? ''),
            'url' => trim($_POST['url'] ?? ''),
            'thumbnail' => trim($_POST['thumbnail'] ?? ''),
            'duration' => trim($_POST['duration'] ?? ''),
            'date' => date('Y-m-d'),
            'views' => 0
        ];

        if (empty($new_video['title']) || empty($new_video['url'])) {
            $error = 'Title and URL are required.';
        } else {
            $vod_items[] = $new_video;
            file_put_contents($vod_file, json_encode($vod_items, JSON_PRETTY_PRINT));
            $success = 'Video added successfully!';
        }
    } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
        $vod_items = array_filter($vod_items, function($v) {
            return $v['id'] !== $_POST['id'];
        });
        $vod_items = array_values($vod_items);
        file_put_contents($vod_file, json_encode($vod_items, JSON_PRETTY_PRINT));
        $success = 'Video deleted.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOD Manager - <?php echo SITE_NAME; ?></title>
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
                <h1>VOD Manager</h1>
                <p>Manage your video on demand library</p>
            </div>

            <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="card mb-3">
                <h3 class="card-title mb-2">Add New Video</h3>
                <form method="POST" class="admin-form">
                    <input type="hidden" name="action" value="add">

                    <div class="form-group">
                        <label for="title" class="form-label">Video Title *</label>
                        <input type="text" id="title" name="title" class="form-control"
                               placeholder="e.g., Morning News - January 17" required maxlength="200">
                    </div>

                    <div class="form-group">
                        <label for="url" class="form-label">Video URL *</label>
                        <input type="url" id="url" name="url" class="form-control"
                               placeholder="https://example.com/video.mp4" required>
                        <p class="form-help">Direct link to video file or embed URL</p>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail" class="form-label">Thumbnail URL</label>
                        <input type="url" id="thumbnail" name="thumbnail" class="form-control"
                               placeholder="https://example.com/thumb.jpg">
                    </div>

                    <div class="form-group">
                        <label for="duration" class="form-label">Duration</label>
                        <input type="text" id="duration" name="duration" class="form-control"
                               placeholder="e.g., 45:30" maxlength="20">
                    </div>

                    <button type="submit" class="btn btn-primary">Add Video</button>
                </form>
            </div>

            <section class="dashboard-section">
                <h2 class="section-title">Video Library (<?php echo count($vod_items); ?> videos)</h2>

                <?php if (empty($vod_items)): ?>
                <div class="card">
                    <div class="empty-state">
                        <div class="empty-icon">&#127909;</div>
                        <p>No videos in the library yet</p>
                        <p class="text-muted">Add your first video using the form above</p>
                    </div>
                </div>
                <?php else: ?>
                <div class="card" style="padding: 0; overflow: hidden;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Thumbnail</th>
                                <th>Title</th>
                                <th>Duration</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vod_items as $video): ?>
                            <tr>
                                <td style="width: 100px;">
                                    <?php if (!empty($video['thumbnail'])): ?>
                                    <img src="<?php echo htmlspecialchars($video['thumbnail']); ?>" alt="" style="width: 80px; height: 45px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                    <div style="width: 80px; height: 45px; background: var(--background-dark); border-radius: 4px; display: flex; align-items: center; justify-content: center;">&#127909;</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo htmlspecialchars($video['url']); ?>" target="_blank"><?php echo htmlspecialchars($video['title']); ?></a>
                                </td>
                                <td><?php echo htmlspecialchars($video['duration'] ?? '-'); ?></td>
                                <td><?php echo isset($video['date']) ? date('M j, Y', strtotime($video['date'])) : '-'; ?></td>
                                <td>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this video?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($video['id']); ?>">
                                        <button type="submit" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; color: var(--error-color);">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>
