<?php
session_start();

require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if admin is logged in and has permission
try {
    $permissionMiddleware = new PermissionMiddleware('view_activity_logs');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    header('Location: login.php');
    exit;
}

// Handle filters
$filters = [];
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $filters['user_id'] = $_GET['user_id'];
}
if (isset($_GET['module']) && !empty($_GET['module'])) {
    $filters['module'] = $_GET['module'];
}
if (isset($_GET['action']) && !empty($_GET['action'])) {
    $filters['action'] = $_GET['action'];
}
if (isset($_GET['entity_type']) && !empty($_GET['entity_type'])) {
    $filters['entity_type'] = $_GET['entity_type'];
}
if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
    $filters['date_from'] = $_GET['date_from'] . ' 00:00:00';
}
if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
    $filters['date_to'] = $_GET['date_to'] . ' 23:59:59';
}

// Handle delete action
$message = '';
$messageType = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_logs'])) {
    try {
        $deletePermission = new PermissionMiddleware('manage_activity_logs');
        $deletePermission->handle();
    } catch (Exception $e) {
        $message = 'You do not have permission to delete activity logs.';
        $messageType = 'danger';
    }

    if (empty($message)) {
        $deleteDateFrom = $_POST['delete_date_from'] ?? '';
        $deleteDateTo = $_POST['delete_date_to'] ?? '';

        if (empty($deleteDateFrom) || empty($deleteDateTo)) {
            $message = 'Please select both start and end dates for deletion.';
            $messageType = 'warning';
        } else {
            $deleteFilters = [];
            $deleteFilters['date_from'] = $deleteDateFrom . ' 00:00:00';
            $deleteFilters['date_to'] = $deleteDateTo . ' 23:59:59';

            // Count logs to be deleted
            $countSql = "SELECT COUNT(*) as count FROM activity_logs WHERE created_at >= :date_from AND created_at <= :date_to";
            $countStmt = $pdo->prepare($countSql);
            $countStmt->execute([
                ':date_from' => $deleteFilters['date_from'],
                ':date_to' => $deleteFilters['date_to']
            ]);
            $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
            $count = $countResult['count'] ?? 0;

            if ($count > 0) {
                // Delete the logs
                $pdo->prepare("DELETE FROM activity_logs WHERE created_at >= ? AND created_at <= ?")
                    ->execute([$deleteFilters['date_from'], $deleteFilters['date_to']]);

                $message = "Successfully deleted $count activity logs from $deleteDateFrom to $deleteDateTo.";
                $messageType = 'success';

                // Log the deletion
                logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'delete_activity_logs', 'activity_log', 'delete', null, "Deleted $count activity logs from $deleteDateFrom to $deleteDateTo");
            } else {
                $message = 'No activity logs found in the selected date range.';
                $messageType = 'info';
            }
        }
    }
}

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Get activity logs
$activityLogs = getActivityLogs($filters, $limit, $offset);

// Log activity logs viewing
logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'view_activity_logs', 'activity_log', 'read', null, 'Viewed activity logs');

// Get statistics
$stats = getActivityStats('today');

// Get filter options
$users = fetchData('admin_users', [], 'id, username, first_name, last_name', 'username ASC');
$modules = fetchData('activity_logs', [], 'DISTINCT module', 'module ASC');
$actions = fetchData('activity_logs', [], 'DISTINCT action', 'action ASC');
$entityTypes = fetchData('activity_logs', [], 'DISTINCT entity_type', 'entity_type ASC');

// Get total count for pagination
$totalLogs = count(fetchData('activity_logs', $filters, 'id'));
$totalPages = ceil($totalLogs / $limit);

// Export functionality
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="activity_logs_' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Date', 'User', 'Action', 'Module', 'Entity Type', 'Entity ID', 'IP Address']);

    $exportLogs = getActivityLogs($filters, 1000, 0); // Export up to 1000 records
    foreach ($exportLogs as $log) {
        fputcsv($output, [
            $log['created_at'],
            $log['username'],
            $log['action'],
            $log['module'],
            $log['entity_type'],
            $log['entity_id'],
            $log['ip_address']
        ]);
    }
    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - DRF Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css">
    <style>
        .activity-card { transition: transform 0.2s; }
        .activity-card:hover { transform: translateY(-2px); }
        .log-entry { font-family: 'Courier New', monospace; font-size: 0.875rem; }
        .filter-section { background: #f8f9fa; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; }
        .stats-card { transition: transform 0.2s; }
        .stats-card:hover { transform: translateY(-2px); }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="admin-layout">
        <?php include 'includes/sidebar.php'; ?>

        <div class="main-content">
            <main>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="bi bi-journal-text me-2"></i>
                        Activity Logs
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="?export=csv<?php echo http_build_query(array_merge($_GET, ['page' => null])); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download me-1"></i>Export CSV
                            </a>
                            <button class="btn btn-sm btn-outline-secondary" onclick="refreshLogs()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card stats-card border-primary">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1 text-primary"><?php echo number_format($stats['total_activities']); ?></div>
                                <small class="text-muted">Total Activities</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card stats-card border-info">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1 text-info"><?php echo number_format($stats['unique_users']); ?></div>
                                <small class="text-muted">Active Users</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card stats-card border-success">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1 text-success"><?php echo number_format($stats['create_actions']); ?></div>
                                <small class="text-muted">Create Actions</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card stats-card border-warning">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1 text-warning"><?php echo number_format($stats['update_actions']); ?></div>
                                <small class="text-muted">Update Actions</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card stats-card border-danger">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1 text-danger"><?php echo number_format($stats['delete_actions']); ?></div>
                                <small class="text-muted">Delete Actions</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card stats-card border-secondary">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1 text-secondary"><?php echo number_format($stats['view_actions']); ?></div>
                                <small class="text-muted">View Actions</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filter-section">
                    <form method="GET" class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">User</label>
                            <select class="form-select form-select-sm" name="user_id">
                                <option value="">All Users</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?php echo $user['id']; ?>" <?php echo (isset($_GET['user_id']) && $_GET['user_id'] == $user['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($user['username']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Module</label>
                            <select class="form-select form-select-sm" name="module">
                                <option value="">All Modules</option>
                                <?php foreach ($modules as $module): ?>
                                    <option value="<?php echo $module['module']; ?>" <?php echo (isset($_GET['module']) && $_GET['module'] == $module['module']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($module['module']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Action</label>
                            <select class="form-select form-select-sm" name="action">
                                <option value="">All Actions</option>
                                <?php foreach ($actions as $action): ?>
                                    <option value="<?php echo $action['action']; ?>" <?php echo (isset($_GET['action']) && $_GET['action'] == $action['action']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($action['action']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Entity Type</label>
                            <select class="form-select form-select-sm" name="entity_type">
                                <option value="">All Types</option>
                                <?php foreach ($entityTypes as $type): ?>
                                    <option value="<?php echo $type['entity_type']; ?>" <?php echo (isset($_GET['entity_type']) && $_GET['entity_type'] == $type['entity_type']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($type['entity_type']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" class="form-control form-control-sm" name="date_from" value="<?php echo $_GET['date_from'] ?? ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" class="form-control form-control-sm" name="date_to" value="<?php echo $_GET['date_to'] ?? ''; ?>">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-sm me-2">
                                <i class="bi bi-search me-1"></i>Filter
                            </button>
                            <a href="activity-logs.php" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-x-circle me-1"></i>Clear Filters
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Delete Logs Section -->
                <div class="filter-section">
                    <h6 class="mb-3">
                        <i class="bi bi-trash me-2"></i>Delete Activity Logs by Date Range
                    </h6>
                    <form method="POST" class="row g-3" onsubmit="return confirmDelete()">
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control form-control-sm" name="delete_date_from" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control form-control-sm" name="delete_date_to" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" name="delete_logs" class="btn btn-danger btn-sm me-2">
                                    <i class="bi bi-trash me-1"></i>Delete Logs
                                </button>
                                <small class="text-muted">This action cannot be undone.</small>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Activity Logs Table -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-list-ul me-2"></i>
                            Activity Logs
                            <span class="badge bg-secondary ms-2"><?php echo number_format($totalLogs); ?> total</span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($activityLogs)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-journal-x display-4 text-muted"></i>
                                <p class="mt-2 text-muted">No activity logs found matching your criteria.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date/Time</th>
                                            <th>User</th>
                                            <th>Action</th>
                                            <th>Module</th>
                                            <th>Entity</th>
                                            <th>IP Address</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($activityLogs as $log): ?>
                                            <tr>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo date('M d, Y', strtotime($log['created_at'])); ?><br>
                                                        <?php echo date('H:i:s', strtotime($log['created_at'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($log['username']); ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php
                                                        echo strpos($log['action'], 'create') !== false ? 'success' :
                                                             (strpos($log['action'], 'update') !== false ? 'warning' :
                                                             (strpos($log['action'], 'delete') !== false ? 'danger' : 'info'));
                                                    ?>">
                                                        <?php echo htmlspecialchars($log['action']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <?php echo htmlspecialchars($log['module']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($log['entity_type'] && $log['entity_id']): ?>
                                                        <small>
                                                            <?php echo htmlspecialchars($log['entity_type']); ?> #<?php echo $log['entity_id']; ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small><?php echo htmlspecialchars($log['ip_address']); ?></small>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-info" onclick="showLogDetails(<?php echo $log['id']; ?>)">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if ($totalPages > 1): ?>
                                <div class="card-footer">
                                    <nav>
                                        <ul class="pagination pagination-sm justify-content-center mb-0">
                                            <?php if ($page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo http_build_query(array_merge($_GET, ['page' => null])); ?>">
                                                        <i class="bi bi-chevron-left"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo http_build_query(array_merge($_GET, ['page' => null])); ?>">
                                                        <?php echo $i; ?>
                                                    </a>
                                                </li>
                                            <?php endfor; ?>

                                            <?php if ($page < $totalPages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo http_build_query(array_merge($_GET, ['page' => null])); ?>">
                                                        <i class="bi bi-chevron-right"></i>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Log Details Modal -->
    <div class="modal fade" id="logDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Activity Log Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="logDetailsContent">
                    <!-- Details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function refreshLogs() {
            location.reload();
        }

        function showLogDetails(logId) {
            // This would typically fetch details via AJAX
            // For now, we'll show a placeholder
            const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
            document.getElementById('logDetailsContent').innerHTML = `
                <div class="text-center">
                    <i class="bi bi-info-circle display-4 text-info"></i>
                    <p class="mt-2">Detailed log information would be loaded here via AJAX.</p>
                    <p class="text-muted">Log ID: ${logId}</p>
                </div>
            `;
            modal.show();
        }

        // Auto-refresh every 5 minutes
        setInterval(refreshLogs, 300000);
    </script>
</body>
</html>
