<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../auth/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to delete designs']);
    exit;
}

$userId = $_SESSION['user']['id'] ?? $_SESSION['user_id'];

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['design_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$designId = intval($data['design_id']);

try {
    $stmt = $pdo->prepare("DELETE FROM saved_designs WHERE design_id = ? AND user_id = ?");
    $stmt->execute([$designId, $userId]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Design deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Design not found or you do not have permission to delete it']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>