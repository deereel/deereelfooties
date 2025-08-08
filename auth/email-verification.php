<?php
session_start();
require_once 'db.php';
require_once 'security.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!checkRateLimit('email_verification', 3, 3600)) {
        echo json_encode(['success' => false, 'message' => 'Too many verification attempts']);
        exit;
    }
    
    $email = sanitizeInput($_POST['email'] ?? '');
    
    if (!validateEmail($email)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? AND email_verified = 0");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
            
            $stmt = $pdo->prepare("INSERT INTO email_verifications (user_id, token, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expires_at = ?");
            $stmt->execute([$user['user_id'], $token, $expires, $token, $expires]);
            
            // Send verification email
            require_once 'email-service-dev.php';
            $verifyLink = "https://yoursite.com/verify-email.php?token=" . $token;
            $emailBody = "<p>Welcome to DeeReel Footies! Click the link below to verify your email:</p><p><a href='$verifyLink'>Verify Email</a></p>";
            sendEmail($email, "Verify Your Email - DeeReel Footies", $emailBody);
        }
        
        echo json_encode(['success' => true, 'message' => 'Verification email sent if account exists']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Server error']);
    }
}
?>