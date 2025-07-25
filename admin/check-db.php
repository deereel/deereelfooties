<?php
// Simple script to check database connection
require_once '../auth/db.php';

try {
    // Try a simple query
    $stmt = $pdo->query("SELECT 1");
    echo "Database connection successful!";
    
    // Check if orders table exists
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM orders");
        $count = $stmt->fetchColumn();
        echo "<br>Orders table exists with $count records.";
    } catch (PDOException $e) {
        echo "<br>Orders table does not exist or is not accessible.";
    }
    
    // Check if order_items table exists
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM order_items");
        $count = $stmt->fetchColumn();
        echo "<br>Order_items table exists with $count records.";
    } catch (PDOException $e) {
        echo "<br>Order_items table does not exist or is not accessible.";
    }
    
    // Check if payment_proof table exists
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM payment_proof");
        $count = $stmt->fetchColumn();
        echo "<br>Payment_proof table exists with $count records.";
    } catch (PDOException $e) {
        echo "<br>Payment_proof table does not exist or is not accessible.";
    }
    
    // Check if order_progress table exists
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM order_progress");
        $count = $stmt->fetchColumn();
        echo "<br>Order_progress table exists with $count records.";
    } catch (PDOException $e) {
        echo "<br>Order_progress table does not exist or is not accessible.";
    }
    
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>