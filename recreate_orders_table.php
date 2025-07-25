<?php
// Script to recreate the orders table

// Include database connection
require_once 'auth/db.php';

// Set headers for plain text output
header('Content-Type: text/plain');

try {
    // Read the SQL file
    $sql = file_get_contents('sql/recreate_orders_table.sql');
    
    // Split SQL file into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    // Execute each statement
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
            echo "Executed: " . substr($statement, 0, 50) . "...\n";
        }
    }
    
    echo "\nOrders table recreated successfully!";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>