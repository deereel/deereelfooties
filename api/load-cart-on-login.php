<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

session_start();
require_once '../auth/db.php';

try {
    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        throw new Exception('User not logged in');
    }

    $user = $_SESSION['user'];
    $user_id = $user['id'];

    // Get saved cart for user
    $stmt = $pdo->prepare("
        SELECT cart_data, updated_at 
        FROM saved_carts 
        WHERE user_id = ?
    ");
    $stmt->execute([$user_id]);
    $savedCart = $stmt->fetch();

    if ($savedCart) {
        $cartData = json_decode($savedCart['cart_data'], true);
        
        echo json_encode([
            'success' => true,
            'cart_data' => $cartData,
            'last_updated' => $savedCart['updated_at']
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'cart_data' => [],
            'message' => 'No saved cart found'
        ]);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>