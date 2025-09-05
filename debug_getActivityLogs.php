<?php
require_once 'auth/db.php';

echo "<h1>Debug getActivityLogs Function</h1>";

// Test the function directly
try {
    $logs = getActivityLogs([], 5, 0);
    echo "<p>getActivityLogs returned " . count($logs) . " logs</p>";
    if (count($logs) > 0) {
        echo "<ul>";
        foreach($logs as $log) {
            echo "<li>{$log['action']} by {$log['username']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No logs returned</p>";
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Test the raw query
try {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->execute([5, 0]);
    $rawLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<p>Raw query returned " . count($rawLogs) . " logs</p>";
    if (count($rawLogs) > 0) {
        echo "<ul>";
        foreach($rawLogs as $log) {
            echo "<li>{$log['action']} by {$log['username']}</li>";
        }
        echo "</ul>";
    }
} catch (Exception $e) {
    echo "<p>Raw query error: " . $e->getMessage() . "</p>";
}
?>
