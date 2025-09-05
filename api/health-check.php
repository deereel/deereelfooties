<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Include database connection
require_once '../auth/db.php';

function checkDatabaseHealth() {
    global $pdo;

    try {
        $startTime = microtime(true);
        $stmt = $pdo->query("SELECT 1");
        $stmt->fetch();
        $responseTime = round((microtime(true) - $startTime) * 1000, 2);

        return [
            'status' => 'healthy',
            'response_time_ms' => $responseTime,
            'timestamp' => date('c')
        ];
    } catch (Exception $e) {
        return [
            'status' => 'unhealthy',
            'error' => $e->getMessage(),
            'timestamp' => date('c')
        ];
    }
}

function checkDiskSpace() {
    $path = __DIR__;

    if (function_exists('disk_free_space') && function_exists('disk_total_space')) {
        $free = disk_free_space($path);
        $total = disk_total_space($path);
        $used = $total - $free;
        $usagePercent = $total > 0 ? round(($used / $total) * 100, 2) : 0;

        return [
            'total_bytes' => $total,
            'free_bytes' => $free,
            'used_bytes' => $used,
            'usage_percentage' => $usagePercent,
            'status' => $usagePercent > 95 ? 'critical' : ($usagePercent > 90 ? 'warning' : 'healthy')
        ];
    }

    return [
        'status' => 'unknown',
        'error' => 'Disk space functions not available'
    ];
}

function checkMemoryUsage() {
    if (function_exists('memory_get_peak_usage')) {
        $peakMemory = memory_get_peak_usage(true);
        $memoryLimit = ini_get('memory_limit');

        if ($memoryLimit) {
            $limitBytes = parse_size($memoryLimit);
            $usagePercent = $limitBytes > 0 ? round(($peakMemory / $limitBytes) * 100, 2) : 0;

            return [
                'peak_usage_bytes' => $peakMemory,
                'limit_bytes' => $limitBytes,
                'usage_percentage' => $usagePercent,
                'status' => $usagePercent > 90 ? 'warning' : 'healthy'
            ];
        }
    }

    return [
        'status' => 'unknown',
        'error' => 'Memory monitoring not available'
    ];
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

function getSystemLoad() {
    if (function_exists('sys_getloadavg')) {
        $load = sys_getloadavg();
        return [
            'load_1min' => round($load[0], 2),
            'load_5min' => round($load[1], 2),
            'load_15min' => round($load[2], 2),
            'status' => $load[0] > 2 ? 'warning' : 'healthy'
        ];
    }

    return [
        'status' => 'unknown',
        'error' => 'Load average not available'
    ];
}

// Perform health checks
$healthChecks = [
    'database' => checkDatabaseHealth(),
    'disk_space' => checkDiskSpace(),
    'memory' => checkMemoryUsage(),
    'system_load' => getSystemLoad(),
    'timestamp' => date('c'),
    'uptime' => time()
];

// Determine overall status
$overallStatus = 'healthy';
$criticalCount = 0;
$warningCount = 0;

foreach ($healthChecks as $component => $check) {
    if ($component === 'timestamp' || $component === 'uptime') continue;

    if (isset($check['status'])) {
        if ($check['status'] === 'critical') {
            $criticalCount++;
            $overallStatus = 'critical';
        } elseif ($check['status'] === 'warning' && $overallStatus !== 'critical') {
            $warningCount++;
            $overallStatus = 'warning';
        } elseif ($check['status'] === 'unhealthy') {
            $criticalCount++;
            $overallStatus = 'critical';
        }
    }
}

$response = [
    'status' => $overallStatus,
    'checks' => $healthChecks,
    'summary' => [
        'total_checks' => count($healthChecks) - 2, // Exclude timestamp and uptime
        'critical_issues' => $criticalCount,
        'warning_issues' => $warningCount,
        'healthy_checks' => (count($healthChecks) - 2) - $criticalCount - $warningCount
    ]
];

// Set HTTP status code based on overall health
if ($overallStatus === 'critical') {
    http_response_code(503); // Service Unavailable
} elseif ($overallStatus === 'warning') {
    http_response_code(200); // OK but with warnings
} else {
    http_response_code(200); // OK
}

echo json_encode($response, JSON_PRETTY_PRINT);
?>
