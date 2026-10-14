<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$name      = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$email     = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?? '');
$phone     = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$residence = trim(filter_input(INPUT_POST, 'residence', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'general');
$message   = trim(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

if (empty($name) || empty($email) || empty($phone)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Please provide your full name, email address, and phone number.'
    ]);
    exit;
}

$inquiry = [
    'id'        => 'INQ-' . strtoupper(substr(uniqid(), -6)),
    'timestamp' => date('c'),
    'name'      => $name,
    'email'     => $email,
    'phone'     => $phone,
    'residence' => $residence,
    'message'   => $message,
    'ip'        => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
];

$storageFile = __DIR__ . '/inquiries.json';
$inquiries = [];

if (file_exists($storageFile)) {
    $existing = json_decode(file_contents($storageFile), true);
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
