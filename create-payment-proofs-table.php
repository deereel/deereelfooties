<?php
require_once 'auth/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS payment_proofs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        user_id INT NOT NULL,
        file_path VARCHAR(255) NOT NULL,
        original_filename VARCHAR(255) NOT NULL,
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
        admin_notes TEXT,
        verified_at TIMESTAMP NULL,
        verified_by INT NULL,
        INDEX idx_order_id (order_id),
        INDEX idx_user_id (user_id),
        INDEX idx_status (status)
    )";
    
    $pdo->exec($sql);
    echo "Payment proofs table created successfully!";
    
} catch (Exception $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>