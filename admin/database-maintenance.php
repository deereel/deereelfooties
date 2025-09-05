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



// Log database maintenance viewing activity
logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'view_database_maintenance', 'system', 'read', null, 'Viewed database maintenance page');

// Handle actions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'optimize_table':
                $table = $_POST['table_name'];
                try {
                    $pdo->exec("OPTIMIZE TABLE `$table`");
                    $message = "Table '$table' has been optimized successfully.";
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = "Failed to optimize table: " . $e->getMessage();
                    $messageType = 'danger';
                }
                break;

            case 'analyze_table':
                $table = $_POST['table_name'];
                try {
                    $result = $pdo->query("ANALYZE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
                    $message = "Table '$table' analyzed. Cardinality: " . ($result['Cardinality'] ?? 'N/A');
                    $messageType = 'info';
                } catch (Exception $e) {
                    $message = "Failed to analyze table: " . $e->getMessage();
                    $messageType = 'danger';
                }
                break;

            case 'repair_table':
                $table = $_POST['table_name'];
                try {
                    $pdo->exec("REPAIR TABLE `$table`");
                    $message = "Table '$table' has been repaired successfully.";
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = "Failed to repair table: " . $e->getMessage();
                    $messageType = 'danger';
                }
                break;

            case 'run_maintenance':
                try {
                    // Run various maintenance operations
                    $pdo->exec("FLUSH TABLES");
                    $pdo->exec("FLUSH LOGS");
                    $pdo->exec("RESET QUERY CACHE");

                    $message = "Database maintenance completed successfully.";
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = "Maintenance failed: " . $e->getMessage();
                    $messageType = 'danger';
                }
                break;
        }
    }
}

// Get database information
$dbInfo = [];
try {
    // Database size
    $result = $pdo->query("
        SELECT
            ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb,
            COUNT(*) as table_count
        FROM information_schema.tables
        WHERE table_schema = DATABASE()
    ")->fetch(PDO::FETCH_ASSOC);

    $dbInfo['size'] = $result['size_mb'] ?? 0;
    $dbInfo['table_count'] = $result['table_count'] ?? 0;

    // Get table information
    $tables = $pdo->query("
        SELECT
            table_name,
            ROUND((data_length + index_length) / 1024 / 1024, 2) as size_mb,
            table_rows,
            auto_increment,
            create_time,
            update_time
        FROM information_schema.tables
        WHERE table_schema = DATABASE()
        ORDER BY (data_length + index_length) DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    $dbInfo['tables'] = $tables;

    // Get slow queries (if available)
    $slowQueries = [];
    try {
        $slowQueries = $pdo->query("
            SELECT sql_text, exec_count, avg_timer_wait/1000000000 as avg_time_sec
            FROM performance_schema.events_statements_summary_by_digest
            WHERE schema_name = DATABASE()
            AND avg_timer_wait > 1000000000
            ORDER BY avg_timer_wait DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        // Performance schema might not be available
    }

    $dbInfo['slow_queries'] = $slowQueries;

} catch (Exception $e) {
    $dbInfo['error'] = $e->getMessage();
}

// Get index information
$indexes = [];
try {
    $indexes = $pdo->query("
        SELECT
            TABLE_NAME,
            INDEX_NAME,
            COLUMN_NAME,
            SEQ_IN_INDEX,
            CARDINALITY,
            PAGES,
            FILTER_CONDITION
        FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
        ORDER BY TABLE_NAME, SEQ_IN_INDEX
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Index info might not be available
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Maintenance - DRF Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .table-responsive { max-height: 400px; }
        .maintenance-card { transition: transform 0.2s; }
        .maintenance-card:hover { transform: translateY(-2px); }
        .status-healthy { color: #198754; }
        .status-warning { color: #fd7e14; }
        .status-danger { color: #dc3545; }
        .metric-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
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
                        <i class="bi bi-database-gear me-2"></i>
                        Database Maintenance
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button class="btn btn-sm btn-warning" onclick="runMaintenance()">
                                <i class="bi bi-tools me-1"></i>Run Maintenance
                            </button>
                            <button class="btn btn-sm btn-info" onclick="refreshStats()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh Stats
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

                <?php if (isset($dbInfo['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Database connection error: <?php echo htmlspecialchars($dbInfo['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Database Overview -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card metric-card">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo number_format($dbInfo['size'] ?? 0, 2); ?> MB</div>
                                <small>Database Size</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card metric-card">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo number_format($dbInfo['table_count'] ?? 0); ?></div>
                                <small>Total Tables</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card metric-card">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo count($indexes); ?></div>
                                <small>Total Indexes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card metric-card">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo count($dbInfo['slow_queries'] ?? []); ?></div>
                                <small>Slow Queries</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tables Overview -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-table me-1"></i>
                            Tables Overview
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Table Name</th>
                                        <th>Size (MB)</th>
                                        <th>Rows</th>
                                        <th>Auto Increment</th>
                                        <th>Last Update</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (($dbInfo['tables'] ?? []) as $table): ?>
                                        <tr>
                                            <td>
                                                <code><?php echo htmlspecialchars($table['table_name']); ?></code>
                                            </td>
                                            <td><?php echo number_format($table['size_mb'], 2); ?> MB</td>
                                            <td><?php echo number_format($table['table_rows']); ?></td>
                                            <td><?php echo $table['auto_increment'] ?? 'N/A'; ?></td>
                                            <td>
                                                <small><?php echo $table['update_time'] ? date('M d, Y H:i', strtotime($table['update_time'])) : 'Never'; ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" onclick="analyzeTable('<?php echo $table['table_name']; ?>')">
                                                        <i class="bi bi-graph-up"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success" onclick="optimizeTable('<?php echo $table['table_name']; ?>')">
                                                        <i class="bi bi-gear"></i>
                                                    </button>
                                                    <button class="btn btn-outline-warning" onclick="repairTable('<?php echo $table['table_name']; ?>')">
                                                        <i class="bi bi-wrench"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Indexes Overview -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-lightning me-1"></i>
                            Database Indexes
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Table</th>
                                        <th>Index Name</th>
                                        <th>Column</th>
                                        <th>Sequence</th>
                                        <th>Cardinality</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($indexes as $index): ?>
                                        <tr>
                                            <td><code><?php echo htmlspecialchars($index['TABLE_NAME']); ?></code></td>
                                            <td><code><?php echo htmlspecialchars($index['INDEX_NAME']); ?></code></td>
                                            <td><code><?php echo htmlspecialchars($index['COLUMN_NAME']); ?></code></td>
                                            <td><?php echo $index['SEQ_IN_INDEX']; ?></td>
                                            <td><?php echo number_format($index['CARDINALITY'] ?? 0); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Slow Queries -->
                <?php if (!empty($dbInfo['slow_queries'])): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bi bi-speedometer2 me-1"></i>
                                Slow Queries (Performance Schema)
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Query</th>
                                            <th>Exec Count</th>
                                            <th>Avg Time (sec)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($dbInfo['slow_queries'] as $query): ?>
                                            <tr>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php echo htmlspecialchars(substr($query['sql_text'], 0, 100)); ?>...
                                                    </small>
                                                </td>
                                                <td><?php echo number_format($query['exec_count']); ?></td>
                                                <td><?php echo number_format($query['avg_time_sec'], 3); ?>s</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Maintenance Actions -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-tools me-1"></i>
                            Maintenance Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card maintenance-card border-primary">
                                    <div class="card-body text-center">
                                        <i class="bi bi-arrow-clockwise display-4 text-primary mb-2"></i>
                                        <h6>Flush Tables</h6>
                                        <small class="text-muted">Clear table cache</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card maintenance-card border-success">
                                    <div class="card-body text-center">
                                        <i class="bi bi-graph-up display-4 text-success mb-2"></i>
                                        <h6>Analyze Tables</h6>
                                        <small class="text-muted">Update index statistics</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card maintenance-card border-warning">
                                    <div class="card-body text-center">
                                        <i class="bi bi-gear display-4 text-warning mb-2"></i>
                                        <h6>Optimize Tables</h6>
                                        <small class="text-muted">Defragment and optimize</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card maintenance-card border-info">
                                    <div class="card-body text-center">
                                        <i class="bi bi-shield-check display-4 text-info mb-2"></i>
                                        <h6>Repair Tables</h6>
                                        <small class="text-muted">Fix corrupted tables</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Action Modals -->
    <div class="modal fade" id="tableActionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tableActionTitle">Table Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" id="tableAction">
                        <input type="hidden" name="table_name" id="tableName">
                        <p id="tableActionMessage">Are you sure you want to perform this action?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="tableActionBtn">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function analyzeTable(tableName) {
            document.getElementById('tableAction').value = 'analyze_table';
            document.getElementById('tableName').value = tableName;
            document.getElementById('tableActionTitle').textContent = 'Analyze Table';
            document.getElementById('tableActionMessage').textContent = `Analyze table "${tableName}" to update index statistics?`;
            document.getElementById('tableActionBtn').textContent = 'Analyze';
            new bootstrap.Modal(document.getElementById('tableActionModal')).show();
        }

        function optimizeTable(tableName) {
            document.getElementById('tableAction').value = 'optimize_table';
            document.getElementById('tableName').value = tableName;
            document.getElementById('tableActionTitle').textContent = 'Optimize Table';
            document.getElementById('tableActionMessage').textContent = `Optimize table "${tableName}" to defragment and improve performance?`;
            document.getElementById('tableActionBtn').textContent = 'Optimize';
            new bootstrap.Modal(document.getElementById('tableActionModal')).show();
        }

        function repairTable(tableName) {
            document.getElementById('tableAction').value = 'repair_table';
            document.getElementById('tableName').value = tableName;
            document.getElementById('tableActionTitle').textContent = 'Repair Table';
            document.getElementById('tableActionMessage').textContent = `Repair table "${tableName}" to fix potential corruption?`;
            document.getElementById('tableActionBtn').textContent = 'Repair';
            new bootstrap.Modal(document.getElementById('tableActionModal')).show();
        }

        function runMaintenance() {
            if (confirm('This will run general database maintenance operations. Continue?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="action" value="run_maintenance">';
                document.body.appendChild(form);
                form.submit();
            }
        }

        function refreshStats() {
            window.location.reload();
        }
    </script>
</body>
</html>
