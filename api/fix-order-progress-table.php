<?php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

try {
    // Check if order_progress table exists
    $checkTableStmt = $pdo->prepare("SHOW TABLES LIKE 'order_progress'");
    $checkTableStmt->execute();
    
    if ($checkTableStmt->rowCount() > 0) {
        // Check if update_date column exists
        $checkColumnStmt = $pdo->prepare("SHOW COLUMNS FROM order_progress LIKE 'update_date'");
        $checkColumnStmt->execute();
        
        if ($checkColumnStmt->rowCount() > 0) {
            // Rename update_date column to created_at
            $renameColumnStmt = $pdo->prepare("ALTER TABLE order_progress CHANGE update_date created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
            $renameColumnStmt->execute();
            echo json_encode(['success' => true, 'message' => 'Column renamed from update_date to created_at']);
        } else {
            // Check if created_at column exists
            $checkCreatedAtStmt = $pdo->prepare("SHOW COLUMNS FROM order_progress LIKE 'created_at'");
            $checkCreatedAtStmt->execute();
            
            if ($checkCreatedAtStmt->rowCount() === 0) {
                // Add created_at column
                $addColumnStmt = $pdo->prepare("ALTER TABLE order_progress ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
                $addColumnStmt->execute();
                echo json_encode(['success' => true, 'message' => 'Added created_at column']);
            } else {
                echo json_encode(['success' => true, 'message' => 'created_at column already exists']);
            }
        }
    } else {
        echo json_encode(['success' => true, 'message' => 'order_progress table does not exist']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>