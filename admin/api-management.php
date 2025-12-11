<?php
session_start();

if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

$permissionMiddleware = new PermissionMiddleware('manage_api');
$permissionMiddleware->handle();

$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'create_key') {
            $keyName = trim($_POST['key_name']);
            $permissions = $_POST['permissions'] ?? [];
            $apiKey = 'drf_' . bin2hex(random_bytes(16));
            
            $stmt = $pdo->prepare("INSERT INTO api_keys (key_name, api_key, permissions, created_by) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$keyName, $apiKey, json_encode($permissions), $_SESSION['admin_user_id']])) {
                $message = 'API key created successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error creating API key';
                $messageType = 'error';
            }
        } elseif ($_POST['action'] === 'toggle_status') {
            $keyId = (int)$_POST['key_id'];
            $newStatus = $_POST['current_status'] == '1' ? 0 : 1;
            
            $stmt = $pdo->prepare("UPDATE api_keys SET is_active = ? WHERE id = ?");
            if ($stmt->execute([$newStatus, $keyId])) {
                $message = 'API key status updated';
                $messageType = 'success';
            }
        }
    }
}

// Get API keys
$stmt = $pdo->prepare("SELECT * FROM api_keys ORDER BY created_at DESC");
$stmt->execute();
$apiKeys = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get API usage stats
$stmt = $pdo->prepare("SELECT COUNT(*) as total_requests, DATE(created_at) as date FROM api_usage WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(created_at) ORDER BY date");
$stmt->execute();
$usageStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Management - DRF Admin</title>
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
                    <h1 class="h2">API Management</h1>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- API Usage Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h4><?php echo count($apiKeys); ?></h4>
                                <p>Total API Keys</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h4><?php echo array_sum(array_column($usageStats, 'total_requests')); ?></h4>
                                <p>Requests (7 days)</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <h4><?php echo count(array_filter($apiKeys, fn($k) => $k['is_active'])); ?></h4>
                                <p>Active Keys</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <h4><?php 
                                $stmt = $pdo->prepare("SELECT COUNT(*) FROM api_usage WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)");
                                $stmt->execute();
                                echo $stmt->fetchColumn();
                                ?></h4>
                                <p>Today's Requests</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Usage Chart -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>API Usage (Last 7 Days)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="usageChart" width="400" height="100"></canvas>
                    </div>
                </div>

                <!-- Create API Key -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Create New API Key</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="create_key">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Key Name</label>
                                    <input type="text" class="form-control" name="key_name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Permissions</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="read" id="perm_read">
                                        <label class="form-check-label" for="perm_read">Read</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="write" id="perm_write">
                                        <label class="form-check-label" for="perm_write">Write</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Create API Key</button>
                        </form>
                    </div>
                </div>

                <!-- API Keys List -->
                <div class="card">
                    <div class="card-header">
                        <h5>API Keys</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>API Key</th>
                                        <th>Permissions</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($apiKeys as $key): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($key['key_name']); ?></td>
                                            <td><code><?php echo substr($key['api_key'], 0, 20) . '...'; ?></code></td>
                                            <td><?php echo implode(', ', json_decode($key['permissions'] ?? '[]')); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $key['is_active'] ? 'success' : 'danger'; ?>">
                                                    <?php echo $key['is_active'] ? 'Active' : 'Inactive'; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($key['created_at'])); ?></td>
                                            <td>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="action" value="toggle_status">
                                                    <input type="hidden" name="key_id" value="<?php echo $key['id']; ?>">
                                                    <input type="hidden" name="current_status" value="<?php echo $key['is_active']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-<?php echo $key['is_active'] ? 'warning' : 'success'; ?>">
                                                        <?php echo $key['is_active'] ? 'Disable' : 'Enable'; ?>
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
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Usage Chart
    const ctx = document.getElementById('usageChart').getContext('2d');
    const usageData = <?php echo json_encode($usageStats); ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: usageData.map(d => d.date),
            datasets: [{
                label: 'API Requests',
                data: usageData.map(d => d.total_requests),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
</body>
</html>