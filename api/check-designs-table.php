<?php
require_once '../auth/db.php';

try {
    $stmt = $pdo->query("DESCRIBE saved_designs");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Table structure:\n";
    foreach ($columns as $column) {
        echo $column['Field'] . " - " . $column['Type'] . "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>