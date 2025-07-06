<?php
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';

// Start session if needed and close immediately to release lock
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_write_close();

// Use the auth database connection (drf_database)
if (!function_exists('getDBConnection')) {
    function getDBConnection() {
        $host = 'localhost';
        $db   = 'drf_database';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            return new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

try {
    $pdo = getDBConnection();
    
    switch ($method) {
        case 'GET':
            $userId = $_GET['user_id'] ?? null;
            $action = $_GET['action'] ?? 'get';
            
            error_log("Cart GET - User ID: " . $userId);
            
            if (!$userId) {
                echo json_encode(['success' => false, 'message' => 'User ID required']);
                exit;
            }
            
            $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE user_id = ? ORDER BY added_at DESC");
            $stmt->execute([$userId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Cart GET - Found " . count($items) . " items for user " . $userId);
            
            echo json_encode(['success' => true, 'items' => $items]);
            break;
            
        case 'POST':
            $action = $input['action'] ?? 'add';
            
            switch ($action) {
                case 'add':
                    $userId = $input['user_id'];
                    $productId = $input['product_id'];
                    $productName = $input['product_name'];
                    $price = $input['price'];
                    $image = $input['image'] ?? '';
                    $color = $input['color'];
                    $size = $input['size'];
                    $width = $input['width'];
                    $quantity = $input['quantity'];
                    
                    // Check if item already exists
                    $stmt = $pdo->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE user_id = ? AND product_id = ? AND color = ? AND size = ? AND width = ?");
                    $stmt->execute([$userId, $productId, $color, $size, $width]);
                    $existing = $stmt->fetch();
                    
                    if ($existing) {
                        // Update quantity
                        $newQuantity = $existing['quantity'] + $quantity;
                        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
                        $stmt->execute([$newQuantity, $existing['cart_item_id']]);
                    } else {
                        // Insert new item
                        $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, product_name, price, image, color, size, width, quantity, added_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                        $stmt->execute([$userId, $productId, $productName, $price, $image, $color, $size, $width, $quantity]);
                    }
                    
                    echo json_encode(['success' => true, 'message' => 'Item added to cart']);
                    break;
                    
                case 'merge':
                    $userId = $input['user_id'];
                    $guestCart = $input['guest_cart'];
                    
                    error_log("Cart merge - User ID: " . $userId);
                    error_log("Cart merge - Guest cart: " . json_encode($guestCart));
                    
                    if (empty($guestCart)) {
                        echo json_encode(['success' => true, 'message' => 'No items to merge']);
                        break;
                    }
                    
                    foreach ($guestCart as $item) {
                        error_log("Processing cart item: " . json_encode($item));
                        
                        // Check if item already exists
                        $stmt = $pdo->prepare("SELECT cart_item_id, quantity FROM cart_items WHERE user_id = ? AND product_id = ? AND color = ? AND size = ? AND width = ?");
                        $stmt->execute([$userId, $item['product_id'], $item['color'], $item['size'], $item['width']]);
                        $existing = $stmt->fetch();
                        
                        error_log("Existing item found: " . ($existing ? 'Yes' : 'No'));
                        
                        if ($existing) {
                            // Update quantity
                            $newQuantity = $existing['quantity'] + $item['quantity'];
                            $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
                            $stmt->execute([$newQuantity, $existing['cart_item_id']]);
                            error_log("Updated existing item quantity to: " . $newQuantity);
                        } else {
                            // Insert new item
                            $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, product_name, price, image, color, size, width, quantity, added_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                            $stmt->execute([
                                $userId, 
                                $item['product_id'], 
                                $item['product_name'], 
                                $item['price'], 
                                $item['image'], 
                                $item['color'], 
                                $item['size'], 
                                $item['width'], 
                                $item['quantity']
                            ]);
                            error_log("Inserted new cart item with ID: " . $pdo->lastInsertId());
                        }
                    }
                    
                    echo json_encode(['success' => true, 'message' => 'Guest cart merged successfully']);
                    break;
                    
                case 'update':
                    $cartItemId = $input['cart_item_id'];
                    $quantity = $input['quantity'];
                    $userId = $input['user_id'];
                    
                    if ($quantity <= 0) {
                        // Remove item if quantity is 0 or less
                        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_item_id = ? AND user_id = ?");
                        $stmt->execute([$cartItemId, $userId]);
                    } else {
                        // Update quantity
                        $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ? AND user_id = ?");
                        $stmt->execute([$quantity, $cartItemId, $userId]);
                    }
                    
                    echo json_encode(['success' => true, 'message' => 'Cart updated successfully']);
                    break;
                    
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid action']);
                    break;
            }
            break;
            
        case 'DELETE':
            $action = $input['action'] ?? 'clear';
            
            switch ($action) {
                case 'clear':
                    $userId = $input['user_id'];
                    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
                    $stmt->execute([$userId]);
                    echo json_encode(['success' => true, 'message' => 'Cart cleared successfully']);
                    break;
                    
                case 'remove':
                    $cartItemId = $input['cart_item_id'];
                    $userId = $input['user_id'];
                    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_item_id = ? AND user_id = ?");
                    $stmt->execute([$cartItemId, $userId]);
                    echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
                    break;
                    
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid delete action']);
                    break;
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            break;
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>