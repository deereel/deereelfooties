<?php
session_start();

require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if admin is logged in and has permission
try {
    $permissionMiddleware = new PermissionMiddleware('manage_settings');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    header('Location: login.php');
    exit;
}

// Log performance monitoring viewing activity
logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'view_performance_monitoring', 'system', 'read', null, 'Viewed performance monitoring page');

// Get performance metrics
$performanceData = [];
try {
    // Database connection info
    $performanceData['connections'] = [
        'current' => $pdo->query("SHOW STATUS LIKE 'Threads_connected'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'running' => $pdo->query("SHOW STATUS LIKE 'Threads_running'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'cached' => $pdo->query("SHOW STATUS LIKE 'Threads_cached'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'max_connections' => $pdo->query("SHOW VARIABLES LIKE 'max_connections'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0
    ];

    // Query cache info
    $performanceData['query_cache'] = [
        'size' => $pdo->query("SHOW VARIABLES LIKE 'query_cache_size'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'hits' => $pdo->query("SHOW STATUS LIKE 'Qcache_hits'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'inserts' => $pdo->query("SHOW STATUS LIKE 'Qcache_inserts'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'not_cached' => $pdo->query("SHOW STATUS LIKE 'Qcache_not_cached'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0
    ];

    // InnoDB buffer pool
    $performanceData['innodb'] = [
        'buffer_pool_size' => $pdo->query("SHOW VARIABLES LIKE 'innodb_buffer_pool_size'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'buffer_pool_pages_total' => $pdo->query("SHOW STATUS LIKE 'Innodb_buffer_pool_pages_total'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'buffer_pool_pages_free' => $pdo->query("SHOW STATUS LIKE 'Innodb_buffer_pool_pages_free'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'buffer_pool_pages_data' => $pdo->query("SHOW STATUS LIKE 'Innodb_buffer_pool_pages_data'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0
    ];

    // Slow queries
    $performanceData['slow_queries'] = [
        'slow_queries' => $pdo->query("SHOW STATUS LIKE 'Slow_queries'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'long_query_time' => $pdo->query("SHOW VARIABLES LIKE 'long_query_time'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0
    ];

    // Table locks
    $performanceData['locks'] = [
        'table_locks_immediate' => $pdo->query("SHOW STATUS LIKE 'Table_locks_immediate'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'table_locks_waited' => $pdo->query("SHOW STATUS LIKE 'Table_locks_waited'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0
    ];

    // Key efficiency
    $performanceData['key_efficiency'] = [
        'key_reads' => $pdo->query("SHOW STATUS LIKE 'Key_reads'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'key_read_requests' => $pdo->query("SHOW STATUS LIKE 'Key_read_requests'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'key_writes' => $pdo->query("SHOW STATUS LIKE 'Key_writes'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0,
        'key_write_requests' => $pdo->query("SHOW STATUS LIKE 'Key_write_requests'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 0
    ];

} catch (Exception $e) {
    $performanceData['error'] = $e->getMessage();
}

// Calculate derived metrics
$derivedMetrics = [];
if (!isset($performanceData['error'])) {
    // Connection utilization
    $derivedMetrics['connection_utilization'] = $performanceData['connections']['max_connections'] > 0
        ? round(($performanceData['connections']['current'] / $performanceData['connections']['max_connections']) * 100, 2)
        : 0;

    // Query cache hit rate
    $total_cache_requests = $performanceData['query_cache']['hits'] + $performanceData['query_cache']['inserts'];
    $derivedMetrics['query_cache_hit_rate'] = $total_cache_requests > 0
        ? round(($performanceData['query_cache']['hits'] / $total_cache_requests) * 100, 2)
        : 0;

    // InnoDB buffer pool utilization
    $derivedMetrics['innodb_buffer_utilization'] = $performanceData['innodb']['buffer_pool_pages_total'] > 0
        ? round((($performanceData['innodb']['buffer_pool_pages_total'] - $performanceData['innodb']['buffer_pool_pages_free']) / $performanceData['innodb']['buffer_pool_pages_total']) * 100, 2)
        : 0;

    // Key read efficiency
    $derivedMetrics['key_read_efficiency'] = $performanceData['key_efficiency']['key_read_requests'] > 0
        ? round((1 - ($performanceData['key_efficiency']['key_reads'] / $performanceData['key_efficiency']['key_read_requests'])) * 100, 2)
        : 100;

    // Key write efficiency
    $derivedMetrics['key_write_efficiency'] = $performanceData['key_efficiency']['key_write_requests'] > 0
        ? round((1 - ($performanceData['key_efficiency']['key_writes'] / $performanceData['key_efficiency']['key_write_requests'])) * 100, 2)
        : 100;

    // Lock contention rate
    $total_locks = $performanceData['locks']['table_locks_immediate'] + $performanceData['locks']['table_locks_waited'];
    $derivedMetrics['lock_contention_rate'] = $total_locks > 0
        ? round(($performanceData['locks']['table_locks_waited'] / $total_locks) * 100, 2)
        : 0;
}

// Get recent slow queries (if performance schema is available)
$slowQueries = [];
try {
    $slowQueries = $pdo->query("
        SELECT
            DIGEST_TEXT as query,
            COUNT_STAR as executions,
            AVG_TIMER_WAIT/1000000000 as avg_time_sec,
            MAX_TIMER_WAIT/1000000000 as max_time_sec,
            FIRST_SEEN,
            LAST_SEEN
        FROM performance_schema.events_statements_summary_by_digest
        WHERE SCHEMA_NAME = DATABASE()
        AND AVG_TIMER_WAIT > 1000000000
        ORDER BY AVG_TIMER_WAIT DESC
        LIMIT 20
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Performance schema might not be enabled
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Monitoring - DRF Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .metric-card { transition: transform 0.2s; }
        .metric-card:hover { transform: translateY(-2px); }
        .performance-good { color: #198754; }
        .performance-warning { color: #fd7e14; }
        .performance-danger { color: #dc3545; }
        .progress-bar-custom { transition: width 0.5s ease; }
        .table-responsive { max-height: 400px; }
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
                        <i class="bi bi-speedometer2 me-2"></i>
                        Performance Monitoring
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button class="btn btn-sm btn-primary" onclick="refreshMetrics()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                            </button>
                            <button class="btn btn-sm btn-info" onclick="exportMetrics()">
                                <i class="bi bi-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                </div>

                <?php if (isset($performanceData['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Performance monitoring error: <?php echo htmlspecialchars($performanceData['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Key Performance Indicators -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card metric-card border-primary">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo number_format($derivedMetrics['connection_utilization'] ?? 0, 1); ?>%</div>
                                <small class="text-muted">Connection Usage</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-primary progress-bar-custom" style="width: <?php echo $derivedMetrics['connection_utilization'] ?? 0; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card metric-card border-success">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo number_format($derivedMetrics['query_cache_hit_rate'] ?? 0, 1); ?>%</div>
                                <small class="text-muted">Query Cache Hit Rate</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-success progress-bar-custom" style="width: <?php echo $derivedMetrics['query_cache_hit_rate'] ?? 0; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card metric-card border-info">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo number_format($derivedMetrics['innodb_buffer_utilization'] ?? 0, 1); ?>%</div>
                                <small class="text-muted">Buffer Pool Usage</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-info progress-bar-custom" style="width: <?php echo $derivedMetrics['innodb_buffer_utilization'] ?? 0; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card metric-card border-warning">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo number_format($derivedMetrics['key_read_efficiency'] ?? 0, 1); ?>%</div>
                                <small class="text-muted">Key Read Efficiency</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-warning progress-bar-custom" style="width: <?php echo $derivedMetrics['key_read_efficiency'] ?? 0; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Connection Statistics -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-link-45deg me-1"></i>
                            Database Connections
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h5 mb-1 <?php echo ($performanceData['connections']['current'] ?? 0) > 50 ? 'performance-warning' : 'performance-good'; ?>">
                                        <?php echo number_format($performanceData['connections']['current'] ?? 0); ?>
                                    </div>
                                    <small class="text-muted">Current Connections</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h5 mb-1 <?php echo ($performanceData['connections']['running'] ?? 0) > 10 ? 'performance-warning' : 'performance-good'; ?>">
                                        <?php echo number_format($performanceData['connections']['running'] ?? 0); ?>
                                    </div>
                                    <small class="text-muted">Running Threads</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h5 mb-1 performance-good">
                                        <?php echo number_format($performanceData['connections']['cached'] ?? 0); ?>
                                    </div>
                                    <small class="text-muted">Cached Threads</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h5 mb-1 performance-info">
                                        <?php echo number_format($performanceData['connections']['max_connections'] ?? 0); ?>
                                    </div>
                                    <small class="text-muted">Max Connections</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Query Cache Performance -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-memory me-1"></i>
                            Query Cache Performance
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h5 mb-1 performance-info">
                                        <?php echo number_format(($performanceData['query_cache']['size'] ?? 0) / 1024 / 1024, 1); ?> MB
                                    </div>
                                    <small class="text-muted">Cache Size</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h5 mb-1 performance-good">
                                        <?php echo number_format($performanceData['query_cache']['hits'] ?? 0); ?>
                                    </div>
                                    <small class="text-muted">Cache Hits</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h5 mb-1 performance-warning">
                                        <?php echo number_format($performanceData['query_cache']['inserts'] ?? 0); ?>
                                    </div>
                                    <small class="text-muted">Cache Inserts</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h5 mb-1 performance-danger">
                                        <?php echo number_format($performanceData['query_cache']['not_cached'] ?? 0); ?>
                                    </div>
                                    <small class="text-muted">Not Cached</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- InnoDB Buffer Pool -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-database me-1"></i>
                            InnoDB Buffer Pool
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h5 mb-1 performance-info">
                                        <?php echo number_format(($performanceData['innodb']['buffer_pool_size'] ?? 0) / 1024 / 1024 / 1024, 1); ?> GB
                                    </div>
                                    <small class="text-muted">Pool Size</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h5 mb-1 performance-good">
                                        <?php echo number_format($performanceData['innodb']['buffer_pool_pages_data'] ?? 0); ?>
                                    </div>
                                    <small class="text-muted">Data Pages</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h5 mb-1 performance-warning">
                                        <?php echo number_format($performanceData['innodb']['buffer_pool_pages_free'] ?? 0); ?>
                                    </div>
                                    <small class="text-muted">Free Pages</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h5 mb-1 performance-info">
                                        <?php echo number_format($performanceData['innodb']['buffer_pool_pages_total'] ?? 0); ?>
                                    </div>
                                    <small class="text-muted">Total Pages</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slow Queries -->
                <?php if (!empty($slowQueries)): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-clock-history me-1"></i>
                                Slow Queries (Performance Schema)
                                <span class="badge bg-warning ms-2"><?php echo count($slowQueries); ?> queries</span>
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Query</th>
                                            <th>Executions</th>
                                            <th>Avg Time (sec)</th>
                                            <th>Max Time (sec)</th>
                                            <th>First Seen</th>
                                            <th>Last Seen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($slowQueries as $query): ?>
                                            <tr>
                                                <td>
                                                    <small class="text-muted font-monospace">
                                                        <?php echo htmlspecialchars(substr($query['query'], 0, 80)); ?>...
                                                    </small>
                                                </td>
                                                <td><?php echo number_format($query['executions']); ?></td>
                                                <td><?php echo number_format($query['avg_time_sec'], 3); ?>s</td>
                                                <td><?php echo number_format($query['max_time_sec'], 3); ?>s</td>
                                                <td><small><?php echo $query['FIRST_SEEN'] ? date('M d, H:i', strtotime($query['FIRST_SEEN'])) : 'N/A'; ?></small></td>
                                                <td><small><?php echo $query['LAST_SEEN'] ? date('M d, H:i', strtotime($query['LAST_SEEN'])) : 'N/A'; ?></small></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Performance Recommendations -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-lightbulb me-1"></i>
                            Performance Recommendations
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $recommendations = [];

                            if (($derivedMetrics['connection_utilization'] ?? 0) > 80) {
                                $recommendations[] = ['type' => 'danger', 'icon' => 'bi-exclamation-triangle', 'message' => 'High connection utilization detected. Consider increasing max_connections.'];
                            }

                            if (($derivedMetrics['query_cache_hit_rate'] ?? 0) < 50) {
                                $recommendations[] = ['type' => 'warning', 'icon' => 'bi-graph-down', 'message' => 'Low query cache hit rate. Consider increasing query_cache_size.'];
                            }

                            if (($derivedMetrics['innodb_buffer_utilization'] ?? 0) > 95) {
                                $recommendations[] = ['type' => 'warning', 'icon' => 'bi-database', 'message' => 'InnoDB buffer pool is nearly full. Consider increasing innodb_buffer_pool_size.'];
                            }

                            if (($derivedMetrics['lock_contention_rate'] ?? 0) > 10) {
                                $recommendations[] = ['type' => 'warning', 'icon' => 'bi-lock', 'message' => 'High lock contention detected. Review table locking patterns.'];
                            }

                            if (($performanceData['slow_queries']['slow_queries'] ?? 0) > 100) {
                                $recommendations[] = ['type' => 'danger', 'icon' => 'bi-clock', 'message' => 'High number of slow queries detected. Enable slow query log for analysis.'];
                            }

                            if (empty($recommendations)) {
                                $recommendations[] = ['type' => 'success', 'icon' => 'bi-check-circle', 'message' => 'Database performance looks good! No immediate optimizations needed.'];
                            }

                            foreach ($recommendations as $rec): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="alert alert-<?php echo $rec['type']; ?> mb-0">
                                        <i class="bi <?php echo $rec['icon']; ?> me-2"></i>
                                        <?php echo htmlspecialchars($rec['message']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function refreshMetrics() {
            window.location.reload();
        }

        function exportMetrics() {
            const data = {
                timestamp: new Date().toISOString(),
                connections: <?php echo json_encode($performanceData['connections'] ?? []); ?>,
                derived_metrics: <?php echo json_encode($derivedMetrics); ?>,
                recommendations: <?php echo json_encode($recommendations ?? []); ?>
            };

            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'performance-metrics-' + new Date().toISOString().split('T')[0] + '.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // Auto-refresh every 30 seconds
        setInterval(refreshMetrics, 30000);
    </script>
</body>
</html>
