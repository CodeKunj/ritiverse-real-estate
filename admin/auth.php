<?php
/**
 * RiTiVerse Admin Authentication & Session Guard
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dataStore = require dirname(__DIR__) . '/includes/data_store.php';

function isAdminLoggedIn(): bool
{
    return isset($_SESSION['ritiverse_admin_id']) && !empty($_SESSION['ritiverse_admin_id']);
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
