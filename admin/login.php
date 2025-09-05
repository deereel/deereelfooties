<?php
session_start();
require_once '../auth/db.php';

// Redirect if already logged in
if (isset($_SESSION['admin_user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
        logLoginAttempt($username, 'failed', 'Missing credentials');
    } else {
        // Check if account is locked
        $lockoutInfo = checkAccountLockout($username);

        if ($lockoutInfo['is_locked']) {
            $error = "Account is temporarily locked due to too many failed login attempts. Please try again in {$lockoutInfo['remaining_time']} minutes.";
            logLoginAttempt($username, 'locked', 'Account locked due to failed attempts');
        } else {
            // Get admin user by username
            $user = getAdminUserByUsername($username);

            if ($user && verifyAdminPassword($password, $user['password'])) {
                // Login successful
                $_SESSION['admin_user_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_role'] = $user['role_name'];
                $_SESSION['admin_name'] = $user['first_name'] . ' ' . $user['last_name'];

                // Update last login
                updateData('admin_users', ['last_login' => date('Y-m-d H:i:s')], ['id' => $user['id']]);

                // Log successful login
                logLoginAttempt($username, 'success');

                // Log activity
                logActivity($user['id'], $user['username'], 'login_success', 'authentication');

                header('Location: index.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
                logLoginAttempt($username, 'failed', 'Invalid credentials');
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DeeReel Footies</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Admin Login</h1>
            <p class="text-gray-600 mt-2">DeeReel Footies Admin Panel</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2"></i>Username
                </label>
                <input type="text"
                       id="username"
                       name="username"
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Enter your username"
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2"></i>Password
                </label>
                <input type="password"
                       id="password"
                       name="password"
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="Enter your password">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                <i class="fas fa-sign-in-alt mr-2"></i>Login
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Demo Accounts:</p>
            <div class="mt-2 space-y-1">
                <p><strong>Super Admin:</strong> oladayo / admin333</p>
                <p><strong>Admin:</strong> temmy / admin222</p>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="../index.php" class="text-blue-600 hover:text-blue-800 text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Website
            </a>
        </div>
    </div>
</body>
</html>
