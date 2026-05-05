<?php
require_once __DIR__ . '/includes/init.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method not allowed']);
    exit();
}

$email = trim((string)($_POST['email'] ?? ''));
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Please enter a valid email address.']);
    exit();
}

// Insert (ignore duplicate)
$stmt = execute_query("INSERT INTO newsletter_subscribers (email, subscribed_at, is_active)
                       VALUES (?, NOW(), TRUE)
                       ON DUPLICATE KEY UPDATE is_active = TRUE", "s", [$email]);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Could not subscribe right now.']);
    exit();
}

echo json_encode(['ok' => true, 'message' => 'Subscribed successfully.']);
exit();

