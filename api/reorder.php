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
    // Verify order belongs to user
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
    
    if (empty($orderItems)) {
        echo json_encode(['error' => 'No items found in this order']);
        exit;
    }
    
    // Store reorder items in session for cart to pick up
    $_SESSION['reorder_items'] = [];
    foreach ($orderItems as $item) {
        $_SESSION['reorder_items'][] = [
            'product_id' => $item['product_id'],
            'product_name' => $item['product_name'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'color' => $item['color'] ?? $item['selected_color'] ?? '',
            'size' => $item['size'] ?? $item['selected_size'] ?? '',
            'image' => $item['image'] ?? $item['product_image'] ?? ''
        ];
    }
    
    $addedItems = count($orderItems);
    
    echo json_encode([
        'success' => true,
        'message' => $addedItems . ' items added to cart',
        'items_count' => $addedItems
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>