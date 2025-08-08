<?php
// EmailJS integration - sends emails via JavaScript
function sendEmail($to, $subject, $body, $type = 'generic', $data = []) {
    session_start();
    $_SESSION['pending_email'] = [
        'to' => $to,
        'subject' => $subject,
        'body' => $body,
        'type' => $type,
        'timestamp' => time()
    ] + $data; // Merge additional data
    session_write_close();
    
    return true;
}

// Specific email functions
function sendPasswordResetEmail($to, $resetLink) {
    return sendEmail($to, 'Password Reset', '', 'password_reset', [
        'reset_link' => $resetLink
    ]);
}

function sendOrderConfirmationEmail($to, $customerName, $orderId, $total, $items, $shippingAddress) {
    return sendEmail($to, 'Order Confirmation', '', 'order_confirmation', [
        'customer_name' => $customerName,
        'order_id' => $orderId,
        'total' => $total,
        'items' => $items,
        'shipping_address' => $shippingAddress
    ]);
}

function getPendingEmail() {
    session_start();
    $email = $_SESSION['pending_email'] ?? null;
    unset($_SESSION['pending_email']);
    session_write_close();
    return $email;
}
?>