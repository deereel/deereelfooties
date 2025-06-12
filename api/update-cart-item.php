<?php
// api/update-cart-item.php
require_once '../auth/db.php';
require_once '../auth/get_user.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get current user
$user = getCurrentUser();
if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

// Get data from request
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['item_id']) || !isset($data['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$itemId = $data['item_id'];
$quantity = intval($data['quantity']);

// Validate quantity
if ($quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'Quantity must be at least 1']);
    exit;
}

try {
    // Update cart item
    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE item_id = ? AND user_id = ?");
    $stmt->execute([$quantity, $itemId, $user['id']]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Cart item updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cart item not found or not owned by current user']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error updating cart item: ' . $e->getMessage()]);
}
?>