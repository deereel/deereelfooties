<?php
session_start();

require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if user is Super Admin only
$userId = $_SESSION['admin_user_id'];
$userRole = getUserRole($userId);
$isAllowed = ($userRole && $userRole['name'] === 'super_admin');

// Log system health viewing activity
logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'view_system_health', 'system', 'read', null, 'Viewed system health monitoring page');

if (!$isAllowed) {
    // Show access denied message for non-super admin/admin users
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Access Denied - System Health</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background-color: #f8f9fa; }
            .access-denied { max-width: 500px; margin: 100px auto; text-align: center; }
        </style>
    </head>
    <body>
        <?php include \'includes/header.php\'; ?>
        <div class="container">
            <div class="access-denied">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="bi bi-shield-x text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="card-title text-danger mb-3">Access Denied</h2>
                        <p class="card-text text-muted mb-4">
                            You do not have permission to access the System Health page.<br>
                            Only Super Admin and Admin users can access this area.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="index.php" class="btn btn-primary">
                                <i class="bi bi-house-door me-2"></i>Return to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>';
    exit;
}

// Get system health data
function getSystemHealth() {
    $health = [];

    // Database connection status
    $health['database'] = checkDatabaseHealth();

    // Server resources
    $health['server'] = getServerResources();

    // Disk usage
    $health['disk'] = getDiskUsage();

    // PHP information
    $health['php'] = getPHPInfo();

    // Overall system status
    $health['overall_status'] = determineOverallStatus($health);

    return $health;
}

function checkDatabaseHealth() {
    global $pdo;

    $dbHealth = [
        'status' => 'unknown',
        'response_time' => 0,
        'connections' => 0,
        'uptime' => 0,
        'version' => 'Unknown'
    ];

    try {
        $startTime = microtime(true);

        // Test basic connection
        $stmt = $pdo->query("SELECT 1");
        $stmt->fetch();

        $dbHealth['status'] = 'healthy';
        $dbHealth['response_time'] = round((microtime(true) - $startTime) * 1000, 2); // ms

        // Get database version
        $stmt = $pdo->query("SELECT VERSION() as version");
        $result = $stmt->fetch();
        $dbHealth['version'] = $result['version'];

        // Get connection count (approximate)
        $stmt = $pdo->query("SHOW PROCESSLIST");
        $dbHealth['connections'] = $stmt->rowCount();

    } catch (Exception $e) {
        $dbHealth['status'] = 'unhealthy';
        $dbHealth['error'] = $e->getMessage();
    }

    return $dbHealth;
}

function getServerResources() {
    $resources = [
        'cpu_usage' => 0,
        'memory_usage' => 0,
        'memory_total' => 0,
        'memory_free' => 0,
        'load_average' => [0, 0, 0]
    ];

    // CPU usage (simplified - requires exec access)
    if (function_exists('sys_getloadavg')) {
        $load = sys_getloadavg();
        $resources['load_average'] = $load;
        // Estimate CPU usage based on load average
        $resources['cpu_usage'] = min(100, $load[0] * 25); // Rough estimation
    }

    // Memory information
    if (function_exists('memory_get_peak_usage')) {
        $resources['memory_usage'] = memory_get_peak_usage(true);
        $resources['memory_total'] = ini_get('memory_limit') ? parse_size(ini_get('memory_limit')) : 0;
    }

    return $resources;
}

function getDiskUsage() {
    $disk = [
        'total' => 0,
        'free' => 0,
        'used' => 0,
        'usage_percentage' => 0
    ];

    $path = __DIR__;

    if (function_exists('disk_total_space') && function_exists('disk_free_space')) {
        $disk['total'] = disk_total_space($path);
        $disk['free'] = disk_free_space($path);
        $disk['used'] = $disk['total'] - $disk['free'];
        $disk['usage_percentage'] = $disk['total'] > 0 ? round(($disk['used'] / $disk['total']) * 100, 2) : 0;
    }

    return $disk;
}

function getPHPInfo() {
    return [
        'version' => PHP_VERSION,
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'extensions' => get_loaded_extensions()
    ];
}

function determineOverallStatus($health) {
    $status = 'healthy';

    // Check database
    if ($health['database']['status'] !== 'healthy') {
        $status = 'critical';
    }

    // Check disk usage (>90% = warning, >95% = critical)
    if ($health['disk']['usage_percentage'] > 95) {
        $status = 'critical';
    } elseif ($health['disk']['usage_percentage'] > 90) {
        $status = max($status, 'warning');
    }

    // Check memory usage (>90% = warning)
    if ($health['server']['memory_total'] > 0) {
        $memoryUsagePercent = ($health['server']['memory_usage'] / $health['server']['memory_total']) * 100;
        if ($memoryUsagePercent > 90) {
            $status = max($status, 'warning');
        }
    }

    return $status;
}

function parse_size($size) {
    $unit = strtolower(substr($size, -1));
    $value = (int)$size;

    switch($unit) {
        case 'g': $value *= 1024 * 1024 * 1024; break;
        case 'm': $value *= 1024 * 1024; break;
        case 'k': $value *= 1024; break;
    }

    return $value;
}

function format_bytes($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

$systemHealth = getSystemHealth();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Health Monitoring - DRF Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .status-healthy { color: #28a745; }
        .status-warning { color: #ffc107; }
        .status-critical { color: #dc3545; }
        .status-unknown { color: #6c757d; }
        .health-card { transition: transform 0.2s; }
        .health-card:hover { transform: translateY(-2px); }
        .metric-value { font-size: 1.5rem; font-weight: bold; }
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
                        <i class="bi bi-activity me-2"></i>
                        System Health Monitoring
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="refreshHealth()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Overall Status -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card health-card border-<?php
                            echo $systemHealth['overall_status'] === 'healthy' ? 'success' :
                                 ($systemHealth['overall_status'] === 'warning' ? 'warning' : 'danger');
                        ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-1">
                                            <i class="bi bi-shield-check me-2"></i>
                                            Overall System Status
                                        </h5>
                                        <p class="card-text mb-0">
                                            <span class="status-<?php echo $systemHealth['overall_status']; ?>">
                                                <i class="bi bi-circle-fill me-1"></i>
                                                <?php echo ucfirst($systemHealth['overall_status']); ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <div class="metric-value status-<?php echo $systemHealth['overall_status']; ?>">
                                            <?php echo $systemHealth['overall_status'] === 'healthy' ? '✓' :
                                                     ($systemHealth['overall_status'] === 'warning' ? '⚠' : '✗'); ?>
                                        </div>
                                        <small class="text-muted">Last checked: <?php echo date('H:i:s'); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Health Metrics Grid -->
                <div class="row mb-4">
                    <!-- Database Health -->
                    <div class="col-md-6 mb-4">
                        <div class="card health-card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-database me-2"></i>
                                    Database Health
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <small class="text-muted">Status</small>
                                            <div class="metric-value status-<?php echo $systemHealth['database']['status']; ?>">
                                                <?php echo ucfirst($systemHealth['database']['status']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <small class="text-muted">Response Time</small>
                                            <div class="metric-value">
                                                <?php echo $systemHealth['database']['response_time']; ?>ms
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <small class="text-muted">Connections</small>
                                            <div class="metric-value">
                                                <?php echo $systemHealth['database']['connections']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <small class="text-muted">Version</small>
                                            <div class="metric-value">
                                                <?php echo substr($systemHealth['database']['version'], 0, 10); ?>...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (isset($systemHealth['database']['error'])): ?>
                                    <div class="alert alert-danger mt-2">
                                        <small><?php echo htmlspecialchars($systemHealth['database']['error']); ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Server Resources -->
                    <div class="col-md-6 mb-4">
                        <div class="card health-card h-100">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-cpu me-2"></i>
                                    Server Resources
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <small class="text-muted">CPU Usage</small>
                                            <div class="metric-value">
                                                <?php echo round($systemHealth['server']['cpu_usage'], 1); ?>%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <small class="text-muted">Memory Used</small>
                                            <div class="metric-value">
                                                <?php echo format_bytes($systemHealth['server']['memory_usage']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <small class="text-muted">Load Average (1m, 5m, 15m)</small>
                                            <div class="metric-value">
                                                <?php echo implode(', ', array_map(function($load) { return round($load, 2); }, $systemHealth['server']['load_average'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Disk Usage -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card health-card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-hdd me-2"></i>
                                    Disk Usage
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <small class="text-muted">Total Space</small>
                                            <div class="metric-value">
                                                <?php echo format_bytes($systemHealth['disk']['total']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <small class="text-muted">Used Space</small>
                                            <div class="metric-value">
                                                <?php echo format_bytes($systemHealth['disk']['used']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <small class="text-muted">Free Space</small>
                                            <div class="metric-value">
                                                <?php echo format_bytes($systemHealth['disk']['free']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <small class="text-muted">Usage</small>
                                            <div class="metric-value status-<?php echo $systemHealth['disk']['usage_percentage'] > 90 ? 'critical' : ($systemHealth['disk']['usage_percentage'] > 80 ? 'warning' : 'healthy'); ?>">
                                                <?php echo $systemHealth['disk']['usage_percentage']; ?>%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 20px;">
                                    <div class="progress-bar bg-<?php echo $systemHealth['disk']['usage_percentage'] > 90 ? 'danger' : ($systemHealth['disk']['usage_percentage'] > 80 ? 'warning' : 'success'); ?>"
                                         role="progressbar"
                                         style="width: <?php echo $systemHealth['disk']['usage_percentage']; ?>%"
                                         aria-valuenow="<?php echo $systemHealth['disk']['usage_percentage']; ?>"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                        <?php echo $systemHealth['disk']['usage_percentage']; ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PHP Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card health-card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-code-slash me-2"></i>
                                    PHP Environment
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <small class="text-muted">Version</small>
                                            <div class="metric-value">
                                                <?php echo $systemHealth['php']['version']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <small class="text-muted">Memory Limit</small>
                                            <div class="metric-value">
                                                <?php echo $systemHealth['php']['memory_limit']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <small class="text-muted">Max Execution</small>
                                            <div class="metric-value">
                                                <?php echo $systemHealth['php']['max_execution_time']; ?>s
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <small class="text-muted">Upload Max Size</small>
                                            <div class="metric-value">
                                                <?php echo $systemHealth['php']['upload_max_filesize']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <small class="text-muted">Extensions Loaded</small>
                                            <div class="metric-value">
                                                <?php echo count($systemHealth['php']['extensions']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    System Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Server Software:</strong><br>
                                        <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Server Time:</strong><br>
                                        <?php echo date('Y-m-d H:i:s T'); ?>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Uptime:</strong><br>
                                        <?php
                                        if (function_exists('shell_exec')) {
                                            $uptime = shell_exec('uptime -p 2>/dev/null');
                                            echo $uptime ? trim($uptime) : 'Not available';
                                        } else {
                                            echo 'Not available';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function refreshHealth() {
            location.reload();
        }

        // Auto-refresh every 30 seconds
        setInterval(refreshHealth, 30000);
    </script>
</body>
</html>
