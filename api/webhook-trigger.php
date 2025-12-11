<?php
require_once '../auth/db.php';
require_once '../classes/WebhookManager.php';

// Example usage - trigger webhooks when orders are created/updated
function triggerOrderWebhook($orderId, $eventType) {
    global $pdo;
    
    // Get order data
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($order) {
        $webhookManager = new WebhookManager($pdo);
        $webhookManager->triggerEvent($eventType, $order);
    }
}

// Example usage - trigger payment confirmation webhook
function triggerPaymentWebhook($orderId) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($order) {
        $webhookManager = new WebhookManager($pdo);
        $webhookManager->triggerEvent('payment.confirmed', $order);
    }
}
?>