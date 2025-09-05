<?php
require_once 'auth/db.php';

echo "<h1>Testing Activity Logs</h1>";

// Check if table exists
try {
    $result = $pdo->query("SHOW TABLES LIKE 'activity_logs'");
    $tableExists = $result->rowCount() > 0;
    echo "<p>Activity logs table exists: " . ($tableExists ? 'YES' : 'NO') . "</p>";
} catch (Exception $e) {
    echo "<p>Error checking table: " . $e->getMessage() . "</p>";
}

// Check count
try {
    $result = $pdo->query('SELECT COUNT(*) as count FROM activity_logs')->fetch();
    echo "<p>Activity logs count: " . $result['count'] . "</p>";
} catch (Exception $e) {
    echo "<p>Error getting count: " . $e->getMessage() . "</p>";
}

// Get sample logs
try {
    $logs = $pdo->query('SELECT * FROM activity_logs LIMIT 5')->fetchAll(PDO::FETCH_ASSOC);
    echo "<p>Sample logs:</p><ul>";
    foreach($logs as $log) {
        echo "<li>ID {$log['id']}: {$log['action']} by {$log['username']}</li>";
    }
    echo "</ul>";
} catch (Exception $e) {
    echo "<p>Error getting logs: " . $e->getMessage() . "</p>";
}

// Test getActivityLogs function
try {
    $activityLogs = getActivityLogs([], 5, 0);
    echo "<p>getActivityLogs function returned " . count($activityLogs) . " logs</p>";
    if (count($activityLogs) > 0) {
        echo "<p>Sample from function:</p><ul>";
        foreach($activityLogs as $log) {
            echo "<li>{$log['action']} by {$log['username']}</li>";
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "<p>Error with getActivityLogs: " . $e->getMessage() . "</p>";
}
?>
