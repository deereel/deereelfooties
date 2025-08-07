<?php
session_start();
require_once 'auth/db.php';
require_once 'auth/security.php';

$token = $_GET['token'] ?? '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request';
    } else {
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (!validatePassword($password)) {
            $error = 'Password must be at least 8 characters with letters and numbers';
        } elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match';
        } else {
            try {
                $stmt = $pdo->prepare("SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW()");
                $stmt->execute([$token]);
                $reset = $stmt->fetch();
                
                if ($reset) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                    $stmt->execute([$hashedPassword, $reset['user_id']]);
                    
                    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
                    $stmt->execute([$token]);
                    
                    $success = 'Password updated successfully';
                } else {
                    $error = 'Invalid or expired reset token';
                }
            } catch (Exception $e) {
                $error = 'Server error';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password | DeeReel Footies</title>
    <?php include 'components/header.php'; ?>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Reset Password</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                            <a href="/" class="btn btn-primary">Login</a>
                        <?php else: ?>
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>