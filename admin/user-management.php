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

// Fetch all users
$allUsers = fetchData('admin_users', [], '*', 'username ASC');

// Log user management viewing activity
logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'view_user_management', 'user', 'read', null, 'Viewed user management page');

// Fetch all roles
$allRoles = fetchData('roles', [], '*', 'name ASC');

// Fetch all permissions
$allPermissions = fetchData('permissions', [], '*', 'name ASC');

$pageTitle = "User Management";
include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">
                        <i class="bi bi-people me-2"></i>
                        User Management
                    </h1>
                    <div class="badge bg-primary fs-6">
                        <i class="bi bi-shield-check me-1"></i>
                        Super Admin Access
                    </div>
                </div>
            </div>
        </div>

        <!-- Users List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list me-2"></i>
                            Admin Users
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allUsers as $user): ?>
                                        <?php
                                        $userRole = getUserRole($user['id']);
                                        $roleName = $userRole ? $userRole['name'] : 'No Role';
                                        ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo htmlspecialchars($roleName); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                            <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="editUser(<?php echo $user['id']; ?>)">
                                                    <i class="bi bi-pencil me-1"></i>
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="user_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" id="editUsername" name="username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-control" id="editRole" name="role_id" required>
                            <option value="">Select Role</option>
                            <?php foreach ($allRoles as $role): ?>
                                <option value="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Permissions (Role-based)</label>
                        <div id="permissionsList">
                            <?php foreach ($allPermissions as $permission): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="perm_<?php echo $permission['id']; ?>" value="<?php echo $permission['id']; ?>" disabled>
                                    <label class="form-check-label" for="perm_<?php echo $permission['id']; ?>">
                                        <?php echo htmlspecialchars($permission['name']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <small class="form-text text-muted">Permissions are automatically assigned based on the selected role.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason for Changes</label>
                        <textarea class="form-control" id="editReason" name="reason" rows="3" placeholder="Please provide a reason for these changes..." required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveUserChanges()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
function editUser(userId) {
    // Fetch user data
    fetch(`api/get-user.php?id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.user;
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editUsername').value = user.username;
                document.getElementById('editEmail').value = user.email;
                document.getElementById('editRole').value = user.role_id || '';

                // Load user permissions
                loadUserPermissions(userId);

                new bootstrap.Modal(document.getElementById('editUserModal')).show();
            } else {
                alert('Error loading user data: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading user data.');
        });
}

function loadUserPermissions(userId) {
    fetch(`api/get-user-permissions.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reset all checkboxes
                document.querySelectorAll('#permissionsList input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Check permissions that the user has
                data.permissions.forEach(perm => {
                    const checkbox = document.getElementById(`perm_${perm.id}`);
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading permissions:', error);
        });
}

function saveUserChanges() {
    const form = document.getElementById('editUserForm');
    const formData = new FormData(form);

    const userData = {
        user_id: formData.get('user_id'),
        username: formData.get('username'),
        email: formData.get('email'),
        role_id: formData.get('role_id'),
        reason: formData.get('reason')
    };

    // Validate
    if (!userData.username || !userData.email || !userData.role_id) {
        alert('Please fill in all required fields.');
        return;
    }

    if (!userData.reason.trim()) {
        alert('Please provide a reason for the changes.');
        return;
    }

    // Send update request
    fetch('api/update-user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(userData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User updated successfully!');
            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
            location.reload();
        } else {
            alert('Error updating user: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the user.');
    });
}
</script>

<style>
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

.form-check {
    margin-bottom: 0.5rem;
}
</style>

<?php include 'includes/footer.php'; ?>
