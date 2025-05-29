<?php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get payment proof for an order
        if (isset($_GET['order_id'])) {
            $orderId = $_GET['order_id'];
            $stmt = $pdo->prepare("SELECT * FROM payment_proof WHERE order_id = ?");
            $stmt->execute([$orderId]);
            $proof = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($proof) {
                echo json_encode(['success' => true, 'data' => $proof]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Payment proof not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing order_id parameter']);
        }
        break;
        
    case 'POST':
        // Handle file upload
        if (isset($_FILES['proof_image']) && isset($_POST['order_id'])) {
            $orderId = $_POST['order_id'];
            
            // Check if order exists
            $checkStmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
            $checkStmt->execute([$orderId]);
            
            if (!$checkStmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Order not found']);
                break;
            }
            
            // Process file upload
            $targetDir = "../uploads/payment_proofs/";
            
            // Create directory if it doesn't exist
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $fileName = time() . '_' . basename($_FILES["proof_image"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            
            // Allow certain file formats
            $allowTypes = array('jpg', 'png', 'jpeg', 'pdf');
            if (in_array($fileType, $allowTypes)) {
                // Upload file to server
                if (move_uploaded_file($_FILES["proof_image"]["tmp_name"], $targetFilePath)) {
                    // Insert file path into database
                    $filePath = 'uploads/payment_proofs/' . $fileName;
                    $stmt = $pdo->prepare("INSERT INTO payment_proof (order_id, proof_image) VALUES (?, ?)");
                    
                    try {
                        $stmt->execute([$orderId, $filePath]);
                        
                        // Update order status to Processing
                        $updateStmt = $pdo->prepare("UPDATE orders SET status = 'Processing' WHERE order_id = ?");
                        $updateStmt->execute([$orderId]);
                        
                        // Add entry to order progress
                        $progressStmt = $pdo->prepare("INSERT INTO order_progress (order_id, status_update) VALUES (?, ?)");
                        $progressStmt->execute([$orderId, 'Payment proof uploaded']);
                        
                        echo json_encode(['success' => true, 'message' => 'Payment proof uploaded successfully']);
                    } catch (PDOException $e) {
                        echo json_encode(['success' => false, 'message' => 'Error saving to database: ' . $e->getMessage()]);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error uploading file']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid file format. Allowed formats: JPG, JPEG, PNG, PDF']);
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