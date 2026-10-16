<?php
/**
 * RiTiVerse - VIP Client Inquiry & Mandate Transmission Handler
 * Production-hardened with rate limiting, honeypot bot trap, and strict validation
 */

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/includes/security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// 1. IP Rate Limiting (Max 10 submissions per IP per hour)
$rateLimit = Security::checkRateLimit('public_inquiry', 10, 3600);
if (!$rateLimit['allowed']) {
    http_response_code(429);
    echo json_encode([
        'success' => false,
        'message' => 'Too many submissions from this connection. Please try again later or contact our concierge directly.'
    ]);
    exit;
}

// Support both standard POST (FormData) and JSON payload
$rawInput = file_get_contents('php://input');
$jsonData = json_decode($rawInput, true);

if (is_array($jsonData)) {
    // 2. Honeypot Check (Bots fill hidden fields)
    if (!empty($jsonData['website_url_hp']) || !empty($jsonData['vip_code_extra'])) {
        // Silently discard spam bot submission
        echo json_encode([
            'success' => true,
            'message' => 'Thank you. Your mandate has been safely transmitted.',
            'ref'     => 'MANDATE-' . strtoupper(substr(uniqid(), -6))
        ]);
        exit;
    }

    $name        = Security::cleanInput($jsonData['name'] ?? '', 100);
    $email       = filter_var(trim($jsonData['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone       = Security::cleanInput($jsonData['phone'] ?? '', 30);
    $residence   = Security::cleanInput($jsonData['residence'] ?? 'general', 150);
    $budget      = Security::cleanInput($jsonData['budget'] ?? '', 100);
    $profile     = Security::cleanInput($jsonData['profile'] ?? '', 100);
    $consultMode = Security::cleanInput($jsonData['consultation_mode'] ?? '', 100);
    $message     = Security::cleanInput($jsonData['message'] ?? $jsonData['notes'] ?? '', 3000);
} else {
    // 2. Honeypot Check (FormData)
    if (!empty($_POST['website_url_hp']) || !empty($_POST['vip_code_extra'])) {
        echo json_encode([
            'success' => true,
            'message' => 'Thank you. Your mandate has been safely transmitted.',
            'ref'     => 'MANDATE-' . strtoupper(substr(uniqid(), -6))
        ]);
        exit;
    }

    $name        = Security::cleanInput($_POST['name'] ?? '', 100);
    $rawEmail    = trim($_POST['email'] ?? '');
    $email       = filter_var($rawEmail, FILTER_VALIDATE_EMAIL);
    $phone       = Security::cleanInput($_POST['phone'] ?? '', 30);
    $residence   = Security::cleanInput($_POST['residence'] ?? 'general', 150);
    $budget      = Security::cleanInput($_POST['budget'] ?? '', 100);
    $profile     = Security::cleanInput($_POST['profile'] ?? '', 100);
    $consultMode = Security::cleanInput($_POST['consultation_mode'] ?? '', 100);
    $message     = Security::cleanInput($_POST['message'] ?? $_POST['notes'] ?? '', 3000);
}

// Validate essential required fields
if (empty($name) || empty($email) || empty($phone)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Please provide a valid full name, email address, and telephone number.'
    ]);
    exit;
}

// Basic phone number format check
if (strlen(preg_replace('/[^0-9]/', '', $phone)) < 6) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid telephone number with country code.'
    ]);
    exit;
}

// Hit rate limit counter
Security::hitRateLimit('public_inquiry', 3600);

$inquiry = [
    'id'                => 'MANDATE-' . strtoupper(substr(uniqid(), -6)),
    'timestamp'         => date('Y-m-d H:i:s'),
    'name'              => $name,
    'email'             => $email,
    'phone'             => $phone,
    'residence'         => $residence,
    'budget'            => $budget,
    'profile'           => $profile,
    'consultation_mode' => $consultMode,
    'message'           => $message,
    'ip'                => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
];

// Save directly to MySQL database via DataStore
require_once __DIR__ . '/includes/data_store.php';
$dataStore = DataStore::getInstance();
$dataStore->saveInquiry($inquiry);

// Trigger admin email notification according to mail settings
@$dataStore->notifyAdminNewInquiry($inquiry);

echo json_encode([
    'success' => true,
    'message' => 'Thank you, ' . htmlspecialchars($name) . '. Our private wealth desk will contact you under complete confidentiality.',
    'ref'     => $inquiry['id']
]);
exit;
