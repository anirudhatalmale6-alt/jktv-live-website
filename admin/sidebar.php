<?php
/**
 * Admin Sidebar Component
 */
$current_admin_page = basename($_SERVER['PHP_SELF'], '.php');

// Get contact count for badge
$contacts_count = 0;
$contacts_file = __DIR__ . '/../data/contacts.json';
if (file_exists($contacts_file)) {
    $contacts = json_decode(file_get_contents($contacts_file), true) ?? [];
    $contacts_count = count($contacts);
}
?>
<aside class="admin-sidebar">
    <div class="sidebar-header">
        <h2><?php echo SITE_NAME; ?></h2>
        <span class="admin-badge">Admin</span>
    </div>
    <nav class="sidebar-nav">
        <a href="/admin/" class="nav-item <?php echo $current_admin_page === 'index' ? 'active' : ''; ?>">
            <span class="nav-icon">&#128200;</span> Dashboard
        </a>
        <a href="/admin/stream.php" class="nav-item <?php echo $current_admin_page === 'stream' ? 'active' : ''; ?>">
            <span class="nav-icon">&#128250;</span> Stream Control
        </a>
        <a href="/admin/viewers.php" class="nav-item <?php echo $current_admin_page === 'viewers' ? 'active' : ''; ?>">
            <span class="nav-icon">&#128101;</span> Viewer Count
        </a>
        <a href="/admin/push.php" class="nav-item <?php echo $current_admin_page === 'push' ? 'active' : ''; ?>">
            <span class="nav-icon">&#128276;</span> Push Notifications
        </a>
        <a href="/admin/vod.php" class="nav-item <?php echo $current_admin_page === 'vod' ? 'active' : ''; ?>">
            <span class="nav-icon">&#127909;</span> VOD Manager
        </a>
        <a href="/admin/contacts.php" class="nav-item <?php echo $current_admin_page === 'contacts' ? 'active' : ''; ?>">
            <span class="nav-icon">&#128233;</span> Contact Messages
            <?php if ($contacts_count > 0): ?>
            <span class="badge"><?php echo $contacts_count; ?></span>
            <?php endif; ?>
        </a>
        <a href="/admin/settings.php" class="nav-item <?php echo $current_admin_page === 'settings' ? 'active' : ''; ?>">
            <span class="nav-icon">&#9881;</span> Settings
        </a>
        <hr class="nav-divider">
        <a href="/admin/quick-links.php" class="nav-item <?php echo $current_admin_page === 'quick-links' ? 'active' : ''; ?>">
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

<button class="sidebar-toggle" onclick="toggleSidebar()">&#9776;</button>

<script>
function toggleSidebar() {
    document.querySelector('.admin-sidebar').classList.toggle('active');
}
</script>
