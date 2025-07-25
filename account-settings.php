<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';

// Get current user data
$currentUser = $_SESSION['user'] ?? null;
$userId = $currentUser['id'] ?? $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: /login.php');
    exit;
}

// Handle form submission
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validate required fields
        if (empty($name) || empty($email)) {
            throw new Exception('Name and email are required.');
        }
        
        // Check if email is already taken by another user
        $emailCheckStmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
        $emailCheckStmt->execute([$email, $userId]);
        if ($emailCheckStmt->rowCount() > 0) {
            throw new Exception('Email address is already in use by another account.');
        }
        
        // If password change is requested
        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                throw new Exception('Current password is required to change password.');
            }
            
            if ($newPassword !== $confirmPassword) {
                throw new Exception('New passwords do not match.');
            }
            
            if (strlen($newPassword) < 6) {
                throw new Exception('New password must be at least 6 characters long.');
            }
            
            // Verify current password
            $userStmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ?");
            $userStmt->execute([$userId]);
            $userData = $userStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$userData || !password_verify($currentPassword, $userData['password'])) {
                throw new Exception('Current password is incorrect.');
            }
            
            // Update with new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, password = ? WHERE user_id = ?");
            $updateStmt->execute([$name, $email, $phone, $hashedPassword, $userId]);
        } else {
            // Update without password change
            $updateStmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE user_id = ?");
            $updateStmt->execute([$name, $email, $phone, $userId]);
        }
        
        // Update session data
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        if (isset($_SESSION['username'])) {
            $_SESSION['username'] = $name;
        }
        
        $success = 'Account settings updated successfully!';
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get current user data from database
try {
    $userStmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $userStmt->execute([$userId]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        header('Location: /login.php');
        exit;
    }
} catch (PDOException $e) {
    $error = 'Error loading user data.';
    $user = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings | DeeReel Footies</title>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
</head>
<body data-page="account-settings">
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <div class="flex items-center text-sm text-gray-500">
                    <a href="/index.php" class="hover:text-gray-700">Home</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-900">Account Settings</span>
                </div>
            </div>

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-light mb-2">Account Settings</h1>
                <p class="text-gray-600">Manage your personal information and account preferences</p>
            </div>

            <!-- Success/Error Messages -->
            <?php if ($success): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Account Settings Form -->
            <div class="bg-white shadow-sm border rounded-lg p-6">
                <form method="POST" class="space-y-6">
                    <!-- Personal Information Section -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Password Change Section -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                        <p class="text-sm text-gray-600 mb-4">Leave password fields empty if you don't want to change your password.</p>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                <input type="password" id="current_password" name="current_password" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <input type="password" id="new_password" name="new_password" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                                </div>
                                
                                <div>
                                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <input type="password" id="confirm_password" name="confirm_password" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
                        <div class="bg-gray-50 p-4 rounded-md">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Account ID:</span>
                                    <span class="text-gray-600">#<?php echo htmlspecialchars($user['user_id'] ?? ''); ?></span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Member Since:</span>
                                    <span class="text-gray-600"><?php echo date('F d, Y', strtotime($user['created_at'] ?? 'now')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-between items-center pt-6 border-t">
                        <a href="/dashboard.php" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                        </a>
                        
                        <div class="space-x-3">
                            <button type="button" onclick="window.location.reload()" 
                                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>

    <script>
        // Password validation
        document.getElementById('new_password').addEventListener('input', function() {
            const newPassword = this.value;
            const confirmPassword = document.getElementById('confirm_password');
            
            if (newPassword.length > 0 && newPassword.length < 6) {
                this.setCustomValidity('Password must be at least 6 characters long');
            } else {
                this.setCustomValidity('');
            }
            
            // Check if passwords match
            if (confirmPassword.value && newPassword !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
            } else {
                confirmPassword.setCustomValidity('');
            }
        });

        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            
            if (this.value && newPassword !== this.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });

        // Require current password if new password is entered
        document.getElementById('new_password').addEventListener('input', function() {
            const currentPassword = document.getElementById('current_password');
            
            if (this.value.length > 0) {
                currentPassword.required = true;
            } else {
                currentPassword.required = false;
            }
        });
    </script>
</body>
</html>