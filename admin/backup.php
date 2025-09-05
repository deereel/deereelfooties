<?php
session_start();

require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if user is Super Admin or Admin
$userId = $_SESSION['admin_user_id'];
$userRole = getUserRole($userId);
$isAllowed = ($userRole && $userRole['name'] === 'super_admin');

if (!$isAllowed) {
    // Show access denied message for non-super admin/admin users
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Access Denied - Backup Management</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background-color: #f8f9fa; }
            .access-denied { max-width: 500px; margin: 100px auto; text-align: center; }
        </style>
    </head>
    <body>
        <?php include \'includes/header.php\'; ?>
        <div class="container">
            <div class="access-denied">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="mb-4">
                            <i class="bi bi-shield-x text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="card-title text-danger mb-3">Access Denied</h2>
                        <p class="card-text text-muted mb-4">
                            You do not have permission to access the Backup Management page.<br>
                            Only Super Admin and Admin users can access this area.
                        </p>
                        <div class="d-grid gap-2">
                            <a href="index.php" class="btn btn-primary">
                                <i class="bi bi-house-door me-2"></i>Return to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>';
    exit;
}

// Backup directory
$backupDir = __DIR__ . '/backups';

// Create backup directory if not exists
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// Handle backup creation
if (isset($_POST['create_backup'])) {
    $filename = 'backup_' . date('Ymd_His') . '.sql';
    $filepath = $backupDir . '/' . $filename;

    // Check if mysqldump is available
    $mysqldumpAvailable = false;
    if (function_exists('exec')) {
        exec('which mysqldump 2>/dev/null', $output, $returnVar);
        $mysqldumpAvailable = ($returnVar === 0 && !empty($output));
    }

    if ($mysqldumpAvailable) {
        // Use mysqldump to create backup
        $dbHost = $host;
        $dbUser = $user;
        $dbPass = $pass;
        $dbName = $db;

        // Escape password for shell
        $escapedPass = escapeshellarg($dbPass);
        $command = "mysqldump --user={$dbUser} --password={$escapedPass} --host={$dbHost} {$dbName} > {$filepath}";
        exec($command . ' 2>&1', $output, $returnVar);

        if ($returnVar === 0) {
            $message = "Backup created successfully: {$filename}";
            // Log backup creation
            logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'backup_database', 'system', 'backup', null, null, ['filename' => $filename]);
        } else {
            $error = "Backup failed with mysqldump. Error: " . implode(' ', $output);
        }
    } else {
        // Fallback: Use PHP to create backup
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);

            // Get all tables
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $sql = "-- DRF Database Backup\n";
            $sql .= "-- Created: " . date('Y-m-d H:i:s') . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

            foreach ($tables as $table) {
                // Get table structure
                $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
                $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
                $sql .= $createTable['Create Table'] . ";\n\n";

                // Get table data
                $stmt = $pdo->query("SELECT * FROM `$table`");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($rows)) {
                    $columns = array_keys($rows[0]);
                    $sql .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES\n";

                    $values = [];
                    foreach ($rows as $row) {
                        $rowValues = [];
                        foreach ($row as $value) {
                            if ($value === null) {
                                $rowValues[] = 'NULL';
                            } else {
                                $rowValues[] = $pdo->quote($value);
                            }
                        }
                        $values[] = "(" . implode(', ', $rowValues) . ")";
                    }

                    $sql .= implode(",\n", $values) . ";\n\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

            // Write to file
            if (file_put_contents($filepath, $sql)) {
                $message = "Backup created successfully using PHP fallback: {$filename}";
            } else {
                $error = "Failed to write backup file. Check directory permissions.";
            }

        } catch (Exception $e) {
            $error = "Backup failed: " . $e->getMessage();
        }
    }
}

// Handle backup deletion
if (isset($_POST['delete_backup']) && !empty($_POST['backup_file'])) {
    $fileToDelete = basename($_POST['backup_file']);
    $filePath = $backupDir . '/' . $fileToDelete;
    if (file_exists($filePath)) {
        unlink($filePath);
        $message = "Backup deleted: {$fileToDelete}";
        // Log backup deletion
        logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'delete_backup', 'system', 'backup', null, null, ['filename' => $fileToDelete]);
    } else {
        $error = "Backup file not found.";
    }
}

// List backups
$backups = array_diff(scandir($backupDir), array('.', '..'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Backup Management - DRF Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="admin-layout">
        <?php include 'includes/sidebar.php'; ?>

        <div class="main-content">
            <main>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="bi bi-database-check me-2"></i>
                        Database Backup Management
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <form method="post" style="display: inline;">
                                <button type="submit" name="create_backup" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i>Create Backup
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Backup Information -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-info-circle me-2"></i>Backup Information
                                </h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <small class="text-muted">Total Backups</small>
                                            <div class="h4 mb-0"><?php echo count($backups); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <small class="text-muted">Backup Directory</small>
                                            <div class="text-truncate" title="<?php echo htmlspecialchars($backupDir); ?>">
                                                <?php echo htmlspecialchars(basename($backupDir)); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <small class="text-muted">Last Backup</small>
                                            <div>
                                                <?php
                                                if (count($backups) > 0) {
                                                    $latestBackup = '';
                                                    $latestTime = 0;
                                                    foreach ($backups as $backup) {
                                                        $fileTime = filemtime($backupDir . '/' . $backup);
                                                        if ($fileTime > $latestTime) {
                                                            $latestTime = $fileTime;
                                                            $latestBackup = $backup;
                                                        }
                                                    }
                                                    echo htmlspecialchars($latestBackup) . '<br><small class="text-muted">' . date("M j, Y H:i", $latestTime) . '</small>';
                                                } else {
                                                    echo 'No backups yet';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Existing Backups -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-archive me-2"></i>Existing Backups
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (count($backups) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th><i class="bi bi-file-earmark-text me-1"></i>Filename</th>
                                                    <th><i class="bi bi-file-binary me-1"></i>Size</th>
                                                    <th><i class="bi bi-calendar me-1"></i>Created</th>
                                                    <th><i class="bi bi-gear me-1"></i>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($backups as $backup):
                                                    $filePath = $backupDir . '/' . $backup;
                                                    $fileSize = round(filesize($filePath) / 1024, 2);
                                                    $fileTime = filemtime($filePath);
                                                ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($backup); ?></strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary"><?php echo $fileSize; ?> KB</span>
                                                    </td>
                                                    <td>
                                                        <?php echo date("M j, Y H:i", $fileTime); ?>
                                                        <br><small class="text-muted"><?php echo date("l", $fileTime); ?></small>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="backups/<?php echo urlencode($backup); ?>"
                                                               class="btn btn-sm btn-success"
                                                               download
                                                               title="Download Backup">
                                                                <i class="bi bi-download me-1"></i>Download
                                                            </a>
                                                            <form method="post" style="display:inline-block;">
                                                                <input type="hidden" name="backup_file" value="<?php echo htmlspecialchars($backup); ?>">
                                                                <button type="submit"
                                                                        name="delete_backup"
                                                                        class="btn btn-sm btn-danger"
                                                                        onclick="return confirm('Are you sure you want to delete this backup?\n\nFile: <?php echo htmlspecialchars($backup); ?>\n\nThis action cannot be undone.')"
                                                                        title="Delete Backup">
                                                                    <i class="bi bi-trash me-1"></i>Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="bi bi-archive text-muted" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3 text-muted">No Backups Found</h5>
                                        <p class="text-muted">Create your first backup to get started with database protection.</p>
                                        <form method="post" style="display: inline;">
                                            <button type="submit" name="create_backup" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-1"></i>Create First Backup
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
