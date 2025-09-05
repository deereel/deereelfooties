<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/middleware/PermissionMiddleware.php';

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
        <title>Access Denied - Database Structure Check</title>
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
                            You do not have permission to access the Database Structure Check page.<br>
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

try {
    // $pdo is already available globally from auth/db.php
    echo "<h2>Database Structure Check</h2>";
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

        // Display structure for each table
        echo "<h3>Table Structures:</h3>";
        foreach ($tables as $table) {
            $tableName = $table[0];
            echo "<h4>{$tableName} Table Structure:</h4>";

            try {
                $stmt = $pdo->query("DESCRIBE `{$tableName}`");
                $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($columns) {
                    echo "<div style='margin-bottom: 30px;'>";
                    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                    echo "<thead>";
                    echo "<tr style='background-color: #f8f9fa;'>";
                    echo "<th style='padding: 8px; text-align: left; border: 1px solid #ddd;'>Field</th>";
                    echo "<th style='padding: 8px; text-align: left; border: 1px solid #ddd;'>Type</th>";
                    echo "<th style='padding: 8px; text-align: left; border: 1px solid #ddd;'>Null</th>";
                    echo "<th style='padding: 8px; text-align: left; border: 1px solid #ddd;'>Key</th>";
                    echo "<th style='padding: 8px; text-align: left; border: 1px solid #ddd;'>Default</th>";
                    echo "<th style='padding: 8px; text-align: left; border: 1px solid #ddd;'>Extra</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    foreach ($columns as $column) {
                        echo "<tr>";
                        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($column['Field']) . "</td>";
                        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($column['Type']) . "</td>";
                        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($column['Null']) . "</td>";
                        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($column['Key']) . "</td>";
                        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($column['Default'] ?? 'NULL') . "</td>";
                        echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($column['Extra']) . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "<p><em>Columns: " . count($columns) . "</em></p>";
                    echo "</div>";
                } else {
                    echo "<p style='color: #dc3545;'>⚠️ No columns found for table '{$tableName}'</p>";
                }
            } catch (Exception $e) {
                echo "<p style='color: #dc3545;'>❌ Error describing table '{$tableName}': " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
    }

} catch (Exception $e) {
    echo "<p style='color: #dc3545;'>❌ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
