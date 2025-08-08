<?php
session_start();
require_once '../auth/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) && !isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$userId = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? null;
$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    echo json_encode(['error' => 'Order ID required']);
    exit;
}

try {
    // Get order details
    $orderStmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
    $orderStmt->execute([$orderId, $userId]);
    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        echo json_encode(['error' => 'Order not found']);
        exit;
    }
    
    // Get order items
    $itemsStmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $itemsStmt->execute([$orderId]);
    $orderItems = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get order status history
    $historyStmt = $pdo->prepare("SELECT * FROM order_status_history WHERE order_id = ? ORDER BY created_at ASC");
    $historyStmt->execute([$orderId]);
    $statusHistory = $historyStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get shipping address
    $shippingAddress = null;
    if (!empty($order['address_id'])) {
        try {
            $addressStmt = $pdo->prepare("SELECT * FROM user_addresses WHERE id = ?");
            $addressStmt->execute([$order['address_id']]);
            $shippingAddress = $addressStmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Address table might not exist
        }
    }
    
    // If no address_id, get shipping info from order fields
    if (!$shippingAddress) {
        $shippingAddress = [
            'name' => $order['customer_name'] ?? null,
            'phone' => $order['phone'] ?? null,
            'street' => $order['address'] ?? null,
            'city' => $order['city'] ?? null,
            'state' => $order['state'] ?? null,
            'zip' => null,
            'country' => $order['country'] ?? null
        ];
    }
    
    // Get user information for customer details
    $userInfo = null;
    if (!empty($order['user_id'])) {
        try {
            $userStmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
            $userStmt->execute([$order['user_id']]);
            $userInfo = $userStmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // User table might not exist
        }
    }
    
    echo json_encode([
        'success' => true,
        'order' => $order,
        'items' => $orderItems,
        'history' => $statusHistory,
        'shipping_address' => $shippingAddress,
        'shipping_method' => $order['shipping_method'] ?? 'Standard Shipping',
        'tracking_number' => $order['tracking_number'] ?? null,
        'user' => $userInfo
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>