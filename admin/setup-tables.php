<?php
session_start();
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if user is logged in
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if user is Super Admin
$userId = $_SESSION['admin_user_id'];
$userRole = getUserRole($userId);
$isSuperAdmin = ($userRole && $userRole['name'] === 'super_admin');

if (!$isSuperAdmin) {
    // Show access denied message for non-super admin users
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Access Denied - Setup Tables</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background-color: #f8f9fa; }
            .access-denied { max-width: 500px; margin: 100px auto; text-align: center; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="access-denied">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="bi bi-shield-x text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="card-title text-danger mb-3">Access Denied</h2>
                        <p class="card-text text-muted mb-4">
                            You do not have permission to access the Setup Tables page.<br>
                            Only Super Admin users can access this area.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="index.php" class="btn btn-primary">
                                <i class="bi bi-house-door me-2"></i>Return to Dashboard
                            </a>
                            <a href="login.php" class="btn btn-outline-secondary">
                                <i class="bi bi-box-arrow-right me-2"></i>Login as Different User
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Auto redirect after 5 seconds
            setTimeout(function() {
                window.location.href = "index.php";
            }, 5000);
        </script>
    </body>
    </html>';
    exit;
}

// Script to set up necessary tables for the admin interface

try {
    echo "<h2>Database Table Setup Check</h2>";
    echo "<p><strong>Generated on:</strong> " . date('Y-m-d H:i:s') . "</p>";

    // Get all tables in the database
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_NUM);

    if (empty($tables)) {
        echo "<p>No tables found in the database.</p>";
    } else {
        echo "<h3>All Tables in Database (" . count($tables) . " tables found):</h3>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li><strong>" . $table[0] . "</strong></li>";
        }
        echo "</ul>";

        // Attempt to create all tables found in database
        echo "<h3>All Tables Creation Check:</h3>";
        echo "<p>Attempting to create all tables found in the database...</p>";

        $createdCount = 0;
        $existingCount = 0;
        $errorCount = 0;

        foreach ($tables as $table) {
            $tableName = $table[0];

            try {
                // Get the CREATE TABLE statement for this table
                $stmt = $pdo->prepare("SHOW CREATE TABLE `$tableName`");
                $stmt->execute();
                $createResult = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($createResult && isset($createResult['Create Table'])) {
                    $createStatement = $createResult['Create Table'];

                    // Replace CREATE TABLE with CREATE TABLE IF NOT EXISTS
                    $createStatement = preg_replace('/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $createStatement);

                    // Execute the CREATE TABLE IF NOT EXISTS statement
                    $pdo->exec($createStatement);
                    echo "✅ $tableName table created or already exists.<br>";
                    $createdCount++;
                } else {
                    echo "⚠️ Could not get CREATE statement for table: $tableName<br>";
                    $errorCount++;
                }

            } catch (PDOException $e) {
                echo "❌ Error creating table $tableName: " . $e->getMessage() . "<br>";
                $errorCount++;
            }
        }

        echo "<br><strong>Summary:</strong><br>";
        echo "✅ Tables processed: $createdCount<br>";
        echo "❌ Errors: $errorCount<br>";
        echo "📊 Total tables found: " . count($tables) . "<br>";

        if ($createdCount > 0) {
            echo "<br>🎉 All tables have been verified/created successfully!";
        }

        if ($errorCount > 0) {
            echo "<br>⚠️ Some tables had errors during creation. Check the messages above for details.";
        }
    }

} catch (PDOException $e) {
    echo "❌ Error setting up tables: " . $e->getMessage();
}
?>