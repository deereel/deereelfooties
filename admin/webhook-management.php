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

$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'create_webhook') {
            $name = trim($_POST['name']);
            $url = trim($_POST['url']);
            $events = $_POST['events'] ?? [];
            $secret = bin2hex(random_bytes(16));
            
            $stmt = $pdo->prepare("INSERT INTO webhooks (name, url, events, secret, created_by) VALUES (?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $url, json_encode($events), $secret, $_SESSION['admin_user_id']])) {
                $message = 'Webhook created successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error creating webhook';
                $messageType = 'error';
            }
        } elseif ($_POST['action'] === 'toggle_status') {
            $webhookId = (int)$_POST['webhook_id'];
            $newStatus = $_POST['current_status'] == '1' ? 0 : 1;
            
            $stmt = $pdo->prepare("UPDATE webhooks SET is_active = ? WHERE id = ?");
            if ($stmt->execute([$newStatus, $webhookId])) {
                $message = 'Webhook status updated';
                $messageType = 'success';
            }
        } elseif ($_POST['action'] === 'test_webhook') {
            require_once '../classes/WebhookManager.php';
            $webhookId = (int)$_POST['webhook_id'];
            
            // Get webhook details
            $stmt = $pdo->prepare("SELECT * FROM webhooks WHERE id = ?");
            $stmt->execute([$webhookId]);
            $webhook = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($webhook) {
                $webhookManager = new WebhookManager($pdo);
                $testData = ['test' => true, 'timestamp' => date('c')];
                $webhookManager->triggerEvent('test.event', $testData);
                $message = 'Test webhook sent successfully';
                $messageType = 'success';
            }
        }
    }
}

// Get webhooks
$stmt = $pdo->prepare("SELECT * FROM webhooks ORDER BY created_at DESC");
$stmt->execute();
$webhooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get webhook logs
$stmt = $pdo->prepare("SELECT wl.*, w.name as webhook_name FROM webhook_logs wl 
                      JOIN webhooks w ON wl.webhook_id = w.id 
                      ORDER BY wl.created_at DESC LIMIT 10");
$stmt->execute();
$recentLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhook Management - DRF Admin</title>
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
                    <h1 class="h2">Webhook Management</h1>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h4><?php echo count($webhooks); ?></h4>
                                <p>Total Webhooks</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h4><?php echo count(array_filter($webhooks, fn($w) => $w['is_active'])); ?></h4>
                                <p>Active Webhooks</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Create Webhook -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Create New Webhook</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="create_webhook">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">URL</label>
                                    <input type="url" class="form-control" name="url" required>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Events</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="events[]" value="order.created" id="order_created">
                                            <label class="form-check-label" for="order_created">Order Created</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="events[]" value="order.updated" id="order_updated">
                                            <label class="form-check-label" for="order_updated">Order Updated</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="events[]" value="payment.confirmed" id="payment_confirmed">
                                            <label class="form-check-label" for="payment_confirmed">Payment Confirmed</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Create Webhook</button>
                        </form>
                    </div>
                </div>

                <!-- Webhooks List -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Webhooks</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>URL</th>
                                        <th>Events</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($webhooks as $webhook): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($webhook['name']); ?></td>
                                            <td><code><?php echo htmlspecialchars($webhook['url']); ?></code></td>
                                            <td><?php echo implode(', ', json_decode($webhook['events'] ?? '[]')); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $webhook['is_active'] ? 'success' : 'danger'; ?>">
                                                    <?php echo $webhook['is_active'] ? 'Active' : 'Inactive'; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($webhook['created_at'])); ?></td>
                                            <td>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="action" value="toggle_status">
                                                    <input type="hidden" name="webhook_id" value="<?php echo $webhook['id']; ?>">
                                                    <input type="hidden" name="current_status" value="<?php echo $webhook['is_active']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-<?php echo $webhook['is_active'] ? 'warning' : 'success'; ?>">
                                                        <?php echo $webhook['is_active'] ? 'Disable' : 'Enable'; ?>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Logs -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Recent Webhook Logs</h5>
                        <a href="webhook-logs.php" class="btn btn-sm btn-outline-primary">View All Logs</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Webhook</th>
                                        <th>Event</th>
                                        <th>Status</th>
                                        <th>Attempts</th>
                                        <th>Response Time</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentLogs as $log): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($log['webhook_name']); ?></td>
                                            <td><?php echo htmlspecialchars($log['event_type']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $log['status_code'] < 300 ? 'success' : ($log['status_code'] < 400 ? 'warning' : 'danger'); ?>">
                                                    <?php echo $log['status_code']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $log['attempt_number'] ?? 1; ?></td>
                                            <td><?php echo round($log['response_time_ms']); ?>ms</td>
                                            <td><?php echo date('M d H:i', strtotime($log['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Test Webhook -->
                <div class="card">
                    <div class="card-header">
                        <h5>Test Webhook</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="test_webhook">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-select" name="webhook_id" required>
                                        <option value="">Select Webhook</option>
                                        <?php foreach ($webhooks as $webhook): ?>
                                            <option value="<?php echo $webhook['id']; ?>"><?php echo htmlspecialchars($webhook['name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-info">Send Test Event</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>