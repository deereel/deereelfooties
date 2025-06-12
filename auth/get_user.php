<?php
// Simple function to check if user is logged in
function getCurrentUser() {
    // Check for user in session
    if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
        return $_SESSION['user'];
    }
    
    // Backward compatibility - create user array from individual session variables
    if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
        $_SESSION['user'] = [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['username'],
            'email' => $_SESSION['email'] ?? '',
            'role' => $_SESSION['role'] ?? 'user'
        ];
        return $_SESSION['user'];
    }
    
    return null;
}

// For direct access
if (basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    header('Content-Type: application/json');
    $user = getCurrentUser();
    if ($user) {
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No active session']);
    }
}