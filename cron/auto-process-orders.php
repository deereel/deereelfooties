<?php
// Auto-process orders cron job
require_once '../auth/db.php';
require_once '../classes/OrderProcessor.php';

try {
    $processor = new OrderProcessor($pdo);
    $processor->autoProcessPendingOrders();
    
    echo date('Y-m-d H:i:s') . " - Auto-processed pending orders\n";
    
} catch (Exception $e) {
    error_log("Auto-process orders failed: " . $e->getMessage());
    echo date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n";
}
?>