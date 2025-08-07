<?php
// CSRF Protection
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Rate Limiting
function checkRateLimit($action, $limit = 5, $window = 300) {
    $key = $action . '_' . $_SERVER['REMOTE_ADDR'];
    $attempts = $_SESSION['rate_limit'][$key] ?? [];
    $now = time();
    
    // Remove old attempts
    $attempts = array_filter($attempts, function($time) use ($now, $window) {
        return ($now - $time) < $window;
    });
    
    if (count($attempts) >= $limit) {
        return false;
    }
    
    $attempts[] = $now;
    $_SESSION['rate_limit'][$key] = $attempts;
    return true;
}

// Input Sanitization
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    return strlen($password) >= 8 && preg_match('/[A-Za-z]/', $password) && preg_match('/[0-9]/', $password);
}
?>