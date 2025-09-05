<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Include database connection and middleware
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if user has permission to delete products
try {
    $permissionMiddleware = new PermissionMiddleware('delete_products');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Permission denied: You are not authorized to delete products.']);
    exit;
}

// Set headers for JSON response
header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$productId = isset($input['id']) ? (int)$input['id'] : 0;

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

try {
    // Check if product exists
    $checkStmt = $pdo->prepare("SELECT product_id FROM products WHERE product_id = ?");
    $checkStmt->execute([$productId]);
    $product = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    // Delete the product
    $deleteStmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $deleteStmt->execute([$productId]);

    // Log the activity
    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'delete_product', 'products', 'product', $productId);

    echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>