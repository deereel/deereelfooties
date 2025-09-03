<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection and middleware
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if user has permission to manage roles
$permissionMiddleware = new PermissionMiddleware('manage_roles');
$permissionMiddleware->handle();

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_role':
                $result = createRole($_POST);
                break;
            case 'update_role':
                $result = updateRole($_POST);
                break;
            case 'delete_role':
                $result = deleteRole($_POST['role_id']);
                break;
            case 'assign_permissions':
                $result = assignPermissions($_POST);
                break;
        }

        if (isset($result['success'])) {
            $message = $result['success'];
            $messageType = 'success';
        } elseif (isset($result['error'])) {
            $message = $result['error'];
            $messageType = 'danger';
        }
    }
}

// Get all roles with their permissions
$roles = fetchData('roles r', [], 'r.*, GROUP_CONCAT(p.name) as permissions',
    'r.name ASC', 0, 'LEFT JOIN role_permissions rp ON r.id = rp.role_id LEFT JOIN permissions p ON rp.permission_id = p.id GROUP BY r.id');

// Get all permissions for assignment
$permissions = fetchData('permissions', [], '*', 'module ASC, name ASC');

function createRole($data) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("INSERT INTO roles (name, description, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$data['name'], $data['description']]);

        return ['success' => 'Role created successfully'];
    } catch (PDOException $e) {
        return ['error' => 'Error creating role: ' . $e->getMessage()];
    }
}

function updateRole($data) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("UPDATE roles SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['description'], $data['role_id']]);

        return ['success' => 'Role updated successfully'];
    } catch (PDOException $e) {
        return ['error' => 'Error updating role: ' . $e->getMessage()];
    }
}

function deleteRole($roleId) {
    global $pdo;

    try {
        // Check if role is assigned to any users
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role_id = ?");
        $stmt->execute([$roleId]);
        $userCount = $stmt->fetchColumn();

        if ($userCount > 0) {
            return ['error' => 'Cannot delete role: It is assigned to ' . $userCount . ' user(s). Please reassign users first.'];
        }

        $stmt = $pdo->prepare("DELETE FROM roles WHERE id = ?");
        $stmt->execute([$roleId]);

        return ['success' => 'Role deleted successfully'];
    } catch (PDOException $e) {
        return ['error' => 'Error deleting role: ' . $e->getMessage()];
    }
}

function assignPermissions($data) {
    global $pdo;

    try {
        $roleId = $data['role_id'];

        // Remove all existing permissions for this role
        $stmt = $pdo->prepare("DELETE FROM role_permissions WHERE role_id = ?");
        $stmt->execute([$roleId]);

        // Add new permissions
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $stmt = $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
            foreach ($data['permissions'] as $permissionId) {
                $stmt->execute([$roleId, $permissionId]);
            }
        }

        return ['success' => 'Permissions assigned successfully'];
    } catch (PDOException $e) {
        return ['error' => 'Error assigning permissions: ' . $e->getMessage()];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Management - DRF Admin</title>
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
                    <h1 class="h2">Role Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                            <i class="bi bi-plus-circle"></i> Create New Role
                        </button>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Roles Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Roles</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Permissions</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($roles as $role): ?>
                                        <tr>
                                            <td><?php echo $role['id']; ?></td>
                                            <td><?php echo htmlspecialchars($role['name']); ?></td>
                                            <td><?php echo htmlspecialchars($role['description']); ?></td>
                                            <td>
                                                <?php
                                                $perms = explode(',', $role['permissions']);
                                                foreach ($perms as $perm):
                                                    if (!empty($perm)):
                                                ?>
                                                    <span class="badge bg-secondary me-1"><?php echo htmlspecialchars($perm); ?></span>
                                                <?php
                                                    endif;
                                                endforeach;
                                                ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="editRole(<?php echo $role['id']; ?>)">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="managePermissions(<?php echo $role['id']; ?>)">
                                                    <i class="bi bi-shield"></i> Permissions
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteRole(<?php echo $role['id']; ?>)">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Create Role Modal -->
    <div class="modal fade" id="createRoleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create_role">
                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_role">
                        <input type="hidden" id="edit_role_id" name="role_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Manage Permissions Modal -->
    <div class="modal fade" id="managePermissionsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Manage Permissions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="assign_permissions">
                        <input type="hidden" id="perm_role_id" name="role_id">
                        <div id="permissions-list">
                            <!-- Permissions will be loaded here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Permissions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteRoleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this role? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete_role">
                        <input type="hidden" id="delete_role_id" name="role_id">
                        <button type="submit" class="btn btn-danger">Delete Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editRole(roleId) {
            // Fetch role data and populate edit modal
            fetch(`../api/get-role.php?id=${roleId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const role = data.role;
                        document.getElementById('edit_role_id').value = role.id;
                        document.getElementById('edit_name').value = role.name;
                        document.getElementById('edit_description').value = role.description;
                        new bootstrap.Modal(document.getElementById('editRoleModal')).show();
                    }
                });
        }

        function managePermissions(roleId) {
            document.getElementById('perm_role_id').value = roleId;

            // Load permissions for this role
            fetch(`../api/get-role-permissions.php?role_id=${roleId}`)
                .then(response => response.json())
                .then(data => {
                    const permissionsList = document.getElementById('permissions-list');
                    permissionsList.innerHTML = '';

                    if (data.permissions && data.permissions.length > 0) {
                        const groupedPermissions = {};
                        data.permissions.forEach(perm => {
                            if (!groupedPermissions[perm.module]) {
                                groupedPermissions[perm.module] = [];
                            }
                            groupedPermissions[perm.module].push(perm);
                        });

                        Object.keys(groupedPermissions).forEach(module => {
                            const moduleDiv = document.createElement('div');
                            moduleDiv.className = 'mb-3';
                            moduleDiv.innerHTML = `<h6>${module.charAt(0).toUpperCase() + module.slice(1)}</h6>`;

                            groupedPermissions[module].forEach(perm => {
                                const checkbox = document.createElement('div');
                                checkbox.className = 'form-check';
                                checkbox.innerHTML = `
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="${perm.id}" id="perm_${perm.id}" ${perm.assigned ? 'checked' : ''}>
                                    <label class="form-check-label" for="perm_${perm.id}">
                                        ${perm.name} - ${perm.description}
                                    </label>
                                `;
                                moduleDiv.appendChild(checkbox);
                            });

                            permissionsList.appendChild(moduleDiv);
                        });
                    }

                    new bootstrap.Modal(document.getElementById('managePermissionsModal')).show();
                });
        }

        function deleteRole(roleId) {
            document.getElementById('delete_role_id').value = roleId;
            new bootstrap.Modal(document.getElementById('deleteRoleModal')).show();
        }
    </script>
</body>
</html>
