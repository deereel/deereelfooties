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

// Log error logs viewing activity
logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'view_error_logs', 'system', 'read', null, 'Viewed error logs management page');

if (!$isAllowed) {
    // Show access denied message for non-super admin/admin users
    include 'includes/header.php';
    echo '<div class="container mt-5">';
    echo '<div class="alert alert-danger text-center" role="alert">';
    echo '<h2>Access Denied</h2>';
    echo '<p>You do not have permission to access the Error Logs page.<br>Only Super Admin and Admin users can access this area.</p>';
    echo '<a href="index.php" class="btn btn-primary">Return to Dashboard</a>';
    echo '</div></div>';
    include 'includes/footer.php';
    exit;
}

// Get error log files
function getErrorLogFiles() {
    $logFiles = [];
    $logDirectories = [
        '../logs/',
        '/var/log/apache2/',
        '/var/log/nginx/',
        '/var/log/httpd/',
        ini_get('error_log') ? dirname(ini_get('error_log')) : null
    ];

    foreach ($logDirectories as $dir) {
        if ($dir && is_dir($dir) && is_readable($dir)) {
            $files = glob($dir . '*.log');
            foreach ($files as $file) {
                if (is_readable($file)) {
                    $logFiles[] = [
                        'path' => $file,
                        'name' => basename($file),
                        'size' => filesize($file),
                        'modified' => filemtime($file),
                        'readable' => true
                    ];
                }
            }
        }
    }

    // Sort by modification time (newest first)
    usort($logFiles, function($a, $b) {
        return $b['modified'] - $a['modified'];
    });

    return $logFiles;
}

// Parse log file content
function parseLogFile($filePath, $lines = 100, $search = '', $level = '') {
    if (!is_readable($filePath)) {
        return ['error' => 'File not readable'];
    }

    $content = file_get_contents($filePath);
    if ($content === false) {
        return ['error' => 'Could not read file'];
    }

    $linesArray = explode("\n", $content);
    $linesArray = array_reverse($linesArray); // Show newest first

    $parsedLines = [];
    $count = 0;

    foreach ($linesArray as $line) {
        if (empty(trim($line))) continue;

        // Apply search filter
        if (!empty($search) && stripos($line, $search) === false) {
            continue;
        }

        // Apply level filter
        if (!empty($level)) {
            $lineLevel = getLogLevel($line);
            if ($lineLevel !== $level) {
                continue;
            }
        }

        $parsedLines[] = parseLogLine($line);
        $count++;

        if ($count >= $lines) break;
    }

    return [
        'lines' => $parsedLines,
        'total_lines' => count($linesArray),
        'filtered_count' => $count,
        'file_size' => filesize($filePath)
    ];
}

// Parse individual log line
function parseLogLine($line) {
    $parsed = [
        'raw' => $line,
        'timestamp' => null,
        'level' => 'UNKNOWN',
        'message' => $line,
        'file' => null,
        'line' => null
    ];

    // Try to extract timestamp (common formats)
    $timestampPatterns = [
        '/^\[([^\]]+)\]/', // [timestamp]
        '/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', // YYYY-MM-DD HH:MM:SS
        '/^(\d{2}\/\w{3}\/\d{4}:\d{2}:\d{2}:\d{2})/' // DD/MMM/YYYY:HH:MM:SS
    ];

    foreach ($timestampPatterns as $pattern) {
        if (preg_match($pattern, $line, $matches)) {
            $parsed['timestamp'] = $matches[1];
            $parsed['message'] = trim(substr($line, strlen($matches[0])));
            break;
        }
    }

    // Extract log level
    $parsed['level'] = getLogLevel($line);

    // Try to extract file and line information
    if (preg_match('/in (.+?) on line (\d+)/', $line, $matches)) {
        $parsed['file'] = $matches[1];
        $parsed['line'] = $matches[2];
    }

    return $parsed;
}

// Determine log level from line content
function getLogLevel($line) {
    $line = strtoupper($line);

    if (strpos($line, 'ERROR') !== false || strpos($line, 'FATAL') !== false) {
        return 'ERROR';
    } elseif (strpos($line, 'WARNING') !== false || strpos($line, 'WARN') !== false) {
        return 'WARNING';
    } elseif (strpos($line, 'NOTICE') !== false || strpos($line, 'INFO') !== false) {
        return 'INFO';
    } elseif (strpos($line, 'DEBUG') !== false) {
        return 'DEBUG';
    }

    return 'UNKNOWN';
}

// Get log statistics
function getLogStatistics($logData) {
    $stats = [
        'total' => 0,
        'error' => 0,
        'warning' => 0,
        'info' => 0,
        'debug' => 0,
        'unknown' => 0
    ];

    foreach ($logData['lines'] as $line) {
        $stats['total']++;
        $level = strtolower($line['level']);
        if (isset($stats[$level])) {
            $stats[$level]++;
        } else {
            $stats['unknown']++;
        }
    }

    return $stats;
}

// Handle form submission
$selectedFile = isset($_GET['file']) ? $_GET['file'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$logLevel = isset($_GET['level']) ? $_GET['level'] : '';
$linesLimit = isset($_GET['lines']) ? (int)$_GET['lines'] : 100;

$logFiles = getErrorLogFiles();
$logData = null;
$statistics = null;

if (!empty($selectedFile)) {
    $logData = parseLogFile($selectedFile, $linesLimit, $searchTerm, $logLevel);
    if (!isset($logData['error'])) {
        $statistics = getLogStatistics($logData);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Logs Management - DRF Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .log-entry { font-family: 'Courier New', monospace; font-size: 0.875rem; }
        .log-level-error { color: #dc3545; }
        .log-level-warning { color: #ffc107; }
        .log-level-info { color: #0dcaf0; }
        .log-level-debug { color: #6c757d; }
        .log-level-unknown { color: #6c757d; }
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
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Error Logs Management
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button class="btn btn-sm btn-outline-primary" onclick="refreshLogs()">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <!-- Log Files List -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-file-earmark-text me-2"></i>
                                    Log Files
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($logFiles)): ?>
                                    <p class="text-muted">No log files found or accessible.</p>
                                <?php else: ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($logFiles as $file): ?>
                                            <a href="?file=<?php echo urlencode($file['path']); ?>"
                                               class="list-group-item list-group-item-action <?php echo $selectedFile === $file['path'] ? 'active' : ''; ?>">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($file['name']); ?></h6>
                                                    <small><?php echo format_bytes($file['size']); ?></small>
                                                </div>
                                                <p class="mb-1"><?php echo htmlspecialchars($file['path']); ?></p>
                                                <small class="text-muted">
                                                    Modified: <?php echo date('Y-m-d H:i:s', $file['modified']); ?>
                                                </small>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Log Viewer -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-eye me-2"></i>
                                    Log Viewer
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($selectedFile)): ?>
                                    <div class="text-center text-muted">
                                        <i class="bi bi-file-earmark-text display-4"></i>
                                        <p class="mt-2">Select a log file to view its contents</p>
                                    </div>
                                <?php elseif (isset($logData['error'])): ?>
                                    <div class="alert alert-danger">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <?php echo htmlspecialchars($logData['error']); ?>
                                    </div>
                                <?php else: ?>
                                    <!-- Filters -->
                                    <form method="GET" class="mb-3">
                                        <input type="hidden" name="file" value="<?php echo htmlspecialchars($selectedFile); ?>">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control form-control-sm"
                                                       name="search" placeholder="Search..."
                                                       value="<?php echo htmlspecialchars($searchTerm); ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-select form-select-sm" name="level">
                                                    <option value="">All Levels</option>
                                                    <option value="ERROR" <?php echo $logLevel === 'ERROR' ? 'selected' : ''; ?>>Error</option>
                                                    <option value="WARNING" <?php echo $logLevel === 'WARNING' ? 'selected' : ''; ?>>Warning</option>
                                                    <option value="INFO" <?php echo $logLevel === 'INFO' ? 'selected' : ''; ?>>Info</option>
                                                    <option value="DEBUG" <?php echo $logLevel === 'DEBUG' ? 'selected' : ''; ?>>Debug</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-select form-select-sm" name="lines">
                                                    <option value="50" <?php echo $linesLimit == 50 ? 'selected' : ''; ?>>50 lines</option>
                                                    <option value="100" <?php echo $linesLimit == 100 ? 'selected' : ''; ?>>100 lines</option>
                                                    <option value="200" <?php echo $linesLimit == 200 ? 'selected' : ''; ?>>200 lines</option>
                                                    <option value="500" <?php echo $linesLimit == 500 ? 'selected' : ''; ?>>500 lines</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-search me-1"></i>Filter
                                                </button>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">
                                                    Showing <?php echo $logData['filtered_count']; ?> of <?php echo $logData['total_lines']; ?> lines
                                                </small>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Statistics -->
                                    <?php if ($statistics): ?>
                                        <div class="row mb-3">
                                            <div class="col-md-2">
                                                <div class="card stats-card border-danger">
                                                    <div class="card-body p-2 text-center">
                                                        <div class="h5 mb-0 text-danger"><?php echo $statistics['error']; ?></div>
                                                        <small class="text-muted">Errors</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="card stats-card border-warning">
                                                    <div class="card-body p-2 text-center">
                                                        <div class="h5 mb-0 text-warning"><?php echo $statistics['warning']; ?></div>
                                                        <small class="text-muted">Warnings</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="card stats-card border-info">
                                                    <div class="card-body p-2 text-center">
                                                        <div class="h5 mb-0 text-info"><?php echo $statistics['info']; ?></div>
                                                        <small class="text-muted">Info</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="card stats-card border-secondary">
                                                    <div class="card-body p-2 text-center">
                                                        <div class="h5 mb-0 text-secondary"><?php echo $statistics['debug']; ?></div>
                                                        <small class="text-muted">Debug</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="card stats-card border-secondary">
                                                    <div class="card-body p-2 text-center">
                                                        <div class="h5 mb-0 text-secondary"><?php echo $statistics['unknown']; ?></div>
                                                        <small class="text-muted">Unknown</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="card stats-card border-primary">
                                                    <div class="card-body p-2 text-center">
                                                        <div class="h5 mb-0 text-primary"><?php echo $statistics['total']; ?></div>
                                                        <small class="text-muted">Total</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Log Entries -->
                                    <div class="log-container" style="max-height: 600px; overflow-y: auto;">
                                        <?php foreach ($logData['lines'] as $line): ?>
                                            <div class="log-entry mb-2 p-2 border-start border-3 border-<?php
                                                echo strtolower($line['level']) === 'error' ? 'danger' :
                                                     (strtolower($line['level']) === 'warning' ? 'warning' :
                                                     (strtolower($line['level']) === 'info' ? 'info' : 'secondary'));
                                            ?> log-level-<?php echo strtolower($line['level']); ?>">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <?php if ($line['timestamp']): ?>
                                                            <small class="text-muted me-2"><?php echo htmlspecialchars($line['timestamp']); ?></small>
                                                        <?php endif; ?>
                                                        <span class="badge bg-<?php
                                                            echo strtolower($line['level']) === 'error' ? 'danger' :
                                                                 (strtolower($line['level']) === 'warning' ? 'warning' :
                                                                 (strtolower($line['level']) === 'info' ? 'info' : 'secondary'));
                                                        ?> me-2"><?php echo htmlspecialchars($line['level']); ?></span>
                                                        <span><?php echo htmlspecialchars($line['message']); ?></span>
                                                    </div>
                                                </div>
                                                <?php if ($line['file'] && $line['line']): ?>
                                                    <div class="mt-1">
                                                        <small class="text-muted">
                                                            <i class="bi bi-file-earmark me-1"></i>
                                                            <?php echo htmlspecialchars($line['file']); ?>:<?php echo htmlspecialchars($line['line']); ?>
                                                        </small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function refreshLogs() {
            location.reload();
        }

        // Auto-scroll to bottom of log container
        document.addEventListener('DOMContentLoaded', function() {
            const logContainer = document.querySelector('.log-container');
            if (logContainer) {
                logContainer.scrollTop = logContainer.scrollHeight;
            }
        });
    </script>
</body>
</html>
