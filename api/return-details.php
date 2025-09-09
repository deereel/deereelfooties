<?php
session_start();
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check permissions
try {
    $permissionMiddleware = new PermissionMiddleware('view_returns');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    http_response_code(403);
    echo json_encode(['error' => 'Permission denied']);
    exit;
}

header('Content-Type: application/json');

if (!isset($_GET['return_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Return ID is required']);
    exit;
}

$returnId = intval($_GET['return_id']);

try {
    // Get return details
    $stmt = $pdo->prepare("
        SELECT r.*, o.order_number, c.username as customer_name, c.email as customer_email
        FROM returns r
        LEFT JOIN orders o ON r.order_id = o.id
        LEFT JOIN customers c ON r.customer_id = c.id
        WHERE r.id = ?
    ");
    $stmt->execute([$returnId]);
    $return = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$return) {
        http_response_code(404);
        echo json_encode(['error' => 'Return not found']);
        exit;
    }

    // Get return items
    $stmt = $pdo->prepare("
        SELECT ri.*, p.name as product_name, p.sku
        FROM return_items ri
        LEFT JOIN products p ON ri.product_id = p.id
        WHERE ri.return_id = ?
    ");
    $stmt->execute([$returnId]);
    $returnItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get refund history
    $stmt = $pdo->prepare("
        SELECT r.*, au.username as processed_by_name
        FROM refunds r
        LEFT JOIN admin_users au ON r.processed_by = au.id
        WHERE r.return_id = ?
        ORDER BY r.processed_at DESC
    ");
    $stmt->execute([$returnId]);
    $refunds = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the response
    $response = [
        'id' => $return['id'],
        'order_id' => $return['order_id'],
        'order_number' => $return['order_number'],
        'customer_id' => $return['customer_id'],
        'customer_name' => $return['customer_name'],
        'customer_email' => $return['customer_email'],
        'reason' => $return['reason'],
        'notes' => $return['notes'],
        'status' => $return['status'],
        'created_at' => $return['created_at'],
        'updated_at' => $return['updated_at'],
        'items' => array_map(function($item) {
            return [
                'id' => $item['id'],
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'sku' => $item['sku'],
                'quantity' => $item['quantity'],
                'reason' => $item['reason'],
                'condition' => $item['condition']
            ];
        }, $returnItems),
        'refunds' => array_map(function($refund) {
            return [
                'id' => $refund['id'],
                'amount' => $refund['amount'],
                'method' => $refund['method'],
                'processed_by' => $refund['processed_by'],
                'processed_by_name' => $refund['processed_by_name'],
                'processed_at' => $refund['processed_at'],
                'reference_number' => $refund['reference_number'],
                'notes' => $refund['notes']
            ];
        }, $refunds)
    ];

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
