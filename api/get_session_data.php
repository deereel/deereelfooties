<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set headers for JSON response
header('Content-Type: application/json');

// Get user from session
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

// Fallback to old session format if needed
if (!$user && isset($_SESSION['user_id'])) {
    $user = [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['username'] ?? 'User',
        'phone' => $_SESSION['phone'] ?? null
    ];
}

if ($user) {
    echo json_encode(['success' => true, 'user' => $user]);
} else {
    echo json_encode(['success' => false, 'message' => 'No user in session']);
}
?>