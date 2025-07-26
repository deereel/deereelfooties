<?php
require_once '../auth/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS saved_designs (
        design_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        design_name VARCHAR(255) NOT NULL,
        design_data TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    )";
    
    $pdo->exec($sql);
    echo "Saved designs table created successfully";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>