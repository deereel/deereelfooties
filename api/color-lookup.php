<?php
// Create color_mappings table if it doesn't exist
if (!function_exists('createColorMappingsTable')) {
    function createColorMappingsTable($pdo) {
        $sql = "CREATE TABLE IF NOT EXISTS color_mappings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            color_name VARCHAR(100) UNIQUE,
            hex_code VARCHAR(7),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $pdo->exec($sql);
    }
}

// Get color hex code using comprehensive color sheet
if (!function_exists('getColorHex')) {
    function getColorHex($colorName, $pdo) {
        $colorLower = strtolower(trim($colorName));
        
        // Load comprehensive color sheet
        $colorSheet = include $_SERVER['DOCUMENT_ROOT'] . '/config/colors.php';
        
        // Check color sheet first
        if (isset($colorSheet[$colorLower])) {
            return $colorSheet[$colorLower];
        }
        
        // Check database for custom colors
        try {
            $stmt = $pdo->prepare("SELECT hex_code FROM color_mappings WHERE color_name = ?");
            $stmt->execute([$colorLower]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $result['hex_code'];
            }
        } catch (Exception $e) {}
        
        return '#666666'; // Default fallback
    }
}
?>