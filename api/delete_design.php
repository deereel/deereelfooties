<?php
// Create this file at: api/delete_design.php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get data from request
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['user_id']) || !isset($data['design_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$userId = $data['user_id'];
$designId = $data['design_id'];

try {
    // Delete design
    $stmt = $pdo->prepare("DELETE FROM saved_designs WHERE design_id = ? AND user_id = ?");
    $stmt->execute([$designId, $userId]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Design deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Design not found or you do not have permission to delete it']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error deleting design: ' . $e->getMessage()]);
}
?>
