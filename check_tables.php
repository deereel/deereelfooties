<?php
require_once 'auth/db.php';

try {
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_NUM);

    echo "Database Tables:\n";
    foreach($tables as $table) {
        echo "- " . $table[0] . "\n";
    }

    // Check if low_stock_alerts table exists
    $lowStockExists = false;
    foreach($tables as $table) {
        if ($table[0] === 'low_stock_alerts') {
            $lowStockExists = true;
            break;
        }
    }

    if ($lowStockExists) {
        echo "\n✅ low_stock_alerts table exists\n";

        // Check structure
        $stmt = $pdo->query('DESCRIBE low_stock_alerts');
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "Columns:\n";
        foreach($columns as $column) {
            echo "  - {$column['Field']}: {$column['Type']}\n";
        }

        // Check data
        $stmt = $pdo->query('SELECT COUNT(*) FROM low_stock_alerts');
        $count = $stmt->fetchColumn();
        echo "\nRecords in low_stock_alerts: $count\n";

        if ($count > 0) {
            $stmt = $pdo->query('SELECT * FROM low_stock_alerts LIMIT 5');
            $alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "\nSample alerts:\n";
            foreach($alerts as $alert) {
                echo "  - Product ID: {$alert['product_id']}, Status: {$alert['status']}, Stock: {$alert['current_stock']}\n";
            }
        }
    } else {
        echo "\n❌ low_stock_alerts table does NOT exist\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
