<?php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get orders for a user
        if (isset($_GET['user_id'])) {
            $userId = $_GET['user_id'];
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
            $stmt->execute([$userId]);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'data' => $orders]);
        } 
        // Get a specific order
        elseif (isset($_GET['order_id'])) {
            $orderId = $_GET['order_id'];
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
            $stmt->execute([$orderId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($order) {
                // Get order items
                $itemsStmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
                $itemsStmt->execute([$orderId]);
                $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
                
                $order['items'] = $items;
                
                echo json_encode(['success' => true, 'data' => $order]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Order not found']);
            }
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
        }
        break;
        
    case 'POST':
        // Create a new order
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['user_id']) && isset($data['items']) && isset($data['total'])) {
            try {
                // Start transaction
                $pdo->beginTransaction();
                
                // Create order
                $orderStmt = $pdo->prepare("INSERT INTO orders (user_id, client_name, shipping_address, state, total, payment_status, order_status, created_at) 
                                           VALUES (?, ?, ?, ?, ?, 'pending', 'processing', NOW())");
                
                $userId = $data['user_id'] ?? null;
                $clientName = $data['client_name'];
                $shippingAddress = $data['shipping_address'];
                $state = $data['state'];
                $total = $data['total'];
                
                $orderStmt->execute([$userId, $clientName, $shippingAddress, $state, $total]);
                $orderId = $pdo->lastInsertId();
                
                // Add order items
                $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity, color, size, width) 
                                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                
                foreach ($data['items'] as $item) {
                    $itemStmt->execute([
                        $orderId,
                        $item['id'],
                        $item['name'],
                        $item['price'],
                        $item['quantity'],
                        $item['color'],
                        $item['size'],
                        $item['width']
                    ]);
                }
                
                // If payment proof is provided
                if (isset($data['payment_proof'])) {
                    $paymentStmt = $pdo->prepare("INSERT INTO payment_proofs (order_id, user_id, file_path, created_at) 
                                                VALUES (?, ?, ?, NOW())");
                    $paymentStmt->execute([$orderId, $userId, $data['payment_proof']]);
                    
                    // Update order payment status
                    $updateStmt = $pdo->prepare("UPDATE orders SET payment_status = 'uploaded' WHERE order_id = ?");
                    $updateStmt->execute([$orderId]);
                }
                
                // Commit transaction
                $pdo->commit();
                
                echo json_encode(['success' => true, 'message' => 'Order created successfully', 'order_id' => $orderId]);
            } catch (PDOException $e) {
                // Rollback transaction on error
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'Error creating order: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        }
        break;
        
    case 'PUT':
        // Update order status
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['order_id'])) {
            $orderId = $data['order_id'];
            
            // Build update query dynamically based on provided fields
            $updateFields = [];
            $params = [];
            
            if (isset($data['payment_status'])) {
                $updateFields[] = "payment_status = ?";
                $params[] = $data['payment_status'];
            }
            
            if (isset($data['order_status'])) {
                $updateFields[] = "order_status = ?";
                $params[] = $data['order_status'];
            }
            
            // Add order_id as the last parameter
            $params[] = $orderId;
            
            if (!empty($updateFields)) {
                $sql = "UPDATE orders SET " . implode(", ", $updateFields) . " WHERE order_id = ?";
                $stmt = $pdo->prepare($sql);
                
                try {
                    $stmt->execute($params);
                    echo json_encode(['success' => true, 'message' => 'Order updated successfully']);
                } catch (PDOException $e) {
                    echo json_encode(['success' => false, 'message' => 'Error updating order: ' . $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No fields to update']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing order_id parameter']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>