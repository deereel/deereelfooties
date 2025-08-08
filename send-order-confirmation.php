<?php
// Example usage for order confirmation
require_once 'auth/email-service-js.php';

// Example order data
$customerEmail = 'biodunoladayo@gmail.com';
$customerName = 'John Doe';
$orderId = '12345';
$total = '45,000';
$items = 'Black Oxford Shoes (Size 42) x1, Brown Loafers (Size 41) x1';
$shippingAddress = '123 Lagos Street, Victoria Island, Lagos, Nigeria';

// Send order confirmation
$result = sendOrderConfirmationEmail(
    $customerEmail,
    $customerName, 
    $orderId,
    $total,
    $items,
    $shippingAddress
);

if ($result) {
    echo "✅ Order confirmation email queued";
} else {
    echo "❌ Failed to queue email";
}
?>

<!DOCTYPE html>
<html>
<head><title>Order Confirmation Test</title></head>
<body>
    <h2>Order Confirmation Email Test</h2>
    <!-- EmailJS -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <script src="/js/emailjs-service.js"></script>
</body>
</html>