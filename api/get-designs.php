<?php
// Create this file at: api/get-designs.php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get user ID from request
$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Missing user_id parameter']);
    exit;
}

try {
    // Check if table exists
    $tableExists = false;
    $tables = $pdo->query("SHOW TABLES LIKE 'saved_designs'")->fetchAll();
    if (count($tables) > 0) {
        $tableExists = true;
    }
    
    if (!$tableExists) {
        // Create table if it doesn't exist
        $pdo->exec("CREATE TABLE IF NOT EXISTS saved_designs (
            design_id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            design_data TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }
    
    // Get designs
    $stmt = $pdo->prepare("SELECT * FROM saved_designs WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    $designs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'designs' => $designs]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
