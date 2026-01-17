<?php
/**
 * JKTV Live - Push Notification Manager
 * Send push notifications to app users
 */
require_once __DIR__ . '/../includes/config.php';
requireLogin();

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $link = trim($_POST['link'] ?? '');

    if (empty($title) || empty($message)) {
        $error = 'Title and message are required.';
    } elseif (empty(FIREBASE_SERVER_KEY)) {
        $error = 'Firebase is not configured. Please add your Firebase Server Key in config.php';
    } else {
        // Send push notification via Firebase Cloud Messaging
        $fcm_url = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => $title,
            'body' => $message,
            'icon' => SITE_URL . '/assets/images/icon.png',
            'click_action' => $link ?: SITE_URL
        ];

        $fcm_data = [
            'to' => '/topics/all', // Send to all subscribed users
            'notification' => $notification,
            'data' => [
                'title' => $title,
                'message' => $message,
                'url' => $link ?: SITE_URL
            ]
        ];

        $headers = [
            'Authorization: key=' . FIREBASE_SERVER_KEY,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcm_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcm_data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200) {
            $success = 'Push notification sent successfully!';

            // Log the notification
            $log_file = __DIR__ . '/../data/push_log.json';
            $log = [];
            if (file_exists($log_file)) {
                $log = json_decode(file_get_contents($log_file), true) ?? [];
            }
            $log[] = [
                'date' => date('Y-m-d H:i:s'),
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'status' => 'sent'
            ];
            @file_put_contents($log_file, json_encode($log, JSON_PRETTY_PRINT));
        } else {
            $error = 'Failed to send notification. Response: ' . $result;
        }
    }
}

// Load recent notifications
$recent_notifications = [];
$log_file = __DIR__ . '/../data/push_log.json';
if (file_exists($log_file)) {
    $all_notifications = json_decode(file_get_contents($log_file), true) ?? [];
    $recent_notifications = array_slice(array_reverse($all_notifications), 0, 10);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Push Notifications - <?php echo SITE_NAME; ?></title>
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
                <h1>Push Notifications</h1>
                <p>Send notifications to app users</p>
            </div>

            <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (empty(FIREBASE_SERVER_KEY)): ?>
            <div class="alert alert-warning">
                <strong>Firebase not configured.</strong> To enable push notifications, add your Firebase Server Key to the config.php file.
            </div>
            <?php endif; ?>

            <div class="card">
                <h3 class="card-title mb-2">Send New Notification</h3>
                <form method="POST" class="admin-form">
                    <div class="form-group">
                        <label for="title" class="form-label">Notification Title *</label>
                        <input type="text" id="title" name="title" class="form-control"
                               placeholder="e.g., We're Live Now!" required maxlength="100">
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">Message *</label>
                        <textarea id="message" name="message" class="form-control"
                                  placeholder="e.g., Join us for today's live broadcast!" required maxlength="500" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="link" class="form-label">Link (Optional)</label>
                        <input type="url" id="link" name="link" class="form-control"
                               placeholder="https://jktv.live">
                        <p class="form-help">Where users go when they tap the notification. Defaults to home page.</p>
                    </div>

                    <button type="submit" class="btn btn-primary" <?php echo empty(FIREBASE_SERVER_KEY) ? 'disabled' : ''; ?>>
                        Send Notification
                    </button>
                </form>
            </div>

            <section class="dashboard-section mt-3">
                <h2 class="section-title">Recent Notifications</h2>
                <?php if (empty($recent_notifications)): ?>
                <div class="card">
                    <div class="empty-state">
                        <div class="empty-icon">&#128276;</div>
                        <p>No notifications sent yet</p>
                    </div>
                </div>
                <?php else: ?>
                <div class="card" style="padding: 0; overflow: hidden;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_notifications as $notif): ?>
                            <tr>
                                <td><?php echo date('M j, g:i A', strtotime($notif['date'])); ?></td>
                                <td><?php echo htmlspecialchars($notif['title']); ?></td>
                                <td><?php echo htmlspecialchars(substr($notif['message'], 0, 50)) . (strlen($notif['message']) > 50 ? '...' : ''); ?></td>
                                <td><span class="status-online"><?php echo ucfirst($notif['status']); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </section>

            <section class="dashboard-section mt-3">
                <h2 class="section-title">Firebase Setup</h2>
                <div class="card">
                    <p class="card-text mb-2">To enable push notifications:</p>
                    <ol class="card-text" style="padding-left: 1.5rem; line-height: 2;">
                        <li>Go to <a href="https://console.firebase.google.com/" target="_blank">Firebase Console</a></li>
                        <li>Create a project or select your existing one</li>
                        <li>Go to Project Settings > Cloud Messaging</li>
                        <li>Copy the "Server Key" (legacy)</li>
                        <li>Add it to your config.php file</li>
                    </ol>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
