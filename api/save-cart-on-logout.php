<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

session_start();
require_once '../auth/db.php';

try {
    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        throw new Exception('User not logged in');
    }

    $user = $_SESSION['user'];
    $user_id = $user['id'];

    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['cartItems']) || !is_array($input['cartItems'])) {
        throw new Exception('Invalid cart data');
    }

    $cartItems = $input['cartItems'];
    
    // Convert cart items to JSON
    $cartData = json_encode($cartItems);

    // Check if user already has a saved cart
    $checkStmt = $pdo->prepare("SELECT id FROM saved_carts WHERE user_id = ?");
    $checkStmt->execute([$user_id]);
    $existingCart = $checkStmt->fetch();

    if ($existingCart) {
        // Update existing cart
        $updateStmt = $pdo->prepare("
            UPDATE saved_carts 
            SET cart_data = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE user_id = ?
        ");
        $updateStmt->execute([$cartData, $user_id]);
        $message = 'Cart updated successfully';
    } else {
        // Insert new cart
        $insertStmt = $pdo->prepare("
            INSERT INTO saved_carts (user_id, cart_data) 
            VALUES (?, ?)
        ");
        $insertStmt->execute([$user_id, $cartData]);
        $message = 'Cart saved successfully';
    }

    echo json_encode([
        'success' => true,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>