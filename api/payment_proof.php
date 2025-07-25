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
            $userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;
            $customerName = isset($_POST['customer_name']) ? $_POST['customer_name'] : 'Guest';
            
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
            if (in_array(strtolower($fileType), $allowTypes)) {
                // Upload file to server
                if (move_uploaded_file($_FILES["proof_image"]["tmp_name"], $targetFilePath)) {
                    // Insert file path into database
                    $filePath = 'uploads/payment_proofs/' . $fileName;
                    
                    // Check if payment_proof table has the necessary columns
                    try {
                        // Try to get table structure
                        $tableCheckStmt = $pdo->prepare("DESCRIBE payment_proof");
                        $tableCheckStmt->execute();
                        $columns = $tableCheckStmt->fetchAll(PDO::FETCH_COLUMN);
                        
                        // Check if user_id and customer_name columns exist
                        $hasUserIdColumn = in_array('user_id', $columns);
                        $hasCustomerNameColumn = in_array('customer_name', $columns);
                        
                        // If columns don't exist, alter table to add them
                        if (!$hasUserIdColumn) {
                            $alterStmt = $pdo->prepare("ALTER TABLE payment_proof ADD COLUMN user_id INT NULL");
                            $alterStmt->execute();
                        }
                        
                        if (!$hasCustomerNameColumn) {
                            $alterStmt = $pdo->prepare("ALTER TABLE payment_proof ADD COLUMN customer_name VARCHAR(255) NULL");
                            $alterStmt->execute();
                        }
                    } catch (PDOException $e) {
                        // If table doesn't exist, create it
                        $createTableStmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS payment_proof (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            order_id INT NOT NULL,
                            proof_image VARCHAR(255) NOT NULL,
                            user_id INT NULL,
                            customer_name VARCHAR(255) NULL,
                            uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        )");
                        $createTableStmt->execute();
                    }
                    
                    // Insert payment proof with user information
                    $stmt = $pdo->prepare("INSERT INTO payment_proof (order_id, proof_image, user_id, customer_name) VALUES (?, ?, ?, ?)");
                    
                    try {
                        $stmt->execute([$orderId, $filePath, $userId, $customerName]);
                        
                        // Update order status to Processing
                        $updateStmt = $pdo->prepare("UPDATE orders SET status = 'Processing' WHERE order_id = ?");
                        $updateStmt->execute([$orderId]);
                        
                        // Check if order_progress table exists
                        try {
                            $progressStmt = $pdo->prepare("INSERT INTO order_progress (order_id, status_update) VALUES (?, ?)");
                            $progressStmt->execute([$orderId, 'Payment proof uploaded by ' . $customerName]);
                        } catch (PDOException $e) {
                            // If table doesn't exist, create it
                            $createProgressTableStmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS order_progress (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                order_id INT NOT NULL,
                                status_update VARCHAR(255) NOT NULL,
                                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                            )");
                            $createProgressTableStmt->execute();
                            
                            // Try again
                            $progressStmt = $pdo->prepare("INSERT INTO order_progress (order_id, status_update) VALUES (?, ?)");
                            $progressStmt->execute([$orderId, 'Payment proof uploaded by ' . $customerName]);
                        }
                        
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