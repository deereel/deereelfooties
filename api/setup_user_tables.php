<?php
require_once '../auth/db.php';

// Add phone column to users table if it doesn't exist
try {
    // Check if column exists first to avoid errors
    $stmt = $pdo->prepare("SHOW COLUMNS FROM users LIKE 'phone'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        // Column doesn't exist, so add it
        $pdo->exec("ALTER TABLE users ADD COLUMN phone VARCHAR(20) DEFAULT NULL");
        echo "Phone column added to users table<br>";
    } else {
        echo "Phone column already exists in users table<br>";
    }
} catch (PDOException $e) {
    echo "Error modifying users table: " . $e->getMessage() . "<br>";
}

// Create addresses table if it doesn't exist
$addressesTable = "
CREATE TABLE IF NOT EXISTS addresses (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_name VARCHAR(50) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    street_address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    country VARCHAR(50) NOT NULL,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)";

try {
    $pdo->exec($addressesTable);
    echo "Addresses table created successfully<br>";
} catch (PDOException $e) {
    echo "Error creating addresses table: " . $e->getMessage() . "<br>";
}

echo "Setup completed";
?>