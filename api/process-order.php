<?php
session_start();
require_once '../auth/db.php';
require_once '../classes/OrderProcessor.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$orderId = $_POST['order_id'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($orderId)) {
    echo json_encode(['success' => false, 'message' => 'Order ID required']);
    exit;
}

try {
    $processor = new OrderProcessor($pdo);
    
    switch ($action) {
        case 'process':
            $processor->processOrder($orderId);
            echo json_encode(['success' => true, 'message' => 'Order processed successfully']);
            break;
            
        case 'update_status':
            $status = $_POST['status'] ?? '';
            if (empty($status)) {
                throw new Exception('Status required');
            }
            $processor->updateOrderStatus($orderId, $status);
            echo json_encode(['success' => true, 'message' => 'Order status updated']);
            break;
            
        default:
            throw new Exception('Invalid action');
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>