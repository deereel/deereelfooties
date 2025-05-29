<?php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get addresses for a user
        if (isset($_GET['user_id'])) {
            $userId = $_GET['user_id'];
            $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC");
            $stmt->execute([$userId]);
            $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'data' => $addresses]);
        } 
        // Get a specific address
        elseif (isset($_GET['address_id'])) {
            $addressId = $_GET['address_id'];
            $stmt = $pdo->prepare("SELECT * FROM addresses WHERE address_id = ?");
            $stmt->execute([$addressId]);
            $address = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($address) {
                echo json_encode(['success' => true, 'data' => $address]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Address not found']);
            }
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
        }
        break;
        
    case 'POST':
        // Create a new address
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['user_id']) && isset($data['full_name']) && isset($data['street_address']) && 
            isset($data['city']) && isset($data['state']) && isset($data['country'])) {
            
            $userId = $data['user_id'];
            $addressName = $data['address_name'] ?? 'Home';
            $fullName = $data['full_name'];
            $phone = $data['phone'] ?? null;
            $streetAddress = $data['street_address'];
            $city = $data['city'];
            $state = $data['state'];
            $country = $data['country'];
            $isDefault = isset($data['is_default']) ? (bool)$data['is_default'] : false;
            
            // If this is the default address, unset any existing default
            if ($isDefault) {
                $unsetDefault = $pdo->prepare("UPDATE addresses SET is_default = FALSE WHERE user_id = ?");
                $unsetDefault->execute([$userId]);
            }
            
            $stmt = $pdo->prepare("INSERT INTO addresses (user_id, address_name, full_name, phone, street_address, city, state, country, is_default) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            try {
                $stmt->execute([$userId, $addressName, $fullName, $phone, $streetAddress, $city, $state, $country, $isDefault]);
                $addressId = $pdo->lastInsertId();
                
                echo json_encode(['success' => true, 'message' => 'Address created successfully', 'address_id' => $addressId]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Error creating address: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        }
        break;
        
    case 'PUT':
        // Update an address
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['address_id'])) {
            $addressId = $data['address_id'];
            
            // Get the current address to check user_id and default status
            $checkStmt = $pdo->prepare("SELECT user_id, is_default FROM addresses WHERE address_id = ?");
            $checkStmt->execute([$addressId]);
            $currentAddress = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$currentAddress) {
                echo json_encode(['success' => false, 'message' => 'Address not found']);
                break;
            }
            
            $userId = $currentAddress['user_id'];
            
            // Build update query dynamically based on provided fields
            $updateFields = [];
            $params = [];
            
            if (isset($data['address_name'])) {
                $updateFields[] = "address_name = ?";
                $params[] = $data['address_name'];
            }
            
            if (isset($data['full_name'])) {
                $updateFields[] = "full_name = ?";
                $params[] = $data['full_name'];
            }
            
            if (isset($data['phone'])) {
                $updateFields[] = "phone = ?";
                $params[] = $data['phone'];
            }
            
            if (isset($data['street_address'])) {
                $updateFields[] = "street_address = ?";
                $params[] = $data['street_address'];
            }
            
            if (isset($data['city'])) {
                $updateFields[] = "city = ?";
                $params[] = $data['city'];
            }
            
            if (isset($data['state'])) {
                $updateFields[] = "state = ?";
                $params[] = $data['state'];
            }
            
            if (isset($data['country'])) {
                $updateFields[] = "country = ?";
                $params[] = $data['country'];
            }
            
            if (isset($data['is_default'])) {
                $isDefault = (bool)$data['is_default'];
                $updateFields[] = "is_default = ?";
                $params[] = $isDefault;
                
                // If setting as default, unset any existing default
                if ($isDefault) {
                    $unsetDefault = $pdo->prepare("UPDATE addresses SET is_default = FALSE WHERE user_id = ? AND address_id != ?");
                    $unsetDefault->execute([$userId, $addressId]);
                }
            }
            
            // Add address_id as the last parameter
            $params[] = $addressId;
            
            if (!empty($updateFields)) {
                $sql = "UPDATE addresses SET " . implode(", ", $updateFields) . " WHERE address_id = ?";
                $stmt = $pdo->prepare($sql);
                
                try {
                    $stmt->execute($params);
                    echo json_encode(['success' => true, 'message' => 'Address updated successfully']);
                } catch (PDOException $e) {
                    echo json_encode(['success' => false, 'message' => 'Error updating address: ' . $e->getMessage()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No fields to update']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing address_id parameter']);
        }
        break;
        
    case 'DELETE':
        // Delete an address
        if (isset($_GET['address_id'])) {
            $addressId = $_GET['address_id'];
            
            // Check if this is a default address
            $checkStmt = $pdo->prepare("SELECT is_default, user_id FROM addresses WHERE address_id = ?");
            $checkStmt->execute([$addressId]);
            $address = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$address) {
                echo json_encode(['success' => false, 'message' => 'Address not found']);
                break;
            }
            
            $stmt = $pdo->prepare("DELETE FROM addresses WHERE address_id = ?");
            
            try {
                $stmt->execute([$addressId]);
                
                // If this was the default address, set another address as default
                if ($address['is_default']) {
                    $userId = $address['user_id'];
                    $newDefaultStmt = $pdo->prepare("UPDATE addresses SET is_default = TRUE WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
                    $newDefaultStmt->execute([$userId]);
                }
                
                echo json_encode(['success' => true, 'message' => 'Address deleted successfully']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Error deleting address: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing address_id parameter']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>