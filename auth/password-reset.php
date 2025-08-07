<?php
session_start();
require_once 'db.php';
require_once 'security.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!checkRateLimit('password_reset', 3, 3600)) {
        echo json_encode(['success' => false, 'message' => 'Too many reset attempts. Try again later.']);
        exit;
    }
    
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }
    
    $email = sanitizeInput($_POST['email'] ?? '');
    
    if (!validateEmail($email)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expires_at = ?");
            $stmt->execute([$user['user_id'], $token, $expires, $token, $expires]);
            
            // Send email
            require_once 'email-service.php';
            $resetLink = "https://yoursite.com/reset-password.php?token=" . $token;
            $emailBody = "<p>Click the link below to reset your password:</p><p><a href='$resetLink'>Reset Password</a></p><p>This link expires in 1 hour.</p>";
            sendEmail($email, "Password Reset - DeeReel Footies", $emailBody);
        }
        
        echo json_encode(['success' => true, 'message' => 'If email exists, reset link sent']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }
}
?>