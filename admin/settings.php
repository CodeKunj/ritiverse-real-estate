<?php
require_once __DIR__ . '/auth.php';
requireAuth();

$pageTitle = 'Password & Admin Security';
$currentPage = 'settings.php';

$admin = $dataStore->getAdminAuth();
$errorMsg = '';

// Handle Password or Profile Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    $action = $_POST['settings_action'] ?? '';

    if (!verifyCsrfToken($csrf)) {
        $errorMsg = 'Security token invalid or expired. Please reload and try again.';
    } else {
        // 1. UPDATE PASSWORD
        if ($action === 'password') {
            $currentPwd = $_POST['current_password'] ?? '';
            $newPwd     = $_POST['new_password'] ?? '';
            $confirmPwd = $_POST['confirm_password'] ?? '';

            if (empty($currentPwd) || empty($newPwd) || empty($confirmPwd)) {
                $errorMsg = 'Please fill out all password fields.';
            } elseif (!password_verify($currentPwd, $admin['password_hash'] ?? '')) {
                $errorMsg = 'The current password you entered is incorrect.';
            } elseif (strlen($newPwd) < 6) {
                $errorMsg = 'New password must be at least 6 characters in length.';
            } elseif ($newPwd !== $confirmPwd) {
                $errorMsg = 'The new password and confirmation password do not match.';
            } else {
                $admin['password_hash'] = password_hash($newPwd, PASSWORD_DEFAULT);
                $dataStore->saveAdminAuth($admin);

                setFlash('success', 'Admin panel password changed successfully. Please remember your new password.');
                header('Location: settings.php');
                exit;
            }
        }

        // 2. UPDATE PROFILE DETAILS
        if ($action === 'profile') {
            $newName = trim($_POST['admin_name'] ?? '');
            $newUsername = trim($_POST['admin_username'] ?? '');

            if (empty($newName) || empty($newUsername)) {
                $errorMsg = 'Display name and username cannot be left empty.';
            } else {
                $admin['name'] = $newName;
                $admin['username'] = $newUsername;
                $_SESSION['ritiverse_admin_id'] = $newUsername;
                $_SESSION['ritiverse_admin_name'] = $newName;

                $dataStore->saveAdminAuth($admin);
                setFlash('success', 'Admin profile and login username updated successfully.');
                header('Location: settings.php');
                exit;
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
</head>
<body>

    <?php require __DIR__ . '/includes/sidebar.php'; ?>

    <div class="admin-main">
        <?php require __DIR__ . '/includes/topbar.php'; ?>

        <main class="admin-content">
            <div class="page-header">
                <div>
                    <h2>Password & Security Console</h2>
                    <p>Update administrator credentials, change login password, and manage console security.</p>
                </div>
            </div>

            <?php if (!empty($errorMsg)): ?>
                <div class="alert alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span><?= htmlspecialchars($errorMsg) ?></span>
                </div>
            <?php endif; ?>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.75rem;" class="form-row">
                
                <!-- CHANGE PASSWORD PANEL -->
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h3>Change Admin Password</h3>
                            <p>Verify your current password to set a new secure password</p>
                        </div>
                    </div>
                    <form method="POST" action="settings.php">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="settings_action" value="password">

                        <div class="panel-body">
                            <div class="form-group">
                                <label class="form-label" for="current_password">Current Password <span class="required">*</span></label>
                                <input type="password" id="current_password" name="current_password" class="form-control" placeholder="••••••••••••" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="new_password">New Password <span class="required">*</span></label>
                                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Min. 6 characters" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="confirm_password">Confirm New Password <span class="required">*</span></label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Re-enter new password" required>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ADMIN PROFILE & INFO PANEL -->
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h3>Admin Profile Identity</h3>
                            <p>Display name and administrator login username/email</p>
                        </div>
                    </div>
                    <form method="POST" action="settings.php">
                        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                        <input type="hidden" name="settings_action" value="profile">

                        <div class="panel-body">
                            <div class="form-group">
                                <label class="form-label" for="admin_name">Administrator Display Name</label>
                                <input type="text" id="admin_name" name="admin_name" class="form-control" value="<?= htmlspecialchars($admin['name'] ?? 'RiTiVerse Administrator') ?>" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="admin_username">Login Username / Email</label>
                                <input type="text" id="admin_username" name="admin_username" class="form-control" value="<?= htmlspecialchars($admin['username'] ?? 'admin@ritiverse.com') ?>" required>
                                <div class="form-hint">This username or email is used when logging into this admin panel.</div>
                            </div>

                            <div style="margin-top:1.5rem; background:var(--bg-card-hover); padding:1rem; border-radius:0.75rem; border:1px solid var(--border-color); font-size:0.8rem; color:var(--text-dim); line-height:1.6;">
                                <div><strong>Last Sign In:</strong> <?= !empty($admin['last_login']) ? date('M d, Y H:i:s', strtotime($admin['last_login'])) : 'First Session' ?></div>
                                <div><strong>Security Algorithm:</strong> PHP BCRYPT (Standard)</div>
                                <div><strong>Session IP:</strong> <?= htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1') ?></div>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Save Profile Details
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>

        <?php require __DIR__ . '/includes/footer.php'; ?>
    </div>

</body>
</html>
