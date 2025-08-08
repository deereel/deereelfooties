<?php
require_once 'auth/email-service-js.php';

// Test password reset email
$result = sendPasswordResetEmail('biodunoladayo@gmail.com', 'http://localhost/drf/reset-password.php?token=test123');

if ($result) {
    echo "✅ Password reset email queued<br>";
    echo "Check console and email inbox<br>";
} else {
    echo "❌ Failed to queue email";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Test</title>
</head>
<body>
    <h2>Password Reset Test</h2>
    <p>Check console (F12) for EmailJS status</p>
    
    <!-- EmailJS -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <script src="/js/emailjs-service.js"></script>
</body>
</html>