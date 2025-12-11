<?php
require_once '../auth/db.php';

$logId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($logId > 0) {
    $stmt = $pdo->prepare("SELECT payload, response_body FROM webhook_logs WHERE id = ?");
    $stmt->execute([$logId]);
    $log = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($log) {
        header('Content-Type: application/json');
        echo json_encode([
            'payload' => json_decode($log['payload'], true),
            'response_body' => $log['response_body']
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Log not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid log ID']);
}
?>