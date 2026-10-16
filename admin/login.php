<?php
require_once __DIR__ . '/auth.php';

if (isAdminLoggedIn()) {
    header('Location: /admin/index.php');
    exit;
}

$error = '';
$siteData = $dataStore->getSiteData();
$site = $siteData['site'] ?? [];
$logoDark = !empty($site['logo_dark']) ? '/' . ltrim($site['logo_dark'], '/') : '/assets/ritiverse-logo-black.svg';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Rate Limiting Check (Max 5 attempts in 15 minutes)
    $rateLimit = Security::checkRateLimit('admin_login', 5, 900);
    if (!$rateLimit['allowed']) {
        $minutes = ceil($rateLimit['retry_after'] / 60);
        $error = "Too many failed login attempts. For security, access is temporarily locked. Please try again in {$minutes} minute(s).";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $csrf = $_POST['csrf_token'] ?? '';

        if (!verifyCsrfToken($csrf)) {
            $error = 'Security session expired. Please refresh and try again.';
        } elseif (empty($username) || empty($password)) {
            $error = 'Please enter both your administrator username and password.';
        } else {
            $admin = $dataStore->getAdminAuth();
            if (
                (strtolower($username) === strtolower($admin['username'] ?? '') || strtolower($username) === 'admin') &&
                password_verify($password, $admin['password_hash'] ?? '')
            ) {
                // Successful Login: Reset rate limit and prevent session fixation
                Security::resetRateLimit('admin_login');
                session_regenerate_id(true);

                $_SESSION['ritiverse_admin_id'] = $admin['username'];
                $_SESSION['ritiverse_admin_name'] = $admin['name'] ?? 'Administrator';
                $_SESSION['auth_fingerprint'] = md5($_SERVER['HTTP_USER_AGENT'] ?? 'unknown_browser');
                $_SESSION['last_activity'] = time();

                // Update last login timestamp in database
                $admin['last_login'] = date('Y-m-d H:i:s');
                $dataStore->saveAdminAuth($admin);

                setFlash('success', 'Welcome back, ' . htmlspecialchars($admin['name'] ?? 'Administrator') . '.');
                header('Location: /admin/index.php');
                exit;
            } else {
                // Failed Login: Record attempt in rate limiter
                Security::hitRateLimit('admin_login', 900);
                $remaining = max(0, $rateLimit['remaining'] - 1);
                if ($remaining > 0) {
                    $error = "Invalid credentials. {$remaining} attempt(s) remaining before temporary lockout.";
                } else {
                    $error = 'Invalid credentials. Access is now temporarily locked for 15 minutes.';
                }
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
    <title>Executive Login | <?= htmlspecialchars($site['name'] ?? 'RiTiVerse') ?> Admin Panel</title>
    <link rel="icon" type="image/svg+xml" href="<?= htmlspecialchars(!empty($site['favicon']) ? '../' . $site['favicon'] : '../assets/ritiverse-logo-white.svg') ?>">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .login-credentials-box {
            margin-top: 1.5rem;
            padding: 1.1rem;
            background: rgba(202, 130, 22, 0.08);
            border: 1px dashed rgba(202, 130, 22, 0.45);
            border-radius: 0.85rem;
            font-size: 0.8rem;
            color: #475569;
            text-align: left;
        }
        .login-credentials-box code {
            color: #9A5803;
            font-family: monospace;
            font-weight: 600;
            background: rgba(202, 130, 22, 0.15);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
        }
    </style>
</head>
<body class="login-body">

    <div class="login-card">
        <div class="login-brand">
            <img src="<?= htmlspecialchars($logoDark) ?>" alt="<?= htmlspecialchars($site['name'] ?? 'RiTiVerse') ?>" class="login-logo">
            <h2>Admin Executive Suite</h2>
            <p>High-End Real Estate Management</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="text-align:left;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" autocomplete="off" style="text-align:left;">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <div class="form-group">
                <label class="form-label" for="username">Administrator Username / Email</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="admin@ritiverse.com" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
            </div>

            <div class="form-group">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 0.45rem;">
                    <label class="form-label" for="password" style="margin-bottom:0;">Access Password</label>
                    <a href="javascript:void(0)" onclick="togglePasswordVisibility()" style="font-size:0.75rem; color:#CA8216; text-decoration:none;">Show / Hide</a>
                </div>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem; font-size: 0.95rem; margin-top: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Authenticate & Access Console
            </button>
        </form>

        <?php if (getenv('APP_ENV') !== 'production' || getenv('APP_DEBUG') === 'true'): ?>
        <div class="login-credentials-box">
            <div style="font-weight:600; color:#9A5803; margin-bottom:0.35rem; display:flex; align-items:center; gap:0.35rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                Development Setup Credentials:
            </div>
            <div>Username: <code>admin@ritiverse.com</code> or <code>admin</code></div>
            <div>Password: <code>admin123456</code></div>
            <div style="font-size:0.72rem; color:#64748b; margin-top:0.35rem;">(Hidden automatically in production environment via .env)</div>
        </div>
        <?php endif; ?>

        <div style="margin-top: 1.5rem; text-align: center;">
            <a href="../index.php" style="color: #64748b; text-decoration: none; font-size: 0.82rem; display: inline-flex; align-items: center; gap: 0.4rem; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#64748b'">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                Return to Live Website
            </a>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const pwd = document.getElementById('password');
            if (pwd.type === 'password') {
                pwd.type = 'text';
            } else {
                pwd.type = 'password';
            }
        }
    </script>
</body>
</html>
