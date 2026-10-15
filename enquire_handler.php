<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Support both standard POST (FormData) and JSON payload
$rawInput = file_get_contents('php://input');
$jsonData = json_decode($rawInput, true);

if (is_array($jsonData)) {
    $name      = trim($jsonData['name'] ?? '');
    $email     = filter_var(trim($jsonData['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone     = trim($jsonData['phone'] ?? '');
    $residence = trim($jsonData['residence'] ?? 'general');
    $budget    = trim($jsonData['budget'] ?? '');
    $profile   = trim($jsonData['profile'] ?? '');
    $consultMode = trim($jsonData['consultation_mode'] ?? '');
    $message   = trim($jsonData['message'] ?? $jsonData['notes'] ?? '');
} else {
    $name      = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $email     = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?? '');
    $phone     = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $residence = trim(filter_input(INPUT_POST, 'residence', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'general');
    $budget    = trim(filter_input(INPUT_POST, 'budget', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $profile   = trim(filter_input(INPUT_POST, 'profile', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $consultMode = trim(filter_input(INPUT_POST, 'consultation_mode', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
    $message   = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS) ?? filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
}

if (empty($name) || empty($email) || empty($phone)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Please provide your full name, email address, and phone number.'
    ]);
    exit;
}

$inquiry = [
    'id'                => 'MANDATE-' . strtoupper(substr(uniqid(), -6)),
    'timestamp'         => date('c'),
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

$storageFile = __DIR__ . '/inquiries.json';
$inquiries = [];

if (file_exists($storageFile)) {
    $existing = json_decode(@file_get_contents($storageFile), true);
    if (is_array($existing)) {
        $inquiries = $existing;
    }
}

$inquiries[] = $inquiry;
@file_put_contents($storageFile, json_encode($inquiries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo json_encode([
    'success' => true,
    'message' => 'Thank you, ' . htmlspecialchars($name) . '. Our private advisory team will contact you shortly.',
    'ref'     => $inquiry['id']
]);
exit;
