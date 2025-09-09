<?php
session_start();
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check permissions
try {
    $permissionMiddleware = new PermissionMiddleware('view_order_automation');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

header('Content-Type: application/json');

if (!isset($_GET['rule_id']) || empty($_GET['rule_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Rule ID is required']);
    exit;
}

$ruleId = intval($_GET['rule_id']);

try {
    $stmt = $pdo->prepare("SELECT * FROM order_automation_rules WHERE id = ?");
    $stmt->execute([$ruleId]);
    $rule = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$rule) {
        http_response_code(404);
        echo json_encode(['error' => 'Automation rule not found']);
        exit;
    }

    // Return the rule data
    echo json_encode([
        'id' => $rule['id'],
        'rule_name' => $rule['rule_name'],
        'description' => $rule['description'],
        'trigger_event' => $rule['trigger_event'],
        'actions' => $rule['actions'],
        'is_active' => (bool)$rule['is_active'],
        'priority' => $rule['priority'],
        'created_at' => $rule['created_at'],
        'updated_at' => $rule['updated_at']
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch automation rule details: ' . $e->getMessage()]);
}
?>
