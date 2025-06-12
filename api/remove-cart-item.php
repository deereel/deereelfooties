<?php
// api/remove-cart-item.php
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
if (!isset($data['item_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing item_id field']);
    exit;
}

$itemId = $data['item_id'];

try {
    // Delete cart item
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE item_id = ? AND user_id = ?");
    $stmt->execute([$itemId, $user['id']]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Cart item removed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cart item not found or not owned by current user']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error removing cart item: ' . $e->getMessage()]);
}
?>