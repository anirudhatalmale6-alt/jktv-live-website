<?php
/**
 * JKTV Live - Contact Messages
 * View contact form submissions
 */
require_once __DIR__ . '/../includes/config.php';
requireLogin();

$contacts_file = __DIR__ . '/../data/contacts.json';
$contacts = [];
if (file_exists($contacts_file)) {
    $contacts = json_decode(file_get_contents($contacts_file), true) ?? [];
    $contacts = array_reverse($contacts); // Newest first
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $index = count($contacts) - 1 - intval($_GET['delete']);
    if (isset($contacts[$index])) {
        // Remove from reversed array, then reverse back to save
        array_splice($contacts, $index, 1);
        $contacts_to_save = array_reverse($contacts);
        file_put_contents($contacts_file, json_encode($contacts_to_save, JSON_PRETTY_PRINT));
        header('Location: /admin/contacts.php?deleted=1');
        exit;
    }
}

// Handle clear all
if (isset($_GET['clear']) && $_GET['clear'] === 'all') {
    file_put_contents($contacts_file, '[]');
    header('Location: /admin/contacts.php?cleared=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - <?php echo SITE_NAME; ?></title>
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
                <h1>Contact Messages</h1>
                <p>View messages from the contact form</p>
            </div>

            <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Message deleted successfully.</div>
            <?php endif; ?>

            <?php if (isset($_GET['cleared'])): ?>
            <div class="alert alert-success">All messages cleared.</div>
            <?php endif; ?>

            <?php if (empty($contacts)): ?>
            <div class="card">
                <div class="empty-state">
                    <div class="empty-icon">&#128233;</div>
                    <p>No contact messages yet</p>
                </div>
            </div>
            <?php else: ?>

            <div style="margin-bottom: 1rem; text-align: right;">
                <a href="/admin/contacts.php?clear=all" class="btn btn-secondary" onclick="return confirm('Are you sure you want to delete ALL messages?');">Clear All Messages</a>
            </div>

            <?php foreach ($contacts as $index => $contact): ?>
            <div class="card mb-2">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                    <div>
                        <strong style="font-size: 1.125rem;"><?php echo htmlspecialchars($contact['subject'] ?? 'No Subject'); ?></strong>
                        <br>
                        <span class="text-muted" style="font-size: 0.8125rem;">
                            From: <?php echo htmlspecialchars($contact['name'] ?? 'Unknown'); ?>
                            &lt;<?php echo htmlspecialchars($contact['email'] ?? ''); ?>&gt;
                        </span>
                    </div>
                    <div style="text-align: right;">
                        <span class="text-muted" style="font-size: 0.8125rem;">
                            <?php echo isset($contact['date']) ? date('M j, Y g:i A', strtotime($contact['date'])) : 'Unknown date'; ?>
                        </span>
                        <br>
                        <a href="/admin/contacts.php?delete=<?php echo $index; ?>" class="text-muted" style="font-size: 0.75rem; color: var(--error-color);" onclick="return confirm('Delete this message?');">Delete</a>
                    </div>
                </div>
                <div style="background: var(--background-dark); padding: 1rem; border-radius: var(--radius-sm); white-space: pre-wrap; font-size: 0.9375rem;">
<?php echo htmlspecialchars($contact['message'] ?? ''); ?>
                </div>
                <?php if (!empty($contact['email'])): ?>
                <div style="margin-top: 1rem;">
                    <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>?subject=Re: <?php echo rawurlencode($contact['subject'] ?? ''); ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                        Reply via Email
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>

            <?php endif; ?>
        </main>
    </div>
</body>
</html>
