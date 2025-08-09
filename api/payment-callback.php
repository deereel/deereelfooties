<?php
require_once '../auth/db.php';
require_once '../classes/PaymentGateway.php';

$reference = $_GET['reference'] ?? null;

if (!$reference) {
    header('Location: /checkout.php?error=invalid_reference');
    exit;
}

try {
    $gateway = new PaymentGateway();
    $result = $gateway->verifyPayment($reference);
    
    if ($result['status'] && $result['data']['status'] === 'success') {
        $orderId = $result['data']['metadata']['order_id'];
        
        // Update order payment status
        $stmt = $pdo->prepare("UPDATE orders SET payment_confirmed = 1, payment_reference = ?, status = 'Processing' WHERE order_id = ?");
        $stmt->execute([$reference, $orderId]);
        
        // Add status history
        $historyStmt = $pdo->prepare("INSERT INTO order_status_history (order_id, status) VALUES (?, 'Processing')");
        $historyStmt->execute([$orderId]);
        
        header('Location: /order-success.php?order_id=' . $orderId);
    } else {
        header('Location: /checkout.php?error=payment_failed');
    }
    
} catch (Exception $e) {
    header('Location: /checkout.php?error=verification_failed');
}
?>