<?php
session_start();

if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

$permissionMiddleware = new PermissionMiddleware('manage_webhooks');
$permissionMiddleware->handle();

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Filters
$webhookFilter = isset($_GET['webhook_id']) ? (int)$_GET['webhook_id'] : 0;
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

$whereClause = '';
$params = [];

if ($webhookFilter > 0) {
    $whereClause .= ' WHERE wl.webhook_id = ?';
    $params[] = $webhookFilter;
}

if ($statusFilter) {
    $whereClause .= ($whereClause ? ' AND' : ' WHERE') . ' wl.status_code LIKE ?';
    $params[] = $statusFilter . '%';
}

// Get logs
$stmt = $pdo->prepare("SELECT wl.*, w.name as webhook_name, w.url 
                      FROM webhook_logs wl 
                      JOIN webhooks w ON wl.webhook_id = w.id 
                      $whereClause 
                      ORDER BY wl.created_at DESC 
                      LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get webhooks for filter
$stmt = $pdo->prepare("SELECT id, name FROM webhooks ORDER BY name");
$stmt->execute();
$webhooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhook Logs - DRF Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-layout">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <main>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Webhook Logs</h1>
                    <a href="webhook-management.php" class="btn btn-outline-secondary">Back to Webhooks</a>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <select class="form-select" name="webhook_id">
                                    <option value="">All Webhooks</option>
                                    <?php foreach ($webhooks as $webhook): ?>
                                        <option value="<?php echo $webhook['id']; ?>" <?php echo $webhookFilter == $webhook['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($webhook['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" name="status">
                                    <option value="">All Status Codes</option>
                                    <option value="2" <?php echo $statusFilter === '2' ? 'selected' : ''; ?>>2xx Success</option>
                                    <option value="4" <?php echo $statusFilter === '4' ? 'selected' : ''; ?>>4xx Client Error</option>
                                    <option value="5" <?php echo $statusFilter === '5' ? 'selected' : ''; ?>>5xx Server Error</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="webhook-logs.php" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Logs Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Webhook</th>
                                        <th>Event</th>
                                        <th>Status</th>
                                        <th>Attempts</th>
                                        <th>Response Time</th>
                                        <th>Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($log['webhook_name']); ?></strong><br>
                                                <small class="text-muted"><?php echo htmlspecialchars($log['url']); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($log['event_type']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $log['status_code'] < 300 ? 'success' : ($log['status_code'] < 400 ? 'warning' : 'danger'); ?>">
                                                    <?php echo $log['status_code']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $log['attempt_number'] ?? 1; ?></td>
                                            <td><?php echo round($log['response_time_ms']); ?>ms</td>
                                            <td><?php echo date('M d, Y H:i:s', strtotime($log['created_at'])); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-info" onclick="viewDetails(<?php echo $log['id']; ?>)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Log Details Modal -->
    <div class="modal fade" id="logModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Webhook Log Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="logDetails">
                    Loading...
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function viewDetails(logId) {
        fetch(`../api/webhook-log-details.php?id=${logId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('logDetails').innerHTML = `
                    <h6>Payload</h6>
                    <pre>${JSON.stringify(data.payload, null, 2)}</pre>
                    <h6>Response</h6>
                    <pre>${data.response_body}</pre>
                `;
                new bootstrap.Modal(document.getElementById('logModal')).show();
            });
    }
    </script>
</body>
</html>