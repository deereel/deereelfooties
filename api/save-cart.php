<?php
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST method allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['user_id']) || !isset($input['cart_data'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $pdo = getDBConnection();
    
    $userId = $input['user_id'];
    $cartData = json_encode($input['cart_data']);
    
    // Delete existing saved cart
    $stmt = $pdo->prepare("DELETE FROM saved_carts WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    // Save new cart data
    $stmt = $pdo->prepare("INSERT INTO saved_carts (user_id, cart_data, saved_at) VALUES (?, ?, NOW())");
    $stmt->execute([$userId, $cartData]);
    
    echo json_encode(['success' => true, 'message' => 'Cart saved successfully']);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>