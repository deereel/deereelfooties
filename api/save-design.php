<?php
// Create this file at: api/save-design.php
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
if (!isset($data['user_id']) || !isset($data['design'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$userId = $data['user_id'];
$design = $data['design'];

// Create saved_designs table if it doesn't exist
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS saved_designs (
        design_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        design_data TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error creating table: ' . $e->getMessage()]);
    exit;
}

// Insert design into database
try {
    $stmt = $pdo->prepare("INSERT INTO saved_designs (user_id, design_data) VALUES (?, ?)");
    $stmt->execute([$userId, json_encode($design)]);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Design saved successfully',
        'design_id' => $pdo->lastInsertId()
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error saving design: ' . $e->getMessage()]);
}
?>
