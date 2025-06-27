<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user']['id'];

try {
    // Get cart data from request
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['cart_data'])) {
        echo json_encode(['success' => false, 'error' => 'No cart data provided']);
        exit;
    }
    
    $cartData = json_encode($data['cart_data']);
    
    // Connect to database
    $pdo = new PDO("mysql:host=localhost;dbname=drf_database;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Check if saved_carts table exists, create if not
    $stmt = $pdo->query("SHOW TABLES LIKE 'saved_carts'");
    if ($stmt->rowCount() == 0) {
        $createTable = "CREATE TABLE saved_carts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            cart_data JSON NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_user_cart (user_id)
        )";
        $pdo->exec($createTable);
    }
    
    // Insert or update saved cart
    $stmt = $pdo->prepare("
        INSERT INTO saved_carts (user_id, cart_data) 
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE cart_data = ?, updated_at = NOW()
    ");
    
    $stmt->execute([$userId, $cartData, $cartData]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
}
?>