<?php
session_start();
header('Content-Type: application/json');

// Check if user session exists
if (isset($_SESSION['user'])) {
    echo json_encode([
        'success' => true,
        'user' => $_SESSION['user']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No active session'
    ]);
}
