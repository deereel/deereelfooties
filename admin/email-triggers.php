<?php
session_start();

require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

// Check permission to manage email triggers
$permissionMiddleware = new PermissionMiddleware('manage_email_triggers');
try {
    $permissionMiddleware->handle();
} catch (Exception $e) {
    echo "Access denied: " . $e->getMessage();
    exit;
}

// Fetch existing email triggers
$stmt = $pdo->query("SELECT * FROM email_triggers ORDER BY created_at DESC");
$emailTriggers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submissions for creating or updating triggers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $eventType = $_POST['event_type'] ?? '';
    $conditions = $_POST['conditions'] ?? '';
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($name) && !empty($eventType)) {
        if (isset($_POST['trigger_id']) && is_numeric($_POST['trigger_id'])) {
            // Update existing trigger
            $stmt = $pdo->prepare("UPDATE email_triggers SET name = ?, event_type = ?, conditions = ?, is_active = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$name, $eventType, $conditions, $isActive, $_POST['trigger_id']]);
        } else {
            // Create new trigger
            $stmt = $pdo->prepare("INSERT INTO email_triggers (name, event_type, conditions, is_active) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $eventType, $conditions, $isActive]);
        }
        header("Location: email-triggers.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Email Triggers Management - DeeReel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/admin.css" rel="stylesheet" />
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="admin-layout">
        <?php include 'includes/sidebar.php'; ?>
        <div class="admin-content">
            <main>
                <h2>Email Triggers Management</h2>
                <form method="POST" class="mb-4">
                    <input type="hidden" name="trigger_id" id="trigger_id" />
                    <div class="mb-3">
                        <label for="name" class="form-label">Trigger Name</label>
                        <input type="text" class="form-control" id="name" name="name" required />
                    </div>
                    <div class="mb-3">
                        <label for="event_type" class="form-label">Event Type</label>
                        <input type="text" class="form-control" id="event_type" name="event_type" required />
                    </div>
                    <div class="mb-3">
                        <label for="conditions" class="form-label">Conditions (JSON)</label>
                        <textarea class="form-control" id="conditions" name="conditions" rows="3"></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked />
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Trigger</button>
                    <button type="button" class="btn btn-secondary" onclick="clearForm()">Clear</button>
                </form>

                <h3>Existing Triggers</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Event Type</th>
                            <th>Conditions</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($emailTriggers as $trigger): ?>
                        <tr>
                            <td><?= htmlspecialchars($trigger['name']) ?></td>
                            <td><?= htmlspecialchars($trigger['event_type']) ?></td>
                            <td><pre><?= htmlspecialchars($trigger['conditions']) ?></pre></td>
                            <td><?= $trigger['is_active'] ? 'Yes' : 'No' ?></td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="editTrigger(<?= $trigger['id'] ?>, '<?= htmlspecialchars(addslashes($trigger['name'])) ?>', '<?= htmlspecialchars(addslashes($trigger['event_type'])) ?>', '<?= htmlspecialchars(addslashes($trigger['conditions'])) ?>', <?= $trigger['is_active'] ?>)">Edit</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
    <script>
        function editTrigger(id, name, eventType, conditions, isActive) {
            document.getElementById('trigger_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('event_type').value = eventType;
            document.getElementById('conditions').value = conditions;
            document.getElementById('is_active').checked = isActive === 1;
        }
        function clearForm() {
            document.getElementById('trigger_id').value = '';
            document.getElementById('name').value = '';
            document.getElementById('event_type').value = '';
            document.getElementById('conditions').value = '';
            document.getElementById('is_active').checked = true;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
