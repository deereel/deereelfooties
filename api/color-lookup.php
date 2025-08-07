<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';

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

// Ensure table exists
createColorMappingsTable($pdo);

// Get color hex code with API fallback
if (!function_exists('getColorHex')) {
    function getColorHex($colorName, $pdo) {
        $colorLower = strtolower(trim($colorName));
        
        // Check database first
        try {
            $stmt = $pdo->prepare("SELECT hex_code FROM color_mappings WHERE color_name = ?");
            $stmt->execute([$colorLower]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $result['hex_code'];
            }
        } catch (Exception $e) {}
        
        // Try API lookup for new colors
        $apiHex = lookupColorFromAPI($colorName);
        if ($apiHex) {
            // Save to database for future use
            try {
                $stmt = $pdo->prepare("INSERT IGNORE INTO color_mappings (color_name, hex_code) VALUES (?, ?)");
                $stmt->execute([$colorLower, $apiHex]);
            } catch (Exception $e) {}
            return $apiHex;
        }
        
        return '#666666'; // Default fallback
    }
}

// API lookup function
if (!function_exists('lookupColorFromAPI')) {
    function lookupColorFromAPI($colorName) {
        try {
            $url = "https://www.thecolorapi.com/id?name=" . urlencode($colorName);
            $response = @file_get_contents($url);
            
            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['hex']['value'])) {
                    return $data['hex']['value'];
                }
            }
        } catch (Exception $e) {}
        
        return null;
    }
}
?>