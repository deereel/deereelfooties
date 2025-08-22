<?php
header('Content-Type: application/json');

// Handle POST request for creating an order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON data from request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Invalid request data']);
        exit;
    }
    
    // Enhanced validation
    $required_fields = ['customer_name', 'address', 'city', 'state', 'country', 'items', 'subtotal', 'total'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || (is_string($data[$field]) && trim($data[$field]) === '')) {
            echo json_encode(['success' => false, 'message' => "Missing required field: {$field}"]);
            exit;
        }
    }
    
    // Validate items array
    if (!is_array($data['items']) || empty($data['items'])) {
        echo json_encode(['success' => false, 'message' => 'Order must contain at least one item']);
        exit;
    }
    
    // Validate numeric fields
    if (!is_numeric($data['subtotal']) || !is_numeric($data['total'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid numeric values for subtotal or total']);
        exit;
    }
    
    try {
        require_once '../auth/db.php';
        
        // Set default order status
        $orderStatus = 'Pending';
        
        // Start transaction
        $pdo->beginTransaction();
        
        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (
            user_id, customer_name, email, phone, address, city, state, country,
            subtotal, shipping, total, payment_method, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
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
            $data['shipping'] ?? 0,
            $data['total'],
            $data['payment_method'] ?? 'bank_transfer',
            $orderStatus
        ]);
        
        $orderId = $pdo->lastInsertId();
        
        // Insert order items and update inventory
        $itemStmt = $pdo->prepare("INSERT INTO order_items (
            order_id, product_id, product_name, price, quantity, color, size, width, image
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($data['items'] as $item) {
            $productId = $item['product_id'] ?? $item['id'] ?? '';
            $quantity = intval($item['quantity'] ?? 1);
            
            $itemStmt->execute([
                $orderId,
                $productId,
                $item['product_name'] ?? $item['name'] ?? 'Unknown Product',
                floatval($item['price'] ?? 0),
                $quantity,
                $item['color'] ?? '',
                $item['size'] ?? '',
                $item['width'] ?? '',
                $item['image'] ?? ''
            ]);
            
            // Note: Inventory will be updated when payment is confirmed, not at order placement
        }
        
        // Add initial order status tracking
        try {
            $statusStmt = $pdo->prepare("INSERT INTO order_progress (order_id, status_update) VALUES (?, ?)");
            $statusStmt->execute([$orderId, 'Order created and pending payment']);
        } catch (Exception $statusError) {
            // Create table if it doesn't exist
            try {
                $createTableStmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS order_progress (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    order_id INT NOT NULL,
                    status_update VARCHAR(255) NOT NULL,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )");
                $createTableStmt->execute();
                
                // Try again
                $statusStmt = $pdo->prepare("INSERT INTO order_progress (order_id, status_update) VALUES (?, ?)");
                $statusStmt->execute([$orderId, 'Order created and pending payment']);
            } catch (Exception $createError) {
                // Log error but don't fail the order
                error_log("Order progress tracking error: " . $createError->getMessage());
            }
        }
        
        // Commit transaction
        $pdo->commit();
        
        // Send basic email notification (non-blocking)
        if (!empty($data['email'])) {
            try {
                $to = $data['email'];
                $subject = "Order Confirmation - Order #{$orderId}";
                $message = "Dear {$data['customer_name']},\n\n";
                $message .= "Thank you for your order! Your order #{$orderId} has been received.\n\n";
                $message .= "Order Total: ₦" . number_format($data['total'], 2) . "\n\n";
                $message .= "We will process your order once payment is confirmed.\n\n";
                $message .= "Best regards,\nDeeReel Footies Team";
                
                $headers = "From: noreply@deereelfooties.com\r\n";
                $headers .= "Reply-To: support@deereelfooties.com\r\n";
                
                // Send email (non-blocking - don't fail if email fails)
                @mail($to, $subject, $message, $headers);
            } catch (Exception $emailError) {
                // Log error but don't fail the order
                error_log("Email notification error: " . $emailError->getMessage());
            }
        }
        
        // Clear user's cart after successful order
        if (!empty($data['user_id'])) {
            try {
                $clearCartStmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
                $clearCartStmt->execute([$data['user_id']]);
            } catch (Exception $cartError) {
                // Log error but don't fail the order
                error_log("Cart clearing error: " . $cartError->getMessage());
            }
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Order created successfully',
            'order_id' => $orderId,
            'status' => $orderStatus
        ]);
        
    } catch (Exception $e) {
        if (isset($pdo)) {
            $pdo->rollBack();
        }
        echo json_encode([
            'success' => false,
            'message' => 'Error creating order: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>