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

// Check if user has permission to manage users
$permissionMiddleware = new PermissionMiddleware('manage_users');
$permissionMiddleware->handle();

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = (int)$_POST['user_id'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($newPassword) || empty($confirmPassword)) {
        $message = 'Both password fields are required.';
        $messageType = 'error';
    } elseif ($newPassword !== $confirmPassword) {
        $message = 'Passwords do not match.';
        $messageType = 'error';
    } elseif (strlen($newPassword) < 6) {
        $message = 'Password must be at least 6 characters long.';
        $messageType = 'error';
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $result = updateData('admin_users', ['password' => $hashedPassword], ['id' => $userId]);
        if (is_array($result) && isset($result['error'])) {
            $message = 'Error changing password: ' . $result['error'];
            $messageType = 'error';
        } else {
            $message = 'Password changed successfully!';
            $messageType = 'success';
        }
    }
}

// Get user details if user_id is provided
$user = null;
if (isset($_GET['user_id'])) {
    $userId = (int)$_GET['user_id'];
    $users = fetchData('admin_users', ['id' => $userId]);
    if (!empty($users)) {
        $user = $users[0];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - DRF Admin</title>
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
                    <h1 class="h2">Change Password</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <span class="badge bg-primary fs-6">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</span>
                            <a href="admin-users.php" class="btn btn-sm btn-outline-secondary ms-2">
                                <i class="bi bi-arrow-left"></i> Back to Users
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

                <?php if ($user): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Change Password for <?php echo htmlspecialchars($user['username']); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
                                </div>
                            </div>

                            <form method="POST" class="row g-3">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">

                                <div class="col-md-6">
                                    <label for="new_password" class="form-label">New Password *</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                                    <div class="form-text">Password must be at least 6 characters long.</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="confirm_password" class="form-label">Confirm New Password *</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                                    <div class="form-text">Re-enter the new password to confirm.</div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-key"></i> Change Password
                                    </button>
                                    <a href="admin-users.php" class="btn btn-secondary ms-2">
                                        <i class="bi bi-x"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <h5>No User Selected</h5>
                        <p>Please select a user from the <a href="admin-users.php">admin users list</a> to change their password.</p>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
</body>
</html>
