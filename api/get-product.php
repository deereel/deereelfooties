<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../auth/db.php';

header('Content-Type: application/json');

$category = $_GET['category'] ?? '';
$type = $_GET['type'] ?? '';

if (empty($category) || empty($type)) {
    echo json_encode(['success' => false, 'message' => 'Category and type required']);
    exit;
}

try {
    // Map category to database field
    $categoryField = $category === 'shoes' ? 'shoes' : $category;
    
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND type = ? LIMIT 1");
    $stmt->execute([$categoryField, $type]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($product) {
        echo json_encode(['success' => true, 'product' => $product]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>