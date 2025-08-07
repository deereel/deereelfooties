<?php
require_once 'auth/db.php';

// Create color_mappings table
$sql = "CREATE TABLE IF NOT EXISTS color_mappings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    color_name VARCHAR(100) UNIQUE,
    hex_code VARCHAR(7),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$pdo->exec($sql);

// Load colors from colors.php
$colors = include 'config/colors.php';

$inserted = 0;
$skipped = 0;

foreach ($colors as $colorName => $hexCode) {
    try {
        $stmt = $pdo->prepare("INSERT IGNORE INTO color_mappings (color_name, hex_code) VALUES (?, ?)");
        $result = $stmt->execute([strtolower($colorName), $hexCode]);
        
        if ($stmt->rowCount() > 0) {
            $inserted++;
        } else {
            $skipped++;
        }
    } catch (Exception $e) {
        echo "Error inserting $colorName: " . $e->getMessage() . "\n";
    }
}

echo "Colors populated successfully!\n";
echo "Inserted: $inserted colors\n";
echo "Skipped (already exists): $skipped colors\n";
?>