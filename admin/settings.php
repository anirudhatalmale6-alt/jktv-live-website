<?php
/**
 * JKTV Live - Settings
 * Site configuration settings
 */
require_once __DIR__ . '/../includes/config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - <?php echo SITE_NAME; ?></title>
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
                <h1>Settings</h1>
                <p>View and manage site configuration</p>
            </div>

            <div class="alert alert-warning">
                Settings are configured in the <code>includes/config.php</code> file on the server.
                Contact your administrator to change these settings.
            </div>

            <section class="dashboard-section">
                <h2 class="section-title">Current Configuration</h2>
                <div class="card">
                    <table class="info-table">
                        <tr>
                            <td><strong>Site Name</strong></td>
                            <td><?php echo htmlspecialchars(SITE_NAME); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Site URL</strong></td>
                            <td><?php echo htmlspecialchars(SITE_URL); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Contact Email</strong></td>
                            <td><?php echo htmlspecialchars(CONTACT_EMAIL); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Admin Username</strong></td>
                            <td><?php echo htmlspecialchars(ADMIN_USERNAME); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Donation URL</strong></td>
                            <td><?php echo !empty(DONATION_URL) ? htmlspecialchars(DONATION_URL) : '<span class="text-muted">Not set</span>'; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Firebase Server Key</strong></td>
                            <td><?php echo !empty(FIREBASE_SERVER_KEY) ? '<span class="status-online">Configured</span>' : '<span class="text-muted">Not configured</span>'; ?></td>
                        </tr>
                    </table>
                </div>
            </section>

            <section class="dashboard-section">
                <h2 class="section-title">Stream Configuration</h2>
                <div class="card">
                    <table class="info-table">
                        <tr>
                            <td><strong>HLS Stream URL</strong></td>
                            <td><code><?php echo htmlspecialchars(STREAM_URL); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>CDN Stream URL</strong></td>
                            <td><code><?php echo htmlspecialchars(CDN_STREAM_URL); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>RTMP Server</strong></td>
                            <td><code><?php echo htmlspecialchars(RTMP_SERVER); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>RTMPS Server</strong></td>
                            <td><code><?php echo htmlspecialchars(RTMPS_SERVER); ?></code></td>
                        </tr>
                    </table>
                </div>
            </section>

            <section class="dashboard-section">
                <h2 class="section-title">Database Configuration</h2>
                <div class="card">
                    <table class="info-table">
                        <tr>
                            <td><strong>Database Host</strong></td>
                            <td><code><?php echo htmlspecialchars(DB_HOST); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Database Name</strong></td>
                            <td><code><?php echo htmlspecialchars(DB_NAME); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Database Status</strong></td>
                            <td>
                                <?php
                                try {
                                    $pdo = getDBConnection();
                                    echo $pdo ? '<span class="status-online">Connected</span>' : '<span class="status-offline">Not connected</span>';
                                } catch (Exception $e) {
                                    echo '<span class="status-offline">Error: ' . htmlspecialchars($e->getMessage()) . '</span>';
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </section>

            <section class="dashboard-section">
                <h2 class="section-title">About Content</h2>
                <div class="card">
                    <table class="info-table">
                        <tr>
                            <td><strong>About Title</strong></td>
                            <td><?php echo htmlspecialchars(ABOUT_TITLE); ?></td>
                        </tr>
                        <tr>
                            <td><strong>About Text</strong></td>
                            <td><?php echo htmlspecialchars(substr(ABOUT_TEXT, 0, 100)) . (strlen(ABOUT_TEXT) > 100 ? '...' : ''); ?></td>
                        </tr>
                    </table>
                </div>
            </section>

            <section class="dashboard-section">
                <h2 class="section-title">How to Update Settings</h2>
                <div class="card">
                    <p class="card-text mb-2">To change settings, edit the config file on the server:</p>
                    <ol class="card-text" style="padding-left: 1.5rem; line-height: 2;">
                        <li>Connect to server via SSH or FTP</li>
                        <li>Navigate to <code>/var/www/html/includes/</code></li>
                        <li>Edit <code>config.php</code></li>
                        <li>Save and the changes take effect immediately</li>
                    </ol>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
