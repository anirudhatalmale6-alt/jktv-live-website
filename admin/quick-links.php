<?php
/**
 * JKTV Live - Quick Links
 * Easy access to all management tools
 */
require_once __DIR__ . '/../includes/config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Links - <?php echo SITE_NAME; ?></title>
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
                <h1>Quick Links</h1>
                <p>Fast access to all your tools and pages</p>
            </div>

            <section class="dashboard-section">
                <h2 class="section-title">Website Pages</h2>
                <div class="quick-links-grid">
                    <a href="/" target="_blank" class="quick-link-card">
                        <h3>&#127760; Home Page</h3>
                        <p>Main page with live player</p>
                    </a>
                    <a href="/about.php" target="_blank" class="quick-link-card">
                        <h3>&#128100; About Us</h3>
                        <p>About page</p>
                    </a>
                    <a href="/vod.php" target="_blank" class="quick-link-card">
                        <h3>&#127909; VOD Page</h3>
                        <p>Video on Demand library</p>
                    </a>
                    <a href="/support.php" target="_blank" class="quick-link-card">
                        <h3>&#128176; Support Page</h3>
                        <p>Donation/support page</p>
                    </a>
                    <a href="/contact.php" target="_blank" class="quick-link-card">
                        <h3>&#128233; Contact Page</h3>
                        <p>Contact form</p>
                    </a>
                </div>
            </section>

            <section class="dashboard-section">
                <h2 class="section-title">Legacy Admin Tools</h2>
                <div class="quick-links-grid">
                    <a href="/live.html" target="_blank" class="quick-link-card">
                        <h3>&#128101; Old Viewer Count</h3>
                        <p>Legacy viewer count control</p>
                    </a>
                    <a href="/stream_info.php" target="_blank" class="quick-link-card">
                        <h3>&#128250; Old Stream Info</h3>
                        <p>Legacy stream info editor</p>
                    </a>
                    <a href="/stat" target="_blank" class="quick-link-card">
                        <h3>&#128202; RTMP Statistics</h3>
                        <p>NGINX RTMP module stats</p>
                    </a>
                </div>
            </section>

            <section class="dashboard-section">
                <h2 class="section-title">Stream URLs</h2>
                <div class="card">
                    <table class="info-table">
                        <tr>
                            <td><strong>HLS Stream</strong></td>
                            <td><code><?php echo STREAM_URL; ?></code></td>
                            <td><button class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="copyText('<?php echo STREAM_URL; ?>')">Copy</button></td>
                        </tr>
                        <tr>
                            <td><strong>CDN Stream</strong></td>
                            <td><code><?php echo CDN_STREAM_URL; ?></code></td>
                            <td><button class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="copyText('<?php echo CDN_STREAM_URL; ?>')">Copy</button></td>
                        </tr>
                        <tr>
                            <td><strong>RTMP Server</strong></td>
                            <td><code><?php echo RTMP_SERVER; ?></code></td>
                            <td><button class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="copyText('<?php echo RTMP_SERVER; ?>')">Copy</button></td>
                        </tr>
                        <tr>
                            <td><strong>RTMPS Server</strong></td>
                            <td><code><?php echo RTMPS_SERVER; ?></code></td>
                            <td><button class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="copyText('<?php echo RTMPS_SERVER; ?>')">Copy</button></td>
                        </tr>
                        <tr>
                            <td><strong>Stream Key</strong></td>
                            <td><code><?php echo DEFAULT_STREAM_KEY; ?></code></td>
                            <td><button class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="copyText('<?php echo DEFAULT_STREAM_KEY; ?>')">Copy</button></td>
                        </tr>
                    </table>
                </div>
            </section>

            <section class="dashboard-section">
                <h2 class="section-title">External Services</h2>
                <div class="quick-links-grid">
                    <a href="https://dash.cloudflare.com" target="_blank" class="quick-link-card">
                        <h3>&#9729; Cloudflare</h3>
                        <p>DNS & Security settings</p>
                    </a>
                    <a href="https://panel.bunny.net" target="_blank" class="quick-link-card">
                        <h3>&#128007; BunnyCDN</h3>
                        <p>CDN management</p>
                    </a>
                    <a href="https://console.firebase.google.com" target="_blank" class="quick-link-card">
                        <h3>&#128293; Firebase</h3>
                        <p>Push notifications</p>
                    </a>
                </div>
            </section>
        </main>
    </div>

    <script>
        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copied to clipboard!');
            });
        }
    </script>
</body>
</html>
