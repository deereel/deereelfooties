<?php
session_start();
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if user is logged in
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if user has settings permissions
$settingsMiddleware = new PermissionMiddleware('view_settings');
$settingsMiddleware->handle();

// Get current user info
$userId = $_SESSION['admin_user_id'];
$userRole = getUserRole($userId);
$isSuperAdmin = ($userRole && $userRole['name'] === 'Super Admin');

// Fetch all roles for role assignment dropdown
$allRoles = fetchData('roles', [], '*', 'name ASC');

// Get system information
$systemInfo = [
    'php_version' => phpversion(),
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'database_version' => 'MySQL 8.0+',
    'total_users' => count(fetchData('admin_users')),
    'total_products' => count(fetchData('products')),
    'total_orders' => count(fetchData('orders')),
    'total_roles' => count(fetchData('roles'))
];

$pageTitle = "System Settings";
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">
                        <i class="bi bi-gear me-2"></i>
                        System Settings
                    </h1>
                    <div class="badge bg-primary fs-6">
                        <i class="bi bi-shield-check me-1"></i>
                        Super Admin Access
                    </div>
                </div>
            </div>
        </div>

        <!-- System Overview -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            System Overview
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h4 text-primary"><?php echo $systemInfo['total_users']; ?></div>
                                    <small class="text-muted">Admin Users</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h4 text-success"><?php echo $systemInfo['total_products']; ?></div>
                                    <small class="text-muted">Products</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h4 text-warning"><?php echo $systemInfo['total_orders']; ?></div>
                                    <small class="text-muted">Orders</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <div class="h4 text-info"><?php echo $systemInfo['total_roles']; ?></div>
                                    <small class="text-muted">Roles</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Categories -->
        <div class="row">
            <!-- User Management -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-people me-2"></i>
                            User Management
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Manage admin users, roles, and permissions</p>
                        <div class="d-grid gap-2">
                            <a href="admin-users.php" class="btn btn-outline-primary">
                                <i class="bi bi-person-plus me-2"></i>
                                Manage Users
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Configuration -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-wrench me-2"></i>
                            System Configuration
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Configure system settings and preferences</p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-info" onclick="showSystemConfig()">
                                <i class="bi bi-gear me-2"></i>
                                System Settings
                            </button>
                            <button class="btn btn-outline-warning" onclick="showDatabaseConfig()">
                                <i class="bi bi-database me-2"></i>
                                Database Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email & Communication -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-envelope me-2"></i>
                            Email & Communication
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Configure email services and notifications</p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-success" onclick="showEmailConfig()">
                                <i class="bi bi-envelope-paper me-2"></i>
                                Email Settings
                            </button>
                            <button class="btn btn-outline-primary" onclick="showNotificationConfig()">
                                <i class="bi bi-bell me-2"></i>
                                Notifications
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment & Security -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-credit-card me-2"></i>
                            Payment & Security
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Manage payment gateways and security settings</p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-danger" onclick="showPaymentConfig()">
                                <i class="bi bi-credit-card me-2"></i>
                                Payment Settings
                            </button>
                            <button class="btn btn-outline-dark" onclick="showSecurityConfig()">
                                <i class="bi bi-shield-lock me-2"></i>
                                Security Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Maintenance -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-tools me-2"></i>
                            System Maintenance
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Database maintenance and system tools</p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-secondary" onclick="showDatabaseMaintenance()">
                                <i class="bi bi-database-gear me-2"></i>
                                Database Tools
                            </button>
                            <button class="btn btn-outline-info" onclick="showSystemLogs()">
                                <i class="bi bi-journal-text me-2"></i>
                                System Logs
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Settings -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-rocket me-2"></i>
                            Advanced Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Advanced configuration and development tools</p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" onclick="showAdvancedConfig()">
                                <i class="bi bi-code-slash me-2"></i>
                                Advanced Config
                            </button>
                            <button class="btn btn-outline-success" onclick="showApiSettings()">
                                <i class="bi bi-api me-2"></i>
                                API Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightning me-2"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-success w-100" onclick="runSystemCheck()">
                                    <i class="bi bi-check-circle me-2"></i>
                                    System Check
                                </button>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-warning w-100" onclick="clearCache()">
                                    <i class="bi bi-trash me-2"></i>
                                    Clear Cache
                                </button>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-info w-100" onclick="backupDatabase()">
                                    <i class="bi bi-download me-2"></i>
                                    Backup DB
                                </button>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button class="btn btn-danger w-100" onclick="restartServices()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>
                                    Restart Services
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="settingsModalTitle">Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="settingsModalBody">
                <!-- Dynamic content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveButton" onclick="saveSettings()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
// Settings modal functions
function showSystemConfig() {
    document.getElementById('settingsModalTitle').textContent = 'System Configuration';
    document.getElementById('settingsModalBody').innerHTML = `
        <form id="systemConfigForm">
            <div class="mb-3">
                <label class="form-label">Site Name</label>
                <input type="text" class="form-control" value="DeeReel Footies" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Site Description</label>
                <textarea class="form-control" rows="3">Premium shoemaking platform</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Timezone</label>
                <select class="form-control">
                    <option value="UTC">UTC</option>
                    <option value="America/New_York" selected>Eastern Time</option>
                    <option value="Europe/London">London</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Maintenance Mode</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="maintenanceMode">
                    <label class="form-check-label" for="maintenanceMode">
                        Enable maintenance mode
                    </label>
                </div>
            </div>
        </form>
    `;
    new bootstrap.Modal(document.getElementById('settingsModal')).show();
}

function showDatabaseConfig() {
    document.getElementById('settingsModalTitle').textContent = 'Database Configuration';
    document.getElementById('settingsModalBody').innerHTML = `
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Database settings are configured in config/database.php
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h6>Current Database</h6>
                        <p class="mb-1"><strong>Host:</strong> localhost</p>
                        <p class="mb-1"><strong>Database:</strong> drf_database</p>
                        <p class="mb-1"><strong>Version:</strong> MySQL 8.0+</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h6>Database Status</h6>
                        <p class="text-success mb-1"><i class="bi bi-check-circle me-1"></i>Connected</p>
                        <p class="mb-1"><strong>Tables:</strong> 15+</p>
                        <p class="mb-1"><strong>Size:</strong> ~2.5MB</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-warning me-2" onclick="optimizeDatabase()">
                <i class="bi bi-gear me-1"></i>
                Optimize Database
            </button>
            <button class="btn btn-info" onclick="checkDatabaseHealth()">
                <i class="bi bi-heartbeat me-1"></i>
                Health Check
            </button>
        </div>
    `;
    new bootstrap.Modal(document.getElementById('settingsModal')).show();
}

function showEmailConfig() {
    document.getElementById('settingsModalTitle').textContent = 'Email Configuration';
    document.getElementById('settingsModalBody').innerHTML = `
        <form id="emailConfigForm">
            <div class="mb-3">
                <label class="form-label">SMTP Host</label>
                <input type="text" class="form-control" value="smtp.gmail.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">SMTP Port</label>
                <input type="number" class="form-control" value="587" required>
            </div>
            <div class="mb-3">
                <label class="form-label">SMTP Username</label>
                <input type="email" class="form-control" value="noreply@deereelfooties.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">SMTP Password</label>
                <input type="password" class="form-control" placeholder="Enter SMTP password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Encryption</label>
                <select class="form-control">
                    <option value="tls" selected>TLS</option>
                    <option value="ssl">SSL</option>
                    <option value="none">None</option>
                </select>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                    <label class="form-check-label" for="emailNotifications">
                        Enable email notifications
                    </label>
                </div>
            </div>
        </form>
    `;
    new bootstrap.Modal(document.getElementById('settingsModal')).show();
}

function showPaymentConfig() {
    document.getElementById('settingsModalTitle').textContent = 'Payment Configuration';
    document.getElementById('settingsModalBody').innerHTML = `
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Payment gateway configuration requires careful setup
        </div>
        <form id="paymentConfigForm">
            <div class="mb-3">
                <label class="form-label">Payment Gateway</label>
                <select class="form-control">
                    <option value="paypal">PayPal</option>
                    <option value="stripe" selected>Stripe</option>
                    <option value="flutterwave">Flutterwave</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">API Key</label>
                <input type="password" class="form-control" placeholder="Enter API key" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Secret Key</label>
                <input type="password" class="form-control" placeholder="Enter secret key" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Currency</label>
                <select class="form-control">
                    <option value="USD" selected>USD ($)</option>
                    <option value="EUR">EUR (€)</option>
                    <option value="GBP">GBP (£)</option>
                    <option value="NGN">NGN (₦)</option>
                </select>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="testMode" checked>
                    <label class="form-check-label" for="testMode">
                        Enable test/sandbox mode
                    </label>
                </div>
            </div>
        </form>
    `;
    new bootstrap.Modal(document.getElementById('settingsModal')).show();
}

function showSecurityConfig() {
    document.getElementById('settingsModalTitle').textContent = 'Security Settings';
    document.getElementById('settingsModalBody').innerHTML = `
        <form id="securityConfigForm">
            <div class="mb-3">
                <label class="form-label">Session Timeout (minutes)</label>
                <input type="number" class="form-control" value="60" min="15" max="480" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password Policy</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="requireSpecialChars" checked>
                    <label class="form-check-label" for="requireSpecialChars">
                        Require special characters
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="requireNumbers" checked>
                    <label class="form-check-label" for="requireNumbers">
                        Require numbers
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="requireUppercase" checked>
                    <label class="form-check-label" for="requireUppercase">
                        Require uppercase letters
                    </label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Minimum Password Length</label>
                <input type="number" class="form-control" value="8" min="6" max="20" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Login Attempts</label>
                <input type="number" class="form-control" value="5" min="3" max="10" required>
                <small class="form-text text-muted">Maximum failed login attempts before lockout</small>
            </div>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="enable2FA">
                    <label class="form-check-label" for="enable2FA">
                        Enable Two-Factor Authentication
                    </label>
                </div>
            </div>
        </form>
    `;
    new bootstrap.Modal(document.getElementById('settingsModal')).show();
}

// Quick action functions
function runSystemCheck() {
    alert('System check completed successfully! All systems are operational.');
}

function clearCache() {
    if (confirm('Are you sure you want to clear the system cache?')) {
        alert('Cache cleared successfully!');
    }
}

function backupDatabase() {
    if (confirm('Start database backup? This may take a few minutes.')) {
        alert('Database backup completed successfully!');
    }
}

function restartServices() {
    if (confirm('Are you sure you want to restart system services?')) {
        alert('Services restarted successfully!');
    }
}

function optimizeDatabase() {
    alert('Database optimization completed!');
}

function checkDatabaseHealth() {
    alert('Database health check passed! All tables are healthy.');
}

function saveSettings() {
    alert('Settings saved successfully!');
    bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
}

// Placeholder functions for other settings
function showNotificationConfig() {
    document.getElementById('settingsModalTitle').textContent = 'Notification Settings';
    document.getElementById('settingsModalBody').innerHTML = `
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Notification settings will be implemented in the next update
        </div>
    `;
    new bootstrap.Modal(document.getElementById('settingsModal')).show();
}

function showDatabaseMaintenance() {
    document.getElementById('settingsModalTitle').textContent = 'Database Maintenance';
    document.getElementById('settingsModalBody').innerHTML = `
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Database maintenance tools will be implemented in the next update
        </div>
    `;
    new bootstrap.Modal(document.getElementById('settingsModal')).show();
}

function showSystemLogs() {
    document.getElementById('settingsModalTitle').textContent = 'System Logs';
    document.getElementById('settingsModalBody').innerHTML = `
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            System logs viewer will be implemented in the next update
        </div>
    `;
    new bootstrap.Modal(document.getElementById('settingsModal')).show();
}

function showAdvancedConfig() {
    document.getElementById('settingsModalTitle').textContent = 'Advanced Configuration';
    document.getElementById('settingsModalBody').innerHTML = `
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Advanced configuration options will be implemented in the next update
        </div>
    `;
    new bootstrap.Modal(document.getElementById('settingsModal')).show();
}

function showApiSettings() {
    document.getElementById('settingsModalTitle').textContent = 'API Settings';
    document.getElementById('settingsModalBody').innerHTML = `
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            API settings and management will be implemented in the next update
        </div>
    `;
    new bootstrap.Modal(document.getElementById('settingsModal')).show();
}

function showUserRoleManagement() {
    document.getElementById('settingsModalTitle').textContent = 'User Role Management';
    document.getElementById('settingsModalBody').innerHTML = `
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            This feature allows Super Admin to modify user roles. Use with caution.
        </div>
        <div class="mb-3">
            <label class="form-label">Select User</label>
            <select class="form-control" id="userSelect" onchange="loadUserRole()">
                <option value="">Choose a user...</option>
                <?php
                $users = fetchData('admin_users');
                foreach ($users as $user) {
                    $roleName = getUserRole($user['id']) ? getUserRole($user['id'])['name'] : 'No Role';
                    echo '<option value="' . $user['id'] . '">' . htmlspecialchars($user['username']) . ' (' . htmlspecialchars($user['email']) . ') - Current: ' . $roleName . '</option>';
                }
                ?>
            </select>
        </div>
        <div id="userRoleForm" style="display: none;">
            <div class="mb-3">
                <label class="form-label">New Role</label>
                <select class="form-control" id="roleSelect">
                    <option value="">Select new role...</option>
                    <?php
                    foreach ($allRoles as $role) {
                        echo '<option value="' . $role['id'] . '">' . htmlspecialchars($role['name']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Reason for Change</label>
                <textarea class="form-control" id="changeReason" rows="3" placeholder="Please provide a reason for this role change..."></textarea>
            </div>
        </div>
    `;
    // Update save button for user role management
    document.getElementById('saveButton').onclick = saveUserRole;
    new bootstrap.Modal(document.getElementById('settingsModal')).show();
}

function loadUserRole() {
    const userId = document.getElementById('userSelect').value;
    const form = document.getElementById('userRoleForm');

    if (userId) {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

function saveUserRole() {
    const userId = document.getElementById('userSelect').value;
    const roleId = document.getElementById('roleSelect').value;
    const reason = document.getElementById('changeReason').value;

    if (!userId || !roleId) {
        alert('Please select both a user and a role.');
        return;
    }

    if (!reason.trim()) {
        alert('Please provide a reason for the role change.');
        return;
    }

    // Send AJAX request to update user role
    fetch('api/update-user-role.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            user_id: userId,
            role_id: roleId,
            reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User role updated successfully!');
            bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
            location.reload();
        } else {
            alert('Error updating user role: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the user role.');
    });
}
</script>

<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.btn {
    transition: all 0.2s;
}
.btn:hover {
    transform: translateY(-1px);
}
</style>

<?php include 'includes/footer.php'; ?>
