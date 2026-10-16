<?php
require_once __DIR__ . '/auth.php';
requireAuth();

$pageTitle = 'Mail & SMTP Configuration';
$currentPage = 'mail.php';

$mailSettings = $dataStore->getMailSettings();
$errorMsg = '';
$testOutput = null;

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    $action = $_POST['mail_action'] ?? 'save';

    if (!verifyCsrfToken($csrf)) {
        $errorMsg = 'Security token invalid or expired. Please reload and try again.';
    } else {
        // 1. SAVE SETTINGS
        if ($action === 'save') {
            $mailSettings['driver']                   = trim($_POST['driver'] ?? 'mail');
            $mailSettings['smtp_host']                = trim($_POST['smtp_host'] ?? '');
            $mailSettings['smtp_port']                = (int)($_POST['smtp_port'] ?? 587);
            $mailSettings['smtp_encryption']          = trim($_POST['smtp_encryption'] ?? 'tls');
            $mailSettings['smtp_username']            = trim($_POST['smtp_username'] ?? '');

            // Keep existing password if empty
            $newPwd = $_POST['smtp_password'] ?? '';
            if ($newPwd !== '') {
                $mailSettings['smtp_password'] = $newPwd;
            }

            $mailSettings['from_email']               = trim($_POST['from_email'] ?? 'concierge@ritiverse.com');
            $mailSettings['from_name']                = trim($_POST['from_name'] ?? 'RiTiVerse Concierge');
            $mailSettings['admin_notification_email'] = trim($_POST['admin_notification_email'] ?? 'concierge@ritiverse.com');
            $mailSettings['notify_on_new_inquiry']    = !empty($_POST['notify_on_new_inquiry']);

            $dataStore->saveMailSettings($mailSettings);
            setFlash('success', 'Mail and SMTP configuration saved successfully.');
            header('Location: mail.php');
            exit;
        }

        // 2. SEND TEST EMAIL
        if ($action === 'test') {
            $testTo = trim($_POST['test_email'] ?? '');
            if (empty($testTo) || !filter_var($testTo, FILTER_VALIDATE_EMAIL)) {
                $errorMsg = 'Please enter a valid recipient email address for testing.';
            } else {
                $testSubject = 'RiTiVerse System Mail Diagnostics Test [' . date('H:i:s') . ']';
                $testBody = "
                <div style='font-family: Arial, sans-serif; background: #141312; color: #ffffff; padding: 25px; border-radius: 12px; max-width: 600px; margin: 0 auto; border: 1px solid rgba(255,255,255,0.1);'>
                    <h2 style='color: #CA8216; margin-top: 0;'>RiTiVerse SMTP Diagnostics Success</h2>
                    <p>This is a verification test email dispatched from the RiTiVerse Executive Admin Console.</p>
                    <div style='background: #1c1b1a; padding: 15px; border-radius: 8px; margin: 20px 0; font-size: 13px; line-height: 1.6;'>
                        <div><strong>Driver:</strong> " . strtoupper($mailSettings['driver'] ?? 'mail') . "</div>
                        <div><strong>SMTP Host:</strong> " . htmlspecialchars($mailSettings['smtp_host'] ?: 'Default') . "</div>
                        <div><strong>SMTP Port:</strong> " . (int)($mailSettings['smtp_port'] ?? 587) . "</div>
                        <div><strong>Encryption:</strong> " . strtoupper($mailSettings['smtp_encryption'] ?? 'tls') . "</div>
                        <div><strong>From Address:</strong> " . htmlspecialchars($mailSettings['from_email'] ?? '') . "</div>
                        <div><strong>Timestamp:</strong> " . date('r') . "</div>
                    </div>
                    <p style='color: #999; font-size: 12px; margin-bottom: 0;'>If you received this message, your mail configuration is operational and client leads will be delivered reliably.</p>
                </div>";

                $testResult = $dataStore->sendEmail($testTo, $testSubject, $testBody);
                $testOutput = $testResult;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?> | RiTiVerse Admin</title>
    <link rel="icon" type="image/svg+xml" href="../assets/ritiverse-logo-white.svg">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .smtp-log-console {
            background: #000000;
            color: #10b981;
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.8rem;
            padding: 1.25rem;
            border-radius: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-height: 350px;
            overflow-y: auto;
            white-space: pre-wrap;
            line-height: 1.5;
            margin-top: 1rem;
        }
    </style>
</head>
<body>

    <?php require __DIR__ . '/includes/sidebar.php'; ?>

    <div class="admin-main">
        <?php require __DIR__ . '/includes/topbar.php'; ?>

        <main class="admin-content">
            <div class="page-header">
                <div>
                    <h2>Mail & Notification Settings</h2>
                    <p>Configure outgoing SMTP servers, notification recipients for inquiries, and test email delivery.</p>
                </div>
            </div>

            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span><?= htmlspecialchars($errorMsg) ?></span>
                </div>
            <?php endif; ?>

            <!-- Test Output Box if triggered -->
            <?php if ($testOutput !== null): ?>
                <div class="alert <?= $testOutput['success'] ? 'alert-success' : 'alert-danger' ?>">
                    <div>
                        <div style="font-weight:700; margin-bottom:0.25rem;">
                            <?= $testOutput['success'] ? '✓ Test Email Dispatched Successfully!' : '✗ Test Email Failed' ?>
                        </div>
                        <div style="font-size:0.82rem;"><?= htmlspecialchars($testOutput['error'] ?: 'Connection established and message accepted by recipient server.') ?></div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h3>Server Connection Transcript Log</h3>
                            <p>Real-time socket communication log with SMTP mail transfer agent</p>
                        </div>
                    </div>
                    <div class="panel-body" style="padding:1rem;">
                        <div class="smtp-log-console"><?= htmlspecialchars($testOutput['log']) ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Mail Configuration Form -->
            <form method="POST" action="mail.php">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="mail_action" value="save">

                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h3>Mail Delivery Engine</h3>
                            <p>Select whether to use pure SMTP or PHP native mail()</p>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="form-label">Delivery Driver</label>
                            <div style="display:flex; gap:2rem; align-items:center;">
                                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; color:var(--text-main);">
                                    <input type="radio" name="driver" value="smtp" <?= ($mailSettings['driver'] ?? 'smtp') === 'smtp' ? 'checked' : '' ?> onchange="toggleSmtpSection(true)">
                                    SMTP Server (Recommended &mdash; Gmail, Mailgun, SendGrid, Amazon SES, Office365)
                                </label>
                                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; color:var(--text-main);">
                                    <input type="radio" name="driver" value="mail" <?= ($mailSettings['driver'] ?? 'smtp') === 'mail' ? 'checked' : '' ?> onchange="toggleSmtpSection(false)">
                                    PHP Native mail() Function
                                </label>
                            </div>
                        </div>

                        <!-- SMTP Credentials Fields -->
                        <div id="smtpCredentials" style="display: <?= ($mailSettings['driver'] ?? 'smtp') === 'smtp' ? 'block' : 'none' ?>; margin-top:1.5rem; background:rgba(255,255,255,0.02); padding:1.5rem; border-radius:1rem; border:1px solid var(--border-color);">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="smtp_host">SMTP Host Server <span class="required">*</span></label>
                                    <input type="text" id="smtp_host" name="smtp_host" class="form-control" value="<?= htmlspecialchars($mailSettings['smtp_host'] ?? '') ?>" placeholder="e.g. smtp.gmail.com or smtp.mailgun.org">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="smtp_port">SMTP Port <span class="required">*</span></label>
                                    <input type="number" id="smtp_port" name="smtp_port" class="form-control" value="<?= (int)($mailSettings['smtp_port'] ?? 587) ?>" placeholder="587 or 465">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="smtp_encryption">Encryption Protocol</label>
                                    <select id="smtp_encryption" name="smtp_encryption" class="form-control">
                                        <option value="tls" <?= ($mailSettings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS / STARTTLS (Port 587 - Standard)</option>
                                        <option value="ssl" <?= ($mailSettings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL (Port 465)</option>
                                        <option value="none" <?= ($mailSettings['smtp_encryption'] ?? '') === 'none' ? 'selected' : '' ?>>None (Port 25 / Plain)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="smtp_username">SMTP Username / Email</label>
                                    <input type="text" id="smtp_username" name="smtp_username" class="form-control" value="<?= htmlspecialchars($mailSettings['smtp_username'] ?? '') ?>" placeholder="your-email@domain.com">
                                </div>
                                <div class="form-group">
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.45rem;">
                                        <label class="form-label" for="smtp_password" style="margin-bottom:0;">SMTP Password / App Password</label>
                                        <a href="javascript:void(0)" onclick="toggleSmtpPassword()" style="font-size:0.75rem; color:#CA8216; text-decoration:none;">Show / Hide</a>
                                    </div>
                                    <input type="password" id="smtp_password" name="smtp_password" class="form-control" value="<?= htmlspecialchars($mailSettings['smtp_password'] ?? '') ?>" placeholder="Leave blank to keep current password">
                                </div>
                            </div>
                        </div>

                        <!-- Sender & Notification Details -->
                        <div style="margin-top:2rem;">
                            <h4 style="font-size:1rem; color:var(--text-main); margin-bottom:1rem;">Sender Identity & Lead Notification Routing</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="from_email">Outgoing From Email Address <span class="required">*</span></label>
                                    <input type="email" id="from_email" name="from_email" class="form-control" value="<?= htmlspecialchars($mailSettings['from_email'] ?? 'concierge@ritiverse.com') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="from_name">Outgoing From Display Name <span class="required">*</span></label>
                                    <input type="text" id="from_name" name="from_name" class="form-control" value="<?= htmlspecialchars($mailSettings['from_name'] ?? 'RiTiVerse Concierge') ?>" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="admin_notification_email">Admin Lead Notification Recipient Email <span class="required">*</span></label>
                                    <input type="email" id="admin_notification_email" name="admin_notification_email" class="form-control" value="<?= htmlspecialchars($mailSettings['admin_notification_email'] ?? 'concierge@ritiverse.com') ?>" required>
                                    <div class="form-hint">Whenever a prospective buyer submits an inquiry, an instant alert is delivered to this inbox.</div>
                                </div>
                                <div class="form-group" style="display:flex; flex-direction:column; justify-content:center;">
                                    <label style="display:flex; align-items:center; gap:0.6rem; cursor:pointer; color:var(--text-main); font-size:0.9rem; margin-top:1.25rem;">
                                        <input type="checkbox" name="notify_on_new_inquiry" value="1" <?= !empty($mailSettings['notify_on_new_inquiry']) ? 'checked' : '' ?> style="width:18px; height:18px; accent-color:var(--accent-gold);">
                                        <strong>Automatically send instant email alert on new client inquiry</strong>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Save Mail Settings
                        </button>
                    </div>
                </div>
            </form>

            <!-- Test Email Delivery Panel -->
            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3>Live Mail Dispatch Verification</h3>
                        <p>Send a real test email to verify your SMTP server handshake and credentials</p>
                    </div>
                </div>
                <div class="panel-body">
                    <form method="POST" action="mail.php" style="display:flex; gap:1rem; align-items:flex-end; flex-wrap:wrap;">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="mail_action" value="test">

                        <div class="form-group" style="flex:1; min-width:280px; margin-bottom:0;">
                            <label class="form-label" for="test_email">Test Recipient Email Address</label>
                            <input type="email" id="test_email" name="test_email" class="form-control" placeholder="your-personal-email@domain.com" value="<?= htmlspecialchars($mailSettings['admin_notification_email'] ?? '') ?>" required>
                        </div>

                        <button type="submit" class="btn btn-secondary" style="height:42px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            Send Test Email & View Log
                        </button>
                    </form>
                </div>
            </div>

        </main>

        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>

    <script>
        function toggleSmtpSection(isSmtp) {
            document.getElementById('smtpCredentials').style.display = isSmtp ? 'block' : 'none';
        }
        function toggleSmtpPassword() {
            const pwd = document.getElementById('smtp_password');
            pwd.type = pwd.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
