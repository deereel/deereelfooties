<?php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get user information
        if (isset($_GET['user_id'])) {
            $userId = $_GET['user_id'];
            $stmt = $pdo->prepare("SELECT user_id, name, email, phone, gender, created_at FROM users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                echo json_encode(['success' => true, 'data' => $user]);
            } else {
                echo json_encode(['success' => false, 'message' => 'User not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing user_id parameter']);
        }
        break;
        
    case 'PUT':
        // Update user information
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['user_id'])) {
            $userId = $data['user_id'];
            
            // Build update query dynamically based on provided fields
            $updateFields = [];
            $params = [];
            
            if (isset($data['name'])) {
                $updateFields[] = "name = ?";
                $params[] = $data['name'];
            }
            
            if (isset($data['phone'])) {
                $updateFields[] = "phone = ?";
                $params[] = $data['phone'];
            }
            
            if (isset($data['gender'])) {
                $updateFields[] = "gender = ?";
                $params[] = $data['gender'];
            }
            
            // Add user_id as the last parameter
            $params[] = $userId;
            
            if (!empty($updateFields)) {
                $sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE user_id = ?";
                $stmt = $pdo->prepare($sql);
                
                try {
                    $stmt->execute($params);
                    echo json_encode(['success' => true, 'message' => 'User information updated successfully']);
                } catch (PDOException $e) {
                    echo json_encode(['success' => false, 'message' => 'Error updating user: ' . $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No fields to update']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing user_id parameter']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>