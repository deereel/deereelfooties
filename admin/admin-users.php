<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Include database connection and middleware
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if user has permission to manage users
$permissionMiddleware = new PermissionMiddleware('manage_users');
$permissionMiddleware->handle();

// Handle form submissions
$message = '';
$messageType = '';

// Clear any previous messages on page load (not POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // This ensures messages don't persist across page refreshes
    $message = '';
    $messageType = '';
}

// Clear messages after successful operations
if (isset($_GET['success'])) {
    $message = '';
    $messageType = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add_user') {
            // Add new admin user
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $firstName = trim($_POST['first_name']);
            $lastName = trim($_POST['last_name']);
            $roleId = (int)$_POST['role_id'];

            $userData = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'role_id' => $roleId,
                'is_active' => 1
            ];

            $result = insertData('admin_users', $userData);
            if (is_array($result) && isset($result['error'])) {
                // Check for duplicate entry errors and provide user-friendly messages
                if (strpos($result['error'], 'Duplicate entry') !== false) {
                    if (strpos($result['error'], 'username') !== false) {
                        $message = 'Error: Username already exists. Please choose a different username.';
                    } elseif (strpos($result['error'], 'email') !== false) {
                        $message = 'Error: Email address already exists. Please use a different email address.';
                    } else {
                        $message = 'Error: A user with this information already exists.';
                    }
                } else {
                    $message = 'Error creating user: ' . $result['error'];
                }
                $messageType = 'error';
            } else {
                $message = 'Admin user created successfully!';
                $messageType = 'success';
            }
        } elseif ($_POST['action'] === 'toggle_status') {
            // Toggle user active status
            $userId = (int)$_POST['user_id'];
            $currentStatus = (int)$_POST['current_status'];
            $newStatus = $currentStatus ? 0 : 1;

            $result = updateData('admin_users', ['is_active' => $newStatus], ['id' => $userId]);
            if (is_array($result) && isset($result['error'])) {
                $message = 'Error updating user status: ' . $result['error'];
                $messageType = 'error';
            } else {
                $message = 'User status updated successfully!';
                $messageType = 'success';
            }
        }
    }
}

// Get all admin users
$adminUsers = fetchData('admin_users', [], '*', 'created_at DESC');

// Get all roles for the form
$roles = fetchData('roles');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Users Management - DRF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="admin-layout">
        <?php include 'includes/sidebar.php'; ?>

        <div class="admin-content">
            <main>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Admin Users Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <span class="badge bg-primary fs-6">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</span>
                            <a href="?logout=1" class="btn btn-sm btn-outline-danger ms-2">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Debug info (remove this in production) -->
                <?php if (isset($_GET['debug'])): ?>
                    <div class="alert alert-info">
                        <strong>Debug Info:</strong><br>
                        Message: "<?php echo htmlspecialchars($message); ?>"<br>
                        Message Type: "<?php echo htmlspecialchars($messageType); ?>"<br>
                        Request Method: "<?php echo $_SERVER['REQUEST_METHOD']; ?>"<br>
                        Action: "<?php echo isset($_POST['action']) ? $_POST['action'] : 'none'; ?>"
                    </div>
                <?php endif; ?>

                <!-- Add New User Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Add New Admin User</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3">
                            <input type="hidden" name="action" value="add_user">

                            <div class="col-md-6">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name">
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name">
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="col-md-6">
                                <label for="role_id" class="form-label">Role *</label>
                                <select class="form-select" id="role_id" name="role_id" required>
                                    <option value="">Select Role</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-person-plus"></i> Create Admin User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Admin Users List -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Admin Users</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($adminUsers) > 0): ?>
                                        <?php foreach ($adminUsers as $user): ?>
                                            <tr>
                                                <td><?php echo $user['id']; ?></td>
                                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?php
                                                        $roleName = 'Unknown';
                                                        foreach ($roles as $role) {
                                                            if ($role['id'] == $user['role_id']) {
                                                                $roleName = $role['name'];
                                                                break;
                                                            }
                                                        }
                                                        echo htmlspecialchars($roleName);
                                                        ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $user['is_active'] ? 'success' : 'danger'; ?>">
                                                        <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo $user['last_login'] ? date('M d, Y H:i', strtotime($user['last_login'])) : 'Never'; ?></td>
                                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-outline-primary" onclick="editUser(<?php echo $user['id']; ?>)">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </button>
                                                        <a href="change-password.php?user_id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-info">
                                                            <i class="bi bi-key"></i> Password
                                                        </a>
                                                        <form method="POST" class="d-inline">
                                                            <input type="hidden" name="action" value="toggle_status">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <input type="hidden" name="current_status" value="<?php echo $user['is_active']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-outline-<?php echo $user['is_active'] ? 'warning' : 'success'; ?>">
                                                                <i class="bi bi-<?php echo $user['is_active'] ? 'pause' : 'play'; ?>"></i>
                                                                <?php echo $user['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No admin users found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="editUserId" name="user_id">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editUsername" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="editUsername" name="username" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editEmail" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editFirstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="editFirstName" name="first_name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editLastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="editLastName" name="last_name">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editRoleId" class="form-label">Role *</label>
                            <select class="form-select" id="editRoleId" name="role_id" required>
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Permissions</label>
                            <div id="permissionsContainer" class="border rounded p-3">
                                <p class="text-muted mb-2">Permissions are automatically assigned based on the selected role.</p>
                                <div id="permissionsList"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveUserChanges()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>

    <script>
    // Edit user functionality
    async function editUser(userId) {
        try {
            // Fetch user data
            const userResponse = await fetch(`../api/get-user.php?id=${userId}`);
            const userData = await userResponse.json();

            if (userData.success) {
                // Populate form with user data
                document.getElementById('editUserId').value = userData.user.id;
                document.getElementById('editUsername').value = userData.user.username;
                document.getElementById('editEmail').value = userData.user.email;
                document.getElementById('editFirstName').value = userData.user.first_name || '';
                document.getElementById('editLastName').value = userData.user.last_name || '';
                document.getElementById('editRoleId').value = userData.user.role_id;

                // Fetch and display permissions
                await loadUserPermissions(userData.user.role_id);

                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                modal.show();
            } else {
                alert('Error loading user data: ' + (userData.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while loading user data.');
        }
    }

    async function loadUserPermissions(roleId) {
        try {
            const response = await fetch(`../api/get-role-permissions.php?role_id=${roleId}`);
            const data = await response.json();

            const permissionsList = document.getElementById('permissionsList');
            if (data.success && data.permissions.length > 0) {
                permissionsList.innerHTML = data.permissions.map(permission => `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked disabled>
                        <label class="form-check-label">${permission.name}</label>
                    </div>
                `).join('');
            } else {
                permissionsList.innerHTML = '<p class="text-muted">No permissions assigned to this role.</p>';
            }
        } catch (error) {
            console.error('Error loading permissions:', error);
            document.getElementById('permissionsList').innerHTML = '<p class="text-danger">Error loading permissions.</p>';
        }
    }

    // Update permissions when role changes
    document.getElementById('editRoleId').addEventListener('change', function() {
        const roleId = this.value;
        if (roleId) {
            loadUserPermissions(roleId);
        } else {
            document.getElementById('permissionsList').innerHTML = '<p class="text-muted">Select a role to view permissions.</p>';
        }
    });

    async function saveUserChanges() {
        const form = document.getElementById('editUserForm');
        const formData = new FormData(form);

        // Validate required fields
        const userId = formData.get('user_id');
        const username = formData.get('username').trim();
        const email = formData.get('email').trim();
        const roleId = formData.get('role_id');
        const firstName = formData.get('first_name').trim();
        const lastName = formData.get('last_name').trim();

        if (!username || !email || !roleId) {
            alert('Please fill in all required fields.');
            return;
        }

        // Validate email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address.');
            return;
        }

        // Prepare data for JSON request
        const requestData = {
            user_id: userId,
            username: username,
            email: email,
            role_id: roleId,
            first_name: firstName,
            last_name: lastName,
            reason: 'User profile updated via admin panel' // Default reason
        };

        try {
            const response = await fetch('../api/update-user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData)
            });

            const result = await response.json();

            if (result.success) {
                alert('User updated successfully!');
                bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                location.reload();
            } else {
                alert('Error updating user: ' + (result.message || result.error || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while updating the user.');
        }
    }
    </script>
</body>
</html>
