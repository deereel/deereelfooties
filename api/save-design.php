<?php
require_once '../auth/db.php';
require_once '../auth/auth.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to save designs']);
    exit;
}

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['design_name']) || !isset($data['design_data'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$userId = $_SESSION['user_id'];
$designName = $data['design_name'];
$designData = json_encode($data['design_data']);

try {
    // Check if we're updating an existing design
    $designId = isset($data['design_id']) ? intval($data['design_id']) : 0;
    
    if ($designId > 0) {
        // Update existing design
        $stmt = $pdo->prepare("UPDATE saved_designs SET design_name = ?, design_data = ? WHERE design_id = ? AND user_id = ?");
        $stmt->execute([$designName, $designData, $designId, $userId]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Design updated successfully', 'design_id' => $designId]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Design not found or you do not have permission to update it']);
        }
    } else {
        // Create new design
        $stmt = $pdo->prepare("INSERT INTO saved_designs (user_id, design_name, design_data) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $designName, $designData]);
        
        $designId = $pdo->lastInsertId();
        echo json_encode(['success' => true, 'message' => 'Design saved successfully', 'design_id' => $designId]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>