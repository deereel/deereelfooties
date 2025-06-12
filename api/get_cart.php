<?php
// api/get_cart.php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Get user ID from query parameter
$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Missing user_id parameter']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ?");
    $stmt->execute([$userId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format items for JavaScript
    $formattedItems = [];
    foreach ($items as $item) {
        $formattedItems[] = [
            'id' => $item['product_id'],
            'name' => $item['product_name'],
            'product_name' => $item['product_name'],
            'price' => (float)$item['price'],
            'color' => $item['color'],
            'size' => $item['size'],
            'material' => $item['material'],
            'width' => $item['width'],
            'quantity' => (int)$item['quantity'],
            'image' => $item['image'],
            'isCustom' => (bool)$item['is_custom']
        ];
    }
    
    echo json_encode(['success' => true, 'items' => $formattedItems]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>