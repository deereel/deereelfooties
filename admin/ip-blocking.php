<?php
session_start();

require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if admin is logged in and has permission
try {
    $permissionMiddleware = new PermissionMiddleware('manage_security');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    header('Location: login.php');
    exit;
}

// Handle actions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'block_ip':
                $ipAddress = trim($_POST['ip_address']);
                $reason = trim($_POST['reason']);
                $duration = intval($_POST['duration']);
                $expiresAt = $duration > 0 ? date('Y-m-d H:i:s', strtotime("+{$duration} hours")) : null;

                if (blockIpAddress($ipAddress, 'manual', $reason, $_SESSION['admin_id'], $expiresAt)) {
                    $message = "IP address $ipAddress has been blocked successfully.";
                    $messageType = 'success';
                } else {
                    $message = "Failed to block IP address.";
                    $messageType = 'danger';
                }
                break;

            case 'unblock_ip':
                $ipAddress = trim($_POST['ip_address']);
                if (unblockIpAddress($ipAddress)) {
                    $message = "IP address $ipAddress has been unblocked successfully.";
                    $messageType = 'success';
                } else {
                    $message = "Failed to unblock IP address.";
                    $messageType = 'danger';
                }
                break;

            case 'auto_block':
                $blockedCount = autoBlockSuspiciousIps();
                if ($blockedCount > 0) {
                    $message = "$blockedCount suspicious IP addresses have been automatically blocked.";
                    $messageType = 'success';
                } else {
                    $message = "No suspicious IP addresses found to block.";
                    $messageType = 'info';
                }
                break;
        }
    }
}

// Get filters
$filters = [];
if (isset($_GET['ip_filter']) && !empty($_GET['ip_filter'])) {
    $filters['ip_address'] = $_GET['ip_filter'];
}
if (isset($_GET['type_filter']) && !empty($_GET['type_filter'])) {
    $filters['block_type'] = $_GET['type_filter'];
}
if (isset($_GET['status_filter'])) {
    $filters['is_active'] = $_GET['status_filter'] === 'active' ? 1 : 0;
}

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 50;
$offset = ($page - 1) * $limit;

// Get blocked IPs
$blockedIps = getBlockedIps($filters, $limit, $offset);

// Get statistics
$stats = getIpBlockStats();

// Get total count for pagination
$totalIps = count(fetchData('ip_blocks', $filters, 'id'));
$totalPages = ceil($totalIps / $limit);

// Get whitelisted IPs
$whitelistedIps = fetchData('ip_whitelist', ['is_active' => 1], '*', 'added_at DESC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Blocking Management - DRF Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .blocked-ip { background-color: #f8d7da; }
        .whitelisted-ip { background-color: #d1ecf1; }
        .stats-card { transition: transform 0.2s; }
        .stats-card:hover { transform: translateY(-2px); }
        .ip-address { font-family: 'Courier New', monospace; font-weight: bold; }
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
                        <i class="bi bi-shield-x me-2"></i>
                        IP Blocking Management
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button class="btn btn-sm btn-warning" onclick="autoBlockSuspicious()">
                                <i class="bi bi-robot me-1"></i>Auto Block Suspicious
                            </button>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#blockIpModal">
                                <i class="bi bi-plus-circle me-1"></i>Block IP
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
                    <div class="col-md-3">
                        <div class="card stats-card border-danger">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1 text-danger"><?php echo number_format($stats['total_blocked']); ?></div>
                                <small class="text-muted">Total Blocked IPs</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card border-warning">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1 text-warning"><?php echo number_format($stats['blocks_by_type']['automatic'] ?? 0); ?></div>
                                <small class="text-muted">Auto Blocked</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card border-info">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1 text-info"><?php echo number_format($stats['recent_blocks']); ?></div>
                                <small class="text-muted">Recent Blocks (24h)</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card border-success">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1 text-success"><?php echo number_format($stats['whitelisted']); ?></div>
                                <small class="text-muted">Whitelisted IPs</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-funnel me-1"></i>Filters
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">IP Address</label>
                                <input type="text" class="form-control" name="ip_filter" value="<?php echo htmlspecialchars($_GET['ip_filter'] ?? ''); ?>" placeholder="192.168.1.1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Block Type</label>
                                <select class="form-select" name="type_filter">
                                    <option value="">All Types</option>
                                    <option value="manual" <?php echo (isset($_GET['type_filter']) && $_GET['type_filter'] === 'manual') ? 'selected' : ''; ?>>Manual</option>
                                    <option value="automatic" <?php echo (isset($_GET['type_filter']) && $_GET['type_filter'] === 'automatic') ? 'selected' : ''; ?>>Automatic</option>
                                    <option value="rate_limit" <?php echo (isset($_GET['type_filter']) && $_GET['type_filter'] === 'rate_limit') ? 'selected' : ''; ?>>Rate Limit</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status_filter">
                                    <option value="">All Status</option>
                                    <option value="active" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search me-1"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Blocked IPs Table -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-list-ul me-1"></i>
                            Blocked IP Addresses
                            <span class="badge bg-danger ms-2"><?php echo number_format($totalIps); ?> total</span>
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($blockedIps)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-shield-check display-4 text-success"></i>
                                <p class="mt-2 text-muted">No blocked IP addresses found matching your criteria.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>IP Address</th>
                                            <th>Block Type</th>
                                            <th>Reason</th>
                                            <th>Blocked By</th>
                                            <th>Blocked At</th>
                                            <th>Expires</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($blockedIps as $ip): ?>
                                            <tr class="<?php echo $ip['is_active'] ? 'blocked-ip' : ''; ?>">
                                                <td>
                                                    <span class="ip-address"><?php echo htmlspecialchars($ip['ip_address']); ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php
                                                        echo $ip['block_type'] === 'manual' ? 'primary' :
                                                             ($ip['block_type'] === 'automatic' ? 'warning' : 'info');
                                                    ?>">
                                                        <?php echo htmlspecialchars($ip['block_type']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small><?php echo htmlspecialchars($ip['reason'] ?: 'No reason provided'); ?></small>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($ip['blocked_by_username'] ?: 'System'); ?>
                                                </td>
                                                <td>
                                                    <small><?php echo date('M d, Y H:i', strtotime($ip['blocked_at'])); ?></small>
                                                </td>
                                                <td>
                                                    <?php if ($ip['expires_at']): ?>
                                                        <small><?php echo date('M d, Y H:i', strtotime($ip['expires_at'])); ?></small>
                                                    <?php else: ?>
                                                        <span class="text-muted">Never</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $ip['is_active'] ? 'danger' : 'secondary'; ?>">
                                                        <?php echo $ip['is_active'] ? 'Active' : 'Inactive'; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($ip['is_active']): ?>
                                                        <button class="btn btn-sm btn-success" onclick="unblockIp('<?php echo $ip['ip_address']; ?>')">
                                                            <i class="bi bi-unlock"></i>
                                                        </button>
                                                    <?php endif; ?>
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

                <!-- Whitelisted IPs Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-check-circle me-1"></i>
                            Whitelisted IP Addresses
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (empty($whitelistedIps)): ?>
                            <p class="text-muted mb-0">No whitelisted IP addresses.</p>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($whitelistedIps as $ip): ?>
                                    <div class="col-md-3 mb-2">
                                        <div class="card whitelisted-ip">
                                            <div class="card-body p-2">
                                                <div class="ip-address"><?php echo htmlspecialchars($ip['ip_address']); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($ip['description']); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Block IP Modal -->
    <div class="modal fade" id="blockIpModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Block IP Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="block_ip">
                        <div class="mb-3">
                            <label class="form-label">IP Address</label>
                            <input type="text" class="form-control" name="ip_address" required placeholder="192.168.1.1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea class="form-control" name="reason" rows="3" placeholder="Reason for blocking this IP"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Block Duration (hours)</label>
                            <input type="number" class="form-control" name="duration" value="24" min="1" placeholder="Leave empty for permanent block">
                            <small class="text-muted">Leave empty for permanent block</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Block IP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function unblockIp(ipAddress) {
            if (confirm(`Are you sure you want to unblock ${ipAddress}?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="unblock_ip">
                    <input type="hidden" name="ip_address" value="${ipAddress}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function autoBlockSuspicious() {
            if (confirm('This will automatically block IP addresses with suspicious login activity. Continue?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="action" value="auto_block">';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
