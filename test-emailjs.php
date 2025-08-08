<?php
require_once 'auth/email-service-js.php';

// Test email
$result = sendEmail('your-email@gmail.com', 'EmailJS Test', '<h1>Testing EmailJS!</h1><p>If you receive this, EmailJS is working!</p>');

if ($result) {
    echo "✅ Email queued for sending via EmailJS<br>";
    echo "Check browser console and your email inbox<br>";
} else {
    echo "❌ Failed to queue email";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>EmailJS Test</title>
</head>
<body>
    <h2>EmailJS Test</h2>
    <p>Check console (F12) for EmailJS status</p>
    
    <!-- EmailJS -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <script src="/js/emailjs-service.js"></script>
</body>
</html>