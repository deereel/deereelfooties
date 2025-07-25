<?php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Handle POST request for creating an order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data from request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Invalid request data']);
        exit;
    }
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Check if orders table exists, if not create it
        try {
            $checkTableStmt = $pdo->prepare("DESCRIBE orders");
            $checkTableStmt->execute();
        } catch (PDOException $e) {
            // Create orders table
            $createTableStmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS orders (
                order_id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NULL,
                customer_name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NULL,
                phone VARCHAR(50) NULL,
                address TEXT NOT NULL,
                city VARCHAR(100) NOT NULL,
                state VARCHAR(100) NOT NULL,
                country VARCHAR(100) NOT NULL,
                subtotal DECIMAL(10,2) NOT NULL,
                shipping DECIMAL(10,2) NOT NULL,
                total DECIMAL(10,2) NOT NULL,
                payment_method VARCHAR(50) NOT NULL,
                status VARCHAR(50) DEFAULT 'Pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            $createTableStmt->execute();
            
            // Create order_items table
            $createItemsTableStmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS order_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_id VARCHAR(50) NOT NULL,
                product_name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                quantity INT NOT NULL,
                color VARCHAR(50) NULL,
                size VARCHAR(20) NULL,
                width VARCHAR(20) NULL,
                image VARCHAR(255) NULL
            )");
            $createItemsTableStmt->execute();
        }
        
        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (
            user_id, customer_name, email, phone, address, city, state, country,
            subtotal, shipping, total, payment_method
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['user_id'] ?? null,
            $data['customer_name'],
            $data['email'] ?? '',
            $data['phone'] ?? '',
            $data['address'],
            $data['city'],
            $data['state'],
            $data['country'],
            $data['subtotal'],
            $data['shipping'],
            $data['total'],
            $data['payment_method']
        ]);
        
        $orderId = $pdo->lastInsertId();
        
        // Insert order items
        $itemStmt = $pdo->prepare("INSERT INTO order_items (
            order_id, product_id, product_name, price, quantity, color, size, width, image
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($data['items'] as $item) {
            // Normalize item data
            $productId = $item['product_id'] ?? $item['id'] ?? '';
            $productName = $item['product_name'] ?? $item['name'] ?? 'Unknown Product';
            $price = floatval($item['price'] ?? 0);
            $quantity = intval($item['quantity'] ?? 1);
            $color = $item['color'] ?? '';
            $size = $item['size'] ?? '';
            $width = $item['width'] ?? '';
            $image = $item['image'] ?? '';
            
            $itemStmt->execute([
                $orderId,
                $productId,
                $productName,
                $price,
                $quantity,
                $color,
                $size,
                $width,
                $image
            ]);
        }
        
        // Create order progress entry
        try {
            $progressStmt = $pdo->prepare("INSERT INTO order_progress (order_id, status_update) VALUES (?, ?)");
            $progressStmt->execute([$orderId, 'Order created']);
        } catch (PDOException $e) {
            // Create order_progress table if it doesn't exist
            $createProgressTableStmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS order_progress (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                status_update VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            $createProgressTableStmt->execute();
            
            // Try again
            $progressStmt = $pdo->prepare("INSERT INTO order_progress (order_id, status_update) VALUES (?, ?)");
            $progressStmt->execute([$orderId, 'Order created']);
        }
        
        // Commit transaction
        $pdo->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Order created successfully',
            'order_id' => $orderId
        ]);
        
    } catch (PDOException $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        echo json_encode([
            'success' => false,
            'message' => 'Error creating order',
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>