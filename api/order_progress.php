<?php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get progress updates for an order
        if (isset($_GET['order_id'])) {
            $orderId = $_GET['order_id'];
            $stmt = $pdo->prepare("SELECT * FROM order_progress WHERE order_id = ? ORDER BY update_date DESC");
            $stmt->execute([$orderId]);
            $progress = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'data' => $progress]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing order_id parameter']);
        }
        break;
        
    case 'POST':
        // Add a new progress update
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['order_id']) && isset($data['status_update'])) {
            $orderId = $data['order_id'];
            $statusUpdate = $data['status_update'];
            
            // Check if order exists
            $checkStmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
            $checkStmt->execute([$orderId]);
            
            if (!$checkStmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Order not found']);
                break;
            }
            
            $stmt = $pdo->prepare("INSERT INTO order_progress (order_id, status_update) VALUES (?, ?)");
            
            try {
                $stmt->execute([$orderId, $statusUpdate]);
                echo json_encode(['success' => true, 'message' => 'Progress update added successfully']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Error adding progress update: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>