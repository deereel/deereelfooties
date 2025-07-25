<?php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

try {
    // Check if the user_addresses table exists
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'user_addresses'");
    $stmt->execute();
    $tableExists = $stmt->rowCount() > 0;
    
    $response = ['success' => true, 'table_exists' => $tableExists];
    
    if ($tableExists) {
        // Count addresses
        $countStmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_addresses");
        $countStmt->execute();
        $count = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];
        $response['address_count'] = $count;
        
        // Get sample addresses (limit to 5)
        $sampleStmt = $pdo->prepare("SELECT * FROM user_addresses LIMIT 5");
        $sampleStmt->execute();
        $samples = $sampleStmt->fetchAll(PDO::FETCH_ASSOC);
        $response['sample_addresses'] = $samples;
        
        // Check table structure
        $structureStmt = $pdo->prepare("DESCRIBE user_addresses");
        $structureStmt->execute();
        $structure = $structureStmt->fetchAll(PDO::FETCH_ASSOC);
        $response['table_structure'] = $structure;
    }
    
    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>