<?php
session_start();
require_once '../auth/db.php';
require_once '../classes/PaymentGateway.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$orderId = $input['order_id'] ?? null;

if (!$orderId) {
    echo json_encode(['success' => false, 'message' => 'Order ID required']);
    exit;
}

try {
    // Get order details
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Order not found']);
        exit;
    }
    
    $gateway = new PaymentGateway();
    $callback_url = $_SERVER['HTTP_HOST'] . '/api/payment-callback.php';
    
    $result = $gateway->initializePayment(
        $order['customer_email'],
        $order['total'] ?? $order['subtotal'],
        $orderId,
        'https://' . $callback_url
    );
    
    if ($result['status']) {
        echo json_encode([
            'success' => true,
            'authorization_url' => $result['data']['authorization_url'],
            'reference' => $result['data']['reference']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Payment initialization failed']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>