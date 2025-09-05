<?php
session_start();
require_once 'auth/db.php';

// Debug information
echo "<h1>Activity Logs Debug</h1>";

// Check session
echo "<h2>Session Info</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Check permissions
echo "<h2>Permissions Check</h2>";
$userId = $_SESSION['admin_id'] ?? null;
if ($userId) {
    echo "<p>User ID: $userId</p>";
    $hasPermission = userHasPermission($userId, 'view_activity_logs');
    echo "<p>Has view_activity_logs permission: " . ($hasPermission ? 'YES' : 'NO') . "</p>";

    // Get user role
    $role = getUserRole($userId);
    echo "<p>User role: " . ($role ? $role['name'] : 'None') . "</p>";
} else {
    echo "<p>No user ID in session</p>";
}

// Test database functions
echo "<h2>Database Functions</h2>";
try {
    $activityLogs = getActivityLogs([], 5, 0);
    echo "<p>getActivityLogs returned " . count($activityLogs) . " logs</p>";
    if (count($activityLogs) > 0) {
        echo "<ul>";
        foreach($activityLogs as $log) {
            echo "<li>{$log['action']} by {$log['username']}</li>";
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "<p>Error with getActivityLogs: " . $e->getMessage() . "</p>";
}

try {
    $stats = getActivityStats('today');
    echo "<p>getActivityStats returned:</p><pre>";
    print_r($stats);
    echo "</pre>";
} catch (Exception $e) {
    echo "<p>Error with getActivityStats: " . $e->getMessage() . "</p>";
}

// Check filter data
echo "<h2>Filter Data</h2>";
try {
    $users = fetchData('admin_users', [], 'id, username, first_name, last_name', 'username ASC');
    echo "<p>Users count: " . count($users) . "</p>";

    $modules = fetchData('activity_logs', [], 'DISTINCT module', 'module ASC');
    echo "<p>Modules count: " . count($modules) . "</p>";
} catch (Exception $e) {
    echo "<p>Error with filter data: " . $e->getMessage() . "</p>";
}
?>
