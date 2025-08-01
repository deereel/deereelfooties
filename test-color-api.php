<?php
require_once 'api/color-lookup.php';
require_once 'auth/db.php';

// Test the color API
$pdo = new PDO("mysql:host=localhost;dbname=drf_database;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

createColorMappingsTable($pdo);

$testColors = ['sage', 'coral', 'dusty rose', 'burnt sienna'];

echo "<h2>Color API Test</h2>";
foreach ($testColors as $color) {
    $hex = getColorHex($color, $pdo);
    echo "<div style='display:inline-block; margin:10px; padding:10px; background-color:$hex; color:white;'>$color: $hex</div>";
}

// Check database
echo "<h2>Database Contents</h2>";
$stmt = $pdo->query("SELECT * FROM color_mappings ORDER BY created_at DESC LIMIT 10");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<div style='display:inline-block; margin:5px; padding:5px; background-color:{$row['hex_code']}; color:white;'>{$row['color_name']}: {$row['hex_code']}</div>";
}
?>