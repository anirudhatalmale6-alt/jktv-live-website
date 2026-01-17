<?php
/**
 * JKTV Live - Contact Page
 * Contact form with server-side validation and spam protection
 */
$page_title = 'Contact Us';

// Process form submission
$success = false;
$error = '';
$form_data = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF token validation
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = 'Invalid form submission. Please try again.';
    }
    // Honeypot check (spam protection)
    elseif (!empty($_POST['website'])) {
        // Bot detected, silently "succeed" to not alert the bot
        $success = true;
    }
    // Time-based check (form must take at least 3 seconds to fill)
    elseif (isset($_POST['form_time']) && (time() - intval($_POST['form_time'])) < 3) {
        $error = 'Please take your time filling out the form.';
    }
    else {
        // Sanitize inputs
        $form_data['name'] = trim($_POST['name'] ?? '');
        $form_data['email'] = trim($_POST['email'] ?? '');
        $form_data['subject'] = trim($_POST['subject'] ?? '');
        $form_data['message'] = trim($_POST['message'] ?? '');

        // Validate
        if (empty($form_data['name'])) {
            $error = 'Please enter your name.';
        } elseif (strlen($form_data['name']) > 100) {
            $error = 'Name is too long (max 100 characters).';
        } elseif (empty($form_data['email'])) {
            $error = 'Please enter your email address.';
        } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (empty($form_data['subject'])) {
            $error = 'Please enter a subject.';
        } elseif (strlen($form_data['subject']) > 200) {
            $error = 'Subject is too long (max 200 characters).';
        } elseif (empty($form_data['message'])) {
            $error = 'Please enter your message.';
        } elseif (strlen($form_data['message']) > 5000) {
            $error = 'Message is too long (max 5000 characters).';
        } else {
            // All validations passed, send email
            $to = CONTACT_EMAIL;
            $email_subject = '[JKTV Contact] ' . $form_data['subject'];
            $email_body = "You have received a new message from the JKTV Live website.\n\n";
            $email_body .= "Name: " . $form_data['name'] . "\n";
            $email_body .= "Email: " . $form_data['email'] . "\n";
            $email_body .= "Subject: " . $form_data['subject'] . "\n\n";
            $email_body .= "Message:\n" . $form_data['message'] . "\n";
            $email_body .= "\n---\nSent from: " . $_SERVER['REMOTE_ADDR'];
            $email_body .= "\nTime: " . date('Y-m-d H:i:s');

            $headers = "From: noreply@" . parse_url(SITE_URL, PHP_URL_HOST) . "\r\n";
            $headers .= "Reply-To: " . $form_data['email'] . "\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();

            if (mail($to, $email_subject, $email_body, $headers)) {
                $success = true;
                // Clear form data on success
                $form_data = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

                // Also save to file as backup
                $contact_log = __DIR__ . '/data/contacts.json';
                $contacts = [];
                if (file_exists($contact_log)) {
                    $contacts = json_decode(file_get_contents($contact_log), true) ?? [];
                }
                $contacts[] = [
                    'date' => date('Y-m-d H:i:s'),
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'subject' => $_POST['subject'],
                    'message' => $_POST['message'],
                    'ip' => $_SERVER['REMOTE_ADDR']
                ];
                @file_put_contents($contact_log, json_encode($contacts, JSON_PRETTY_PRINT));
            } else {
                $error = 'Failed to send message. Please try again later.';
            }
        }
    }
}

// Generate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

require_once 'includes/header.php';
?>

<div class="page-header">
    <h1 class="page-title">Contact Us</h1>
    <p class="page-subtitle">We'd love to hear from you</p>
</div>

<section class="section">
    <div class="card" style="max-width: 700px; margin: 0 auto;">
        <?php if ($success): ?>
        <div class="alert alert-success">
            <strong>Thank you!</strong> Your message has been sent successfully. We'll get back to you soon.
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="/contact.php" id="contactForm">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <!-- Time-based spam protection -->
            <input type="hidden" name="form_time" value="<?php echo time(); ?>">

            <!-- Honeypot field (hidden from real users, bots will fill it) -->
            <div style="position: absolute; left: -9999px;">
                <label for="website">Website (leave blank)</label>
                <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="form-group">
                <label for="name" class="form-label">Your Name *</label>
                <input type="text" id="name" name="name" class="form-control"
                       value="<?php echo htmlspecialchars($form_data['name']); ?>"
                       placeholder="John Doe" required maxlength="100">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email Address *</label>
                <input type="email" id="email" name="email" class="form-control"
                       value="<?php echo htmlspecialchars($form_data['email']); ?>"
                       placeholder="john@example.com" required maxlength="255">
            </div>

            <div class="form-group">
                <label for="subject" class="form-label">Subject *</label>
                <input type="text" id="subject" name="subject" class="form-control"
                       value="<?php echo htmlspecialchars($form_data['subject']); ?>"
                       placeholder="What is this about?" required maxlength="200">
            </div>

            <div class="form-group">
                <label for="message" class="form-label">Message *</label>
                <textarea id="message" name="message" class="form-control"
                          placeholder="Your message here..." required maxlength="5000"><?php echo htmlspecialchars($form_data['message']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Send Message</button>
        </form>
    </div>
</section>

<section class="section text-center">
    <p class="text-muted">We typically respond within 24-48 hours.</p>
</section>

<?php require_once 'includes/footer.php'; ?>
