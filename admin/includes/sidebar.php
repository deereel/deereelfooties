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
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'active' : ''; ?>" href="settings.php">
                    <i class="bi bi-gear me-1"></i>
                    Settings
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>User Management</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'admin-users.php' ? 'active' : ''; ?>" href="admin-users.php">
                    <i class="bi bi-person-badge me-1"></i>
                    Admin Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'user-management.php' ? 'active' : ''; ?>" href="user-management.php">
                    <i class="bi bi-people-fill me-1"></i>
                    User Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'role-management.php' ? 'active' : ''; ?>" href="role-management.php">
                    <i class="bi bi-shield-check me-1"></i>
                    Role Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'change-password.php' ? 'active' : ''; ?>" href="change-password.php">
                    <i class="bi bi-key-fill me-1"></i>
                    Change Password
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Product Management</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'add-product.php' ? 'active' : ''; ?>" href="add-product.php">
                    <i class="bi bi-plus-circle me-1"></i>
                    Add Product
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'edit-product.php' ? 'active' : ''; ?>" href="edit-product.php">
                    <i class="bi bi-pencil-square me-1"></i>
                    Edit Product
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'delete-product.php' ? 'active' : ''; ?>" href="delete-product.php">
                    <i class="bi bi-trash me-1"></i>
                    Delete Product
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Customer Management</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'customer-details.php' ? 'active' : ''; ?>" href="customer-details.php">
                    <i class="bi bi-person-lines-fill me-1"></i>
                    Customer Details
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Reports</span>
        </h6>
        <ul class="nav flex-column mb-2">
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
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'setup-tables.php' ? 'active' : ''; ?>" href="setup-tables.php">
                    <i class="bi bi-tools me-1"></i>
                    Setup Tables
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'setup-order-automation.php' ? 'active' : ''; ?>" href="setup-order-automation.php">
                    <i class="bi bi-robot me-1"></i>
                    Order Automation
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'check-database-structure.php' ? 'active' : ''; ?>" href="check-database-structure.php">
                    <i class="bi bi-database-check me-1"></i>
                    DB Structure
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'test-db-connection.php' ? 'active' : ''; ?>" href="test-db-connection.php">
                    <i class="bi bi-plug me-1"></i>
                    Test DB Connection
                </a>
            </li>
        </ul>
    </div>
</nav>
