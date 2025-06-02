<?php
// api/sync_cart.php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get data from request
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['user_id']) || !isset($data['cart_items'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$userId = $data['user_id'];
$cartItems = $data['cart_items'];

try {
    // Begin transaction
    $pdo->beginTransaction();
    
    // Delete existing cart items for this user
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    // Insert new cart items
    if (!empty($cartItems)) {
        $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, product_name, price, color, size, material, width, quantity, image, is_custom) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($cartItems as $item) {
            $stmt->execute([
                $userId,
                $item['id'],
                $item['name'],
                $item['price'],
                $item['color'] ?? '',
                $item['size'] ?? '',
                $item['material'] ?? '',
                $item['width'] ?? '',
                $item['quantity'] ?? 1,
                $item['image'] ?? '',
                $item['isCustom'] ? 1 : 0
            ]);
        }
    }
    
    // Commit transaction
    $pdo->commit();
    
    echo json_encode(['success' => true, 'message' => 'Cart synced successfully']);
} catch (PDOException $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error syncing cart: ' . $e->getMessage()]);
}
?>
