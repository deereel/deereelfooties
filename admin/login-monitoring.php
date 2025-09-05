<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection and middleware
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if user has permission to view login monitoring
try {
    $permissionMiddleware = new PermissionMiddleware('view_login_monitoring');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    header('Location: login.php');
    exit;
}

// Get login monitoring data
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 25;
$offset = ($page - 1) * $limit;

// Filter parameters
$usernameFilter = isset($_GET['username']) ? $_GET['username'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$ipFilter = isset($_GET['ip']) ? $_GET['ip'] : '';
$dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// Build WHERE clause
$whereClause = '';
$params = [];
$conditions = [];

if (!empty($usernameFilter)) {
    $conditions[] = "username LIKE ?";
    $params[] = "%$usernameFilter%";
}

if (!empty($statusFilter)) {
    $conditions[] = "status = ?";
    $params[] = $statusFilter;
}

if (!empty($ipFilter)) {
    $conditions[] = "ip_address LIKE ?";
    $params[] = "%$ipFilter%";
}

if (!empty($dateFrom)) {
    $conditions[] = "DATE(attempt_time) >= ?";
    $params[] = $dateFrom;
}

if (!empty($dateTo)) {
    $conditions[] = "DATE(attempt_time) <= ?";
    $params[] = $dateTo;
}

if (!empty($conditions)) {
    $whereClause = " WHERE " . implode(" AND ", $conditions);
}

// Count total login attempts for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM login_attempts" . $whereClause);
$countStmt->execute($params);
$totalAttempts = $countStmt->fetchColumn();
$totalPages = ceil($totalAttempts / $limit);

// Get login attempts with pagination
$orderBy = " ORDER BY attempt_time DESC LIMIT $limit OFFSET $offset";
$attemptsStmt = $pdo->prepare("SELECT * FROM login_attempts" . $whereClause . $orderBy);
$attemptsStmt->execute($params);
$loginAttempts = $attemptsStmt->fetchAll(PDO::FETCH_ASSOC);

// Get login statistics
$stats = [
    'total_attempts' => 0,
    'successful_logins' => 0,
    'failed_attempts' => 0,
    'locked_accounts' => 0,
    'unique_ips' => 0,
    'unique_users' => 0
];

try {
    $statsStmt = $pdo->query("
        SELECT
            COUNT(*) as total_attempts,
            SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as successful_logins,
            SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_attempts,
            SUM(CASE WHEN status = 'locked' THEN 1 ELSE 0 END) as locked_accounts,
            COUNT(DISTINCT ip_address) as unique_ips,
            COUNT(DISTINCT username) as unique_users
        FROM login_attempts
    ");
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Table might not exist yet, use default values
}

// Get recent failed attempts (last 24 hours)
$recentFailedStmt = $pdo->prepare("
    SELECT username, COUNT(*) as failed_count, MAX(attempt_time) as last_attempt
    FROM login_attempts
    WHERE status = 'failed' AND attempt_time >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    GROUP BY username
    HAVING failed_count >= 3
    ORDER BY failed_count DESC, last_attempt DESC
    LIMIT 10
");
$recentFailedAttempts = $recentFailedStmt->fetchAll(PDO::FETCH_ASSOC);

// Get suspicious IPs (multiple failed attempts from same IP)
$suspiciousIPsStmt = $pdo->prepare("
    SELECT ip_address, COUNT(*) as failed_count, COUNT(DISTINCT username) as unique_users
    FROM login_attempts
    WHERE status = 'failed' AND attempt_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY ip_address
    HAVING failed_count >= 5
    ORDER BY failed_count DESC
    LIMIT 10
");
$suspiciousIPs = $suspiciousIPsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Monitoring - DRF Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .status-success { color: #198754; }
        .status-failed { color: #dc3545; }
        .status-locked { color: #fd7e14; }
        .suspicious-ip { background-color: #fff3cd; }
        .recent-failed { background-color: #f8d7da; }
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
                        <i class="bi bi-shield-lock me-2"></i>
                        Login Monitoring
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="refreshData()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="exportData()">
                                <i class="bi bi-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 mb-0"><?php echo number_format($stats['total_attempts'] ?? 0); ?></div>
                                <small class="text-muted">Total Attempts</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 mb-0 text-success"><?php echo number_format($stats['successful_logins'] ?? 0); ?></div>
                                <small class="text-muted">Successful</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 mb-0 text-danger"><?php echo number_format($stats['failed_attempts'] ?? 0); ?></div>
                                <small class="text-muted">Failed</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 mb-0 text-warning"><?php echo number_format($stats['locked_accounts'] ?? 0); ?></div>
                                <small class="text-muted">Locked</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 mb-0"><?php echo number_format($stats['unique_ips'] ?? 0); ?></div>
                                <small class="text-muted">Unique IPs</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="h4 mb-0"><?php echo number_format($stats['unique_users'] ?? 0); ?></div>
                                <small class="text-muted">Unique Users</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Filters</h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo htmlspecialchars($usernameFilter); ?>">
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="success" <?php echo $statusFilter === 'success' ? 'selected' : ''; ?>>Success</option>
                                    <option value="failed" <?php echo $statusFilter === 'failed' ? 'selected' : ''; ?>>Failed</option>
                                    <option value="locked" <?php echo $statusFilter === 'locked' ? 'selected' : ''; ?>>Locked</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="ip" placeholder="IP Address" value="<?php echo htmlspecialchars($ipFilter); ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="login-monitoring.php" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Login Attempts Table -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Login Attempts</h6>
                        <small class="text-muted">Page <?php echo $page; ?> of <?php echo $totalPages; ?> (<?php echo number_format($totalAttempts); ?> total)</small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Username</th>
                                        <th>IP Address</th>
                                        <th>Status</th>
                                        <th>Reason</th>
                                        <th>User Agent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($loginAttempts) > 0): ?>
                                        <?php foreach ($loginAttempts as $attempt): ?>
                                            <tr>
                                                <td><?php echo date('M d, Y H:i:s', strtotime($attempt['attempt_time'])); ?></td>
                                                <td><?php echo htmlspecialchars($attempt['username']); ?></td>
                                                <td><?php echo htmlspecialchars($attempt['ip_address']); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php
                                                        echo $attempt['status'] === 'success' ? 'success' :
                                                             ($attempt['status'] === 'failed' ? 'danger' : 'warning');
                                                    ?>">
                                                        <?php echo ucfirst($attempt['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($attempt['failure_reason'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <small class="text-muted" title="<?php echo htmlspecialchars($attempt['user_agent'] ?? ''); ?>">
                                                        <?php echo htmlspecialchars(substr($attempt['user_agent'] ?? '', 0, 50)); ?>...
                                                    </small>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                No login attempts found. <?php if (empty($stats['total_attempts'])): ?>
                                                    <br><small>The login_attempts table may not be created yet. Please run the migration.</small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <nav aria-label="Login attempts pagination">
                                <ul class="pagination justify-content-center">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo !empty($_SERVER['QUERY_STRING']) ? '&' . http_build_query(array_diff_key($_GET, ['page' => ''])) : ''; ?>">Previous</a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($_SERVER['QUERY_STRING']) ? '&' . http_build_query(array_diff_key($_GET, ['page' => ''])) : ''; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo !empty($_SERVER['QUERY_STRING']) ? '&' . http_build_query(array_diff_key($_GET, ['page' => ''])) : ''; ?>">Next</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Security Alerts -->
                <div class="row">
                    <!-- Recent Failed Attempts -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                    Recent Failed Attempts (24h)
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (count($recentFailedAttempts) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Username</th>
                                                    <th>Failed Count</th>
                                                    <th>Last Attempt</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recentFailedAttempts as $failed): ?>
                                                    <tr class="recent-failed">
                                                        <td><?php echo htmlspecialchars($failed['username']); ?></td>
                                                        <td><span class="badge bg-danger"><?php echo $failed['failed_count']; ?></span></td>
                                                        <td><?php echo date('H:i:s', strtotime($failed['last_attempt'])); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-0">No recent failed attempts detected.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Suspicious IPs -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-shield-x text-danger me-2"></i>
                                    Suspicious IPs (7 days)
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (count($suspiciousIPs) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>IP Address</th>
                                                    <th>Failed Attempts</th>
                                                    <th>Unique Users</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($suspiciousIPs as $ip): ?>
                                                    <tr class="suspicious-ip">
                                                        <td><?php echo htmlspecialchars($ip['ip_address']); ?></td>
                                                        <td><span class="badge bg-danger"><?php echo $ip['failed_count']; ?></span></td>
                                                        <td><?php echo $ip['unique_users']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-0">No suspicious IP activity detected.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
    <script>
        function refreshData() {
            location.reload();
        }

        function exportData() {
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'csv');
            window.open('login-monitoring.php?' + params.toString(), '_blank');
        }
    </script>
</body>
</html>
