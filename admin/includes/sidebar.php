<?php
require_once __DIR__ . '/../../auth/db.php';

if (!function_exists('currentUserHasPermission')) {
    // Function to check if current admin user has a permission
    function currentUserHasPermission($permissionName) {
        if (!isset($_SESSION['admin_user_id'])) {
            return false;
        }
        return userHasPermission($_SESSION['admin_user_id'], $permissionName);
    }
}
?>

<nav id="sidebarMenu" class="admin-sidebar">
    <div class="p-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>" href="index.php">
                    <i class="bi bi-speedometer2 me-1"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'orders.php' || basename($_SERVER['PHP_SELF']) === 'order-details.php' ? 'active' : ''; ?>" href="orders.php">
                    <i class="bi bi-cart me-1"></i>
                    Orders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : ''; ?>" href="products.php">
                    <i class="bi bi-box me-1"></i>
                    Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'inventory.php' ? 'active' : ''; ?>" href="inventory.php">
                    <i class="bi bi-boxes me-1"></i>
                    Inventory
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'customers.php' ? 'active' : ''; ?>" href="customers.php">
                    <i class="bi bi-people me-1"></i>
                    Customers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'social-media.php' ? 'active' : ''; ?>" href="social-media.php">
                    <i class="bi bi-camera me-1"></i>
                    Social Media
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'slide-generator.php' ? 'active' : ''; ?>" href="slide-generator.php">
                    <i class="bi bi-images me-1"></i>
                    Slide Generator
                </a>
            </li>
            <li class="nav-item">
                <?php if (currentUserHasPermission('manage_settings')): ?>
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'active' : ''; ?>" href="settings.php">
                    <i class="bi bi-gear me-1"></i>
                    Settings
                </a>
                <?php endif; ?>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Reports</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'analytics-dashboard.php' ? 'active' : ''; ?>" href="analytics-dashboard.php">
                    <i class="bi bi-bar-chart-line me-1"></i>
                    Analytics Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'reports.php' ? 'active' : ''; ?>" href="reports.php">
                    <i class="bi bi-file-earmark-text me-1"></i>
                    Business Reports
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'sales-report.php' ? 'active' : ''; ?>" href="sales-report.php">
                    <i class="bi bi-graph-up me-1"></i>
                    Sales Report
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Tools & Setup</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <?php if (currentUserHasPermission('manage_security')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'setup-tables.php' ? 'active' : ''; ?>" href="setup-tables.php">
                    <i class="bi bi-tools me-1"></i>
                    Setup Tables
                </a>
            </li>
            <?php endif; ?>

            <?php if (currentUserHasPermission('manage_security')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'setup-order-automation.php' ? 'active' : ''; ?>" href="setup-order-automation.php">
                    <i class="bi bi-robot me-1"></i>
                    Order Automation
                </a>
            </li>
            <?php endif; ?>

            <?php if (currentUserHasPermission('manage_security')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'check-database-structure.php' ? 'active' : ''; ?>" href="check-database-structure.php">
                    <i class="bi bi-database-check me-1"></i>
                    DB Structure
                </a>
            </li>
            <?php endif; ?>

            <?php if (currentUserHasPermission('manage_security')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'test-db-connection.php' ? 'active' : ''; ?>" href="test-db-connection.php">
                    <i class="bi bi-plug me-1"></i>
                    Test DB Connection
                </a>
            </li>
            <?php endif; ?>

            <?php if (currentUserHasPermission('manage_backups')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'backup.php' ? 'active' : ''; ?>" href="backup.php">
                    <i class="bi bi-cloud-upload me-1"></i>
                    Backup Management
                </a>
            </li>
            <?php endif; ?>

            <?php if (currentUserHasPermission('view_system_health')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'system-health.php' ? 'active' : ''; ?>" href="system-health.php">
                    <i class="bi bi-activity me-1"></i>
                    System Health
                </a>
            </li>
            <?php endif; ?>

            <?php if (currentUserHasPermission('view_error_logs')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'error-logs.php' ? 'active' : ''; ?>" href="error-logs.php">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Error Logging
                </a>
            </li>
            <?php endif; ?>

            <?php if (currentUserHasPermission('view_login_monitoring')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'login-monitoring.php' ? 'active' : ''; ?>" href="login-monitoring.php">
                    <i class="bi bi-shield-lock me-1"></i>
                    Login Monitoring
                </a>
            </li>
            <?php endif; ?>

            <?php if (currentUserHasPermission('view_activity_logs')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'activity-logs.php' ? 'active' : ''; ?>" href="activity-logs.php">
                    <i class="bi bi-journal-text me-1"></i>
                    Activity Logs
                </a>
            </li>
            <?php endif; ?>

            <?php if (currentUserHasPermission('manage_security')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'ip-blocking.php' ? 'active' : ''; ?>" href="ip-blocking.php">
                    <i class="bi bi-shield-x me-1"></i>
                    IP Blocking
                </a>
            </li>
            <?php endif; ?>

            <?php if (currentUserHasPermission('manage_security')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'database-maintenance.php' ? 'active' : ''; ?>" href="database-maintenance.php">
                    <i class="bi bi-database-gear me-1"></i>
                    DB Maintenance
                </a>
            </li>
            <?php endif; ?>

            <?php if (currentUserHasPermission('view_system_health')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'performance-monitoring.php' ? 'active' : ''; ?>" href="performance-monitoring.php">
                    <i class="bi bi-speedometer2 me-1"></i>
                    Performance Monitor
                </a>
            </li>
            <?php endif; ?>

            <?php if (currentUserHasPermission('view_support_tickets')): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'support-tickets.php' ? 'active' : ''; ?>" href="support-tickets.php">
                    <i class="bi bi-headset me-1"></i>
                    Support Tickets
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
