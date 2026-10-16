<?php
/**
 * RiTiVerse Admin Authentication & Session Guard
 * Production-hardened with session hijacking, fixation, and timeout protection
 */

require_once dirname(__DIR__) . '/includes/security.php';

$dataStore = require dirname(__DIR__) . '/includes/data_store.php';

// Session Inactivity Timeout (2 Hours = 7200 seconds)
define('ADMIN_SESSION_TIMEOUT', 7200);

function isAdminLoggedIn(): bool
{
    if (empty($_SESSION['ritiverse_admin_id'])) {
        return false;
    }

    // 1. Session Inactivity Timeout Check
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > ADMIN_SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        return false;
    }
    $_SESSION['last_activity'] = time();

    // 2. User-Agent Fingerprint Check (Anti-Session Hijacking)
    $currentFp = md5($_SERVER['HTTP_USER_AGENT'] ?? 'unknown_browser');
    if (isset($_SESSION['auth_fingerprint']) && $_SESSION['auth_fingerprint'] !== $currentFp) {
        session_unset();
        session_destroy();
        return false;
    }

    return true;
}

function requireAuth(): void
{
    if (!isAdminLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function getAdminUser(): array
{
    global $dataStore;
    return $dataStore->getAdminAuth();
}

function generateCsrfToken(): string
{
    if (empty($_SESSION['admin_csrf_token'])) {
        $_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['admin_csrf_token'];
}

function verifyCsrfToken(?string $token): bool
{
    if (empty($token) || empty($_SESSION['admin_csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['admin_csrf_token'], $token);
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash_message'] = [
        'type'    => $type, // 'success', 'danger', 'warning'
        'message' => $message
    ];
}

function getFlash(): ?array
{
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}
