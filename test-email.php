<?php
require_once 'auth/email-service-dev.php';

$result = sendEmail('test@example.com', 'Test Email', '<h1>Hello from DRF!</h1><p>Email is working!</p>');

if ($result) {
    echo "✅ Email sent successfully!";
} else {
    echo "❌ Email failed to send";
}
?>