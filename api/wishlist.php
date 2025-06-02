<?php
// api/wishlist.php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Create wishlist_items table if it doesn't exist
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS wishlist_items (
        wishlist_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        product_id VARCHAR(255) NOT NULL,
        product_name VARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error creating wishlist table: ' . $e->getMessage()]);
    exit;
}

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get wishlist items
        $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
        
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Missing user_id parameter']);
            exit;
        }
        
        try {
            $stmt = $pdo->prepare("SELECT * FROM wishlist_items WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$userId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'items' => $items]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        break;
        
    case 'POST':
        // Add item to wishlist
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['user_id']) || !isset($data['product_id']) || !isset($data['product_name']) || !isset($data['price'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }
        
        try {
            // Check if item already exists
            $stmt = $pdo->prepare("SELECT wishlist_id FROM wishlist_items WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$data['user_id'], $data['product_id']]);
            
            if ($stmt->fetch()) {
                echo json_encode(['success' => true, 'message' => 'Item already in wishlist']);
                exit;
            }
            
            // Add to wishlist
            $stmt = $pdo->prepare("INSERT INTO wishlist_items (user_id, product_id, product_name, price, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['user_id'],
                $data['product_id'],
                $data['product_name'],
                $data['price'],
                $data['image'] ?? ''
            ]);
            
            echo json_encode(['success' => true, 'message' => 'Item added to wishlist']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        break;
        
    case 'DELETE':
        // Remove item from wishlist
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['user_id']) || !isset($data['wishlist_id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }
        
        try {
            $stmt = $pdo->prepare("DELETE FROM wishlist_items WHERE wishlist_id = ? AND user_id = ?");
            $stmt->execute([$data['wishlist_id'], $data['user_id']]);
            
            echo json_encode(['success' => true, 'message' => 'Item removed from wishlist']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>
