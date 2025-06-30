<?php
header('Content-Type: application/json');
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';

// Use drf_database connection
$pdo = new PDO("mysql:host=localhost;dbname=drf_database;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

try {
    switch ($method) {
        case 'GET':
            $userId = $_GET['user_id'] ?? null;
            
            if (!$userId) {
                echo json_encode(['success' => false, 'message' => 'User ID required']);
                exit;
            }
            
            $stmt = $pdo->prepare("SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
            $stmt->execute([$userId]);
            $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'addresses' => $addresses]);
            break;
            
        case 'POST':
            $action = $input['action'] ?? 'add';
            
            switch ($action) {
                case 'add':
                    $userId = $input['user_id'];
                    $addressName = $input['address_name'] ?? 'Home';
                    $fullName = $input['full_name'];
                    $phone = $input['phone'];
                    $streetAddress = $input['street_address'];
                    $city = $input['city'];
                    $state = $input['state'];
                    $country = $input['country'];
                    $isDefault = $input['is_default'] ?? false;
                    
                    // If this is set as default, unset other defaults
                    if ($isDefault) {
                        $stmt = $pdo->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
                        $stmt->execute([$userId]);
                    }
                    
                    $stmt = $pdo->prepare("INSERT INTO user_addresses (user_id, address_name, full_name, phone, street_address, city, state, country, is_default, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                    $stmt->execute([$userId, $addressName, $fullName, $phone, $streetAddress, $city, $state, $country, $isDefault]);
                    
                    echo json_encode(['success' => true, 'message' => 'Address saved successfully', 'address_id' => $pdo->lastInsertId()]);
                    break;
                    
                case 'set_default':
                    $userId = $input['user_id'];
                    $addressId = $input['address_id'];
                    
                    // Unset all defaults
                    $stmt = $pdo->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
                    $stmt->execute([$userId]);
                    
                    // Set new default
                    $stmt = $pdo->prepare("UPDATE user_addresses SET is_default = 1 WHERE address_id = ? AND user_id = ?");
                    $stmt->execute([$addressId, $userId]);
                    
                    echo json_encode(['success' => true, 'message' => 'Default address updated']);
                    break;
                    
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid action']);
                    break;
            }
            break;
            
        case 'DELETE':
            $addressId = $input['address_id'];
            $userId = $input['user_id'];
            
            $stmt = $pdo->prepare("DELETE FROM user_addresses WHERE address_id = ? AND user_id = ?");
            $stmt->execute([$addressId, $userId]);
            
            echo json_encode(['success' => true, 'message' => 'Address deleted successfully']);
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
