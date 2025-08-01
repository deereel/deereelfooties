<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/color-lookup.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['colors']) || empty($input['colors'])) {
    echo json_encode(['success' => false, 'message' => 'No colors provided']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=drf_database;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    createColorMappingsTable($pdo);
    
    $colors = explode(',', $input['colors']);
    $populated = [];
    
    foreach ($colors as $color) {
        $color = trim($color);
        if (!empty($color)) {
            $hex = getColorHex($color, $pdo);
            $populated[] = ['color' => $color, 'hex' => $hex];
        }
    }
    
    echo json_encode(['success' => true, 'colors' => $populated]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>