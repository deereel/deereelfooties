<?php
require_once '../auth/db.php';

// Check if the user_addresses table exists
try {
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'user_addresses'");
    $stmt->execute();
    $tableExists = $stmt->rowCount() > 0;
    
    if (!$tableExists) {
        // Create the user_addresses table if it doesn't exist
        $createTableSQL = "
        CREATE TABLE IF NOT EXISTS user_addresses (
          address_id INT AUTO_INCREMENT PRIMARY KEY,
          user_id INT NOT NULL,
          address_name VARCHAR(100) NOT NULL DEFAULT 'Home',
          full_name VARCHAR(255) NOT NULL,
          phone VARCHAR(20) NOT NULL,
          street_address TEXT NOT NULL,
          city VARCHAR(100) NOT NULL,
          state VARCHAR(100) NOT NULL,
          country VARCHAR(100) NOT NULL DEFAULT 'Nigeria',
          is_default BOOLEAN DEFAULT FALSE,
          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          INDEX idx_user_id (user_id),
          INDEX idx_default (user_id, is_default)
        )";
        
        $pdo->exec($createTableSQL);
        echo "Created user_addresses table.<br>";
    } else {
        echo "user_addresses table already exists.<br>";
    }
    
    // Check if the addresses table exists (old table name)
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'addresses'");
    $stmt->execute();
    $oldTableExists = $stmt->rowCount() > 0;
    
    if ($oldTableExists) {
        // Copy data from addresses to user_addresses if needed
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM user_addresses");
        $stmt->execute();
        $userAddressCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        if ($userAddressCount == 0) {
            // No data in user_addresses, copy from addresses
            $pdo->exec("INSERT INTO user_addresses (user_id, address_name, full_name, phone, street_address, city, state, country, is_default)
                       SELECT user_id, 'Home', full_name, phone, street_address, city, state, country, is_default
                       FROM addresses");
            
            echo "Copied data from addresses to user_addresses table.<br>";
        }
    }
    
    echo "Database check completed successfully.";
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>