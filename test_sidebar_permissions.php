<?php
session_start();
require_once 'auth/db.php';

echo "🧪 Testing Sidebar Permissions & Page Access\n";
echo "=============================================\n\n";

// Test users
$testUsers = [
    'super_admin' => 'oladayo',
    'admin' => 'temmy'
];

// Restricted pages that should only show for super admin
$restrictedPages = [
    'backup.php' => 'Backup Management',
    'system-health.php' => 'System Health',
    'error-logs.php' => 'Error Logging',
    'login-monitoring.php' => 'Login Monitoring',
    'activity-logs.php' => 'Activity Logs',
    'ip-blocking.php' => 'IP Blocking',
    'database-maintenance.php' => 'DB Maintenance',
    'performance-monitoring.php' => 'Performance Monitor'
];

$results = [];

foreach ($testUsers as $userType => $username) {
    echo "👤 Testing {$userType}: {$username}\n";
    echo "-----------------------------------\n";

    // Get user and simulate login
    $user = getAdminUserByUsername($username);
    if (!$user) {
        echo "❌ User not found\n\n";
        continue;
    }

    // Set session for testing
    $_SESSION['admin_user_id'] = $user['id'];
    $_SESSION['admin_username'] = $user['username'];
    $_SESSION['admin_role'] = $user['role_name'];

    // Test sidebar visibility (simulate include)
    ob_start();
    include 'admin/includes/sidebar.php';
    $sidebarContent = ob_get_clean();

    // Check which restricted buttons are visible in sidebar
    $visibleButtons = [];
    $hiddenButtons = [];

    foreach ($restrictedPages as $page => $name) {
        if (strpos($sidebarContent, $page) !== false) {
            $visibleButtons[] = $name;
        } else {
            $hiddenButtons[] = $name;
        }
    }

    // Test actual page access
    $accessiblePages = [];
    $inaccessiblePages = [];

    foreach ($restrictedPages as $page => $name) {
        // Simulate page access by checking permission
        $pagePermission = getPagePermission($page);
        if ($pagePermission && userHasPermission($user['id'], $pagePermission)) {
            $accessiblePages[] = $name;
        } else {
            $inaccessiblePages[] = $name;
        }
    }

    // Results
    if ($userType === 'super_admin') {
        $sidebarCorrect = count($visibleButtons) === count($restrictedPages);
        $accessCorrect = count($accessiblePages) === count($restrictedPages);
        echo "Sidebar: " . ($sidebarCorrect ? "✅ All restricted buttons visible" : "❌ Some buttons missing") . "\n";
        echo "Page Access: " . ($accessCorrect ? "✅ All pages accessible" : "❌ Some pages inaccessible") . "\n";
    } else {
        $sidebarCorrect = count($visibleButtons) === 0;
        $accessCorrect = count($accessiblePages) === 0;
        echo "Sidebar: " . ($sidebarCorrect ? "✅ No restricted buttons visible" : "❌ Restricted buttons visible: " . implode(', ', $visibleButtons)) . "\n";
        echo "Page Access: " . ($accessCorrect ? "✅ No restricted pages accessible" : "❌ Restricted pages accessible: " . implode(', ', $accessiblePages)) . "\n";
    }

    $results[$userType] = [
        'sidebar_visible' => $visibleButtons,
        'sidebar_hidden' => $hiddenButtons,
        'pages_accessible' => $accessiblePages,
        'pages_inaccessible' => $inaccessiblePages,
        'sidebar_correct' => ($userType === 'super_admin') ? count($visibleButtons) === count($restrictedPages) : count($visibleButtons) === 0,
        'access_correct' => ($userType === 'super_admin') ? count($accessiblePages) === count($restrictedPages) : count($accessiblePages) === 0
    ];

    // Clear session
    session_unset();

    echo "\n";
}

// Helper function to get permission required for each page
function getPagePermission($page) {
    $pagePermissions = [
        'backup.php' => 'manage_backups',
        'system-health.php' => 'view_system_health',
        'error-logs.php' => 'view_error_logs',
        'login-monitoring.php' => 'view_login_monitoring',
        'activity-logs.php' => 'view_activity_logs',
        'ip-blocking.php' => 'manage_security',
        'database-maintenance.php' => 'manage_security', // Assuming this uses security permission
        'performance-monitoring.php' => 'view_system_health' // Assuming this uses system health permission
    ];

    return $pagePermissions[$page] ?? null;
}

// Summary
echo "📊 Final Test Results\n";
echo "=====================\n";

$allTestsPass = true;
foreach ($results as $userType => $result) {
    $overallPass = $result['sidebar_correct'] && $result['access_correct'];
    echo "{$userType}: " . ($overallPass ? "✅ PASS" : "❌ FAIL") . "\n";
    if (!$overallPass) {
        $allTestsPass = false;
        if (!$result['sidebar_correct']) {
            echo "  - Sidebar issue\n";
        }
        if (!$result['access_correct']) {
            echo "  - Page access issue\n";
        }
    }
}

echo "\n" . ($allTestsPass ? "🎉 All permission tests passed!" : "⚠️  Some permission tests failed.") . "\n";

echo "\n🔍 Detailed Results:\n";
foreach ($results as $userType => $result) {
    echo "\n{$userType}:\n";
    echo "  Sidebar visible: " . count($result['sidebar_visible']) . " buttons\n";
    echo "  Pages accessible: " . count($result['pages_accessible']) . " pages\n";
}
?>
