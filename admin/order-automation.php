<?php
session_start();
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check permissions
try {
    $permissionMiddleware = new PermissionMiddleware('view_order_automation');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    header('Location: login.php');
    exit;
}

// Check if user can manage automation rules
$canManageAutomation = false;
try {
    $managePermission = new PermissionMiddleware('manage_order_automation');
    $managePermission->handle();
    $canManageAutomation = true;
} catch (Exception $e) {
    $canManageAutomation = false;
}

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_rule':
                if (!$canManageAutomation) {
                    $message = 'You do not have permission to create automation rules.';
                    $messageType = 'danger';
                    break;
                }

                $ruleName = trim($_POST['rule_name'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $triggerEvent = $_POST['trigger_event'] ?? '';
                $actions = json_decode($_POST['actions'] ?? '[]', true);
                $isActive = isset($_POST['is_active']) ? 1 : 0;
                $priority = intval($_POST['priority'] ?? 0);

                if (empty($ruleName) || empty($triggerEvent) || empty($actions)) {
                    $message = 'Please fill in all required fields.';
                    $messageType = 'danger';
                    break;
                }

                try {
                    $stmt = $pdo->prepare("INSERT INTO order_automation_rules (rule_name, description, trigger_event, actions, is_active, priority, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$ruleName, $description, $triggerEvent, json_encode($actions), $isActive, $priority, $_SESSION['admin_user_id']]);

                    $message = "Automation rule created successfully!";
                    $messageType = 'success';

                    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'create_automation_rule', 'order_automation_rules', 'create', $pdo->lastInsertId(), "Created automation rule: {$ruleName}");

                } catch (Exception $e) {
                    $message = 'Failed to create automation rule: ' . $e->getMessage();
                    $messageType = 'danger';
                }
                break;

            case 'update_rule':
                if (!$canManageAutomation) {
                    $message = 'You do not have permission to update automation rules.';
                    $messageType = 'danger';
                    break;
                }

                $ruleId = $_POST['rule_id'] ?? '';
                $ruleName = trim($_POST['rule_name'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $triggerEvent = $_POST['trigger_event'] ?? '';
                $actions = json_decode($_POST['actions'] ?? '[]', true);
                $isActive = isset($_POST['is_active']) ? 1 : 0;
                $priority = intval($_POST['priority'] ?? 0);

                if (empty($ruleId) || empty($ruleName) || empty($triggerEvent) || empty($actions)) {
                    $message = 'Please fill in all required fields.';
                    $messageType = 'danger';
                    break;
                }

                try {
                    $stmt = $pdo->prepare("UPDATE order_automation_rules SET rule_name = ?, description = ?, trigger_event = ?, actions = ?, is_active = ?, priority = ? WHERE id = ?");
                    $stmt->execute([$ruleName, $description, $triggerEvent, json_encode($actions), $isActive, $priority, $ruleId]);

                    $message = 'Automation rule updated successfully!';
                    $messageType = 'success';

                    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'update_automation_rule', 'order_automation_rules', 'update', $ruleId, "Updated automation rule: {$ruleName}");

                } catch (Exception $e) {
                    $message = 'Failed to update automation rule: ' . $e->getMessage();
                    $messageType = 'danger';
                }
                break;

            case 'delete_rule':
                if (!$canManageAutomation) {
                    $message = 'You do not have permission to delete automation rules.';
                    $messageType = 'danger';
                    break;
                }

                $ruleId = $_POST['rule_id'] ?? '';

                if (empty($ruleId)) {
                    $message = 'Invalid rule ID.';
                    $messageType = 'danger';
                    break;
                }

                try {
                    $stmt = $pdo->prepare("DELETE FROM order_automation_rules WHERE id = ?");
                    $stmt->execute([$ruleId]);

                    $message = 'Automation rule deleted successfully!';
                    $messageType = 'success';

                    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'delete_automation_rule', 'order_automation_rules', 'delete', $ruleId, "Deleted automation rule ID: {$ruleId}");

                } catch (Exception $e) {
                    $message = 'Failed to delete automation rule: ' . $e->getMessage();
                    $messageType = 'danger';
                }
                break;

            case 'toggle_rule':
                if (!$canManageAutomation) {
                    $message = 'You do not have permission to modify automation rules.';
                    $messageType = 'danger';
                    break;
                }

                $ruleId = $_POST['rule_id'] ?? '';
                $isActive = isset($_POST['is_active']) ? 1 : 0;

                if (empty($ruleId)) {
                    $message = 'Invalid rule ID.';
                    $messageType = 'danger';
                    break;
                }

                try {
                    $stmt = $pdo->prepare("UPDATE order_automation_rules SET is_active = ? WHERE id = ?");
                    $stmt->execute([$isActive, $ruleId]);

                    $statusText = $isActive ? 'activated' : 'deactivated';
                    $message = "Automation rule {$statusText} successfully!";
                    $messageType = 'success';

                    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'toggle_automation_rule', 'order_automation_rules', 'update', $ruleId, "Rule {$statusText}");

                } catch (Exception $e) {
                    $message = 'Failed to update rule status: ' . $e->getMessage();
                    $messageType = 'danger';
                }
                break;
        }
    }
}

// Get automation rules
$stmt = $pdo->query("SELECT * FROM order_automation_rules ORDER BY priority DESC, created_at DESC");
$automationRules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get automation logs (recent 50)
$stmt = $pdo->query("SELECT al.*, oar.rule_name FROM automation_logs al LEFT JOIN order_automation_rules oar ON al.rule_id = oar.id ORDER BY al.executed_at DESC LIMIT 50");
$automationLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

function logActivity($userId, $username, $action, $tableName, $operation, $recordId, $details) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, username, action, table_name, operation, record_id, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $username,
            $action,
            $tableName,
            $operation,
            $recordId,
            $details,
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
    } catch (Exception $e) {
        // Log to error log if activity logging fails
        error_log("Failed to log activity: " . $e->getMessage());
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Automation - DRF Admin</title>
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
                <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1><i class="bi bi-robot me-2"></i>Order Automation</h1>
                    <?php if ($canManageAutomation): ?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRuleModal">
                            <i class="bi bi-plus-circle me-1"></i>Create Rule
                        </button>
                    <?php endif; ?>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Automation Rules -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Automation Rules</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($automationRules)): ?>
                            <div class="alert alert-info">No automation rules found. Create your first rule to get started!</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th>Rule Name</th>
                                            <th>Trigger Event</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($automationRules as $rule): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($rule['rule_name']); ?></strong>
                                                    <?php if ($rule['description']): ?>
                                                        <br><small class="text-muted"><?php echo htmlspecialchars($rule['description']); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary"><?php echo ucfirst(str_replace('_', ' ', $rule['trigger_event'])); ?></span>
                                                </td>
                                                <td><?php echo $rule['priority']; ?></td>
                                                <td>
                                                    <?php if ($canManageAutomation): ?>
                                                        <form method="POST" class="d-inline">
                                                            <input type="hidden" name="action" value="toggle_rule">
                                                            <input type="hidden" name="rule_id" value="<?php echo $rule['id']; ?>">
                                                            <input type="hidden" name="is_active" value="<?php echo $rule['is_active'] ? '0' : '1'; ?>">
                                                            <button type="submit" class="btn btn-sm <?php echo $rule['is_active'] ? 'btn-success' : 'btn-secondary'; ?>">
                                                                <i class="bi bi-<?php echo $rule['is_active'] ? 'check-circle' : 'x-circle'; ?>"></i>
                                                                <?php echo $rule['is_active'] ? 'Active' : 'Inactive'; ?>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="badge bg-<?php echo $rule['is_active'] ? 'success' : 'secondary'; ?>">
                                                            <?php echo $rule['is_active'] ? 'Active' : 'Inactive'; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('Y-m-d H:i', strtotime($rule['created_at'])); ?></td>
                                                <td>
                                                    <?php if ($canManageAutomation): ?>
                                                        <button class="btn btn-sm btn-outline-primary" onclick="editRule(<?php echo $rule['id']; ?>)">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteRule(<?php echo $rule['id']; ?>, '<?php echo htmlspecialchars($rule['rule_name']); ?>')">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Automation Logs -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Recent Automation Logs</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($automationLogs)): ?>
                            <div class="alert alert-info">No automation logs found yet.</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th>Rule</th>
                                            <th>Order ID</th>
                                            <th>Action</th>
                                            <th>Status</th>
                                            <th>Executed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($automationLogs as $log): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($log['rule_name'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($log['order_id'] ?? 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($log['action_type']); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php
                                                        echo $log['execution_status'] === 'success' ? 'success' :
                                                             ($log['execution_status'] === 'failed' ? 'danger' : 'warning');
                                                    ?>">
                                                        <?php echo ucfirst($log['execution_status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('Y-m-d H:i', strtotime($log['executed_at'])); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Create Rule Modal -->
    <div class="modal fade" id="createRuleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Automation Rule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create_rule">
                        <div class="mb-3">
                            <label for="rule_name" class="form-label">Rule Name *</label>
                            <input type="text" class="form-control" id="rule_name" name="rule_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="trigger_event" class="form-label">Trigger Event *</label>
                            <select class="form-select" id="trigger_event" name="trigger_event" required>
                                <option value="">Select trigger event...</option>
                                <option value="order_created">Order Created</option>
                                <option value="payment_received">Payment Received</option>
                                <option value="order_shipped">Order Shipped</option>
                                <option value="order_delivered">Order Delivered</option>
                                <option value="custom">Custom Event</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <input type="number" class="form-control" id="priority" name="priority" value="0" min="0" max="100">
                            <small class="form-text text-muted">Higher numbers execute first (0-100)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Actions *</label>
                            <div id="actions-container">
                                <div class="action-item border rounded p-3 mb-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-select action-type" name="action_types[]" required>
                                                <option value="">Select action...</option>
                                                <option value="update_status">Update Status</option>
                                                <option value="send_email">Send Email</option>
                                                <option value="notify_admin">Notify Admin</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control action-value" name="action_values[]" placeholder="Value" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-action">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-action">
                                <i class="bi bi-plus-circle me-1"></i>Add Action
                            </button>
                            <input type="hidden" name="actions" id="actions-json">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Active (rule will execute when triggered)
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Rule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Rule Modal -->
    <div class="modal fade" id="editRuleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" id="editRuleForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Automation Rule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_rule">
                        <input type="hidden" name="rule_id" id="edit_rule_id">
                        <div class="mb-3">
                            <label for="edit_rule_name" class="form-label">Rule Name *</label>
                            <input type="text" class="form-control" id="edit_rule_name" name="rule_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_trigger_event" class="form-label">Trigger Event *</label>
                            <select class="form-select" id="edit_trigger_event" name="trigger_event" required>
                                <option value="order_created">Order Created</option>
                                <option value="payment_received">Payment Received</option>
                                <option value="order_shipped">Order Shipped</option>
                                <option value="order_delivered">Order Delivered</option>
                                <option value="custom">Custom Event</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_priority" class="form-label">Priority</label>
                            <input type="number" class="form-control" id="edit_priority" name="priority" value="0" min="0" max="100">
                            <small class="form-text text-muted">Higher numbers execute first (0-100)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Actions *</label>
                            <div id="edit-actions-container">
                                <!-- Actions will be populated by JavaScript -->
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="edit-add-action">
                                <i class="bi bi-plus-circle me-1"></i>Add Action
                            </button>
                            <input type="hidden" name="actions" id="edit-actions-json">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active">
                                <label class="form-check-label" for="edit_is_active">
                                    Active (rule will execute when triggered)
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Rule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
    <script>
        // Action management functions
        function addAction(containerId, actionType = '', actionValue = '') {
            const container = document.getElementById(containerId);
            const actionItem = document.createElement('div');
            actionItem.className = 'action-item border rounded p-3 mb-2';
            actionItem.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-select action-type" name="action_types[]" required>
                            <option value="">Select action...</option>
                            <option value="update_status" ${actionType === 'update_status' ? 'selected' : ''}>Update Status</option>
                            <option value="send_email" ${actionType === 'send_email' ? 'selected' : ''}>Send Email</option>
                            <option value="notify_admin" ${actionType === 'notify_admin' ? 'selected' : ''}>Notify Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control action-value" name="action_values[]" placeholder="Value" value="${actionValue}" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-action">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(actionItem);

            // Add event listener for remove button
            actionItem.querySelector('.remove-action').addEventListener('click', function() {
                actionItem.remove();
                updateActionsJson();
            });

            updateActionsJson();
        }

        function updateActionsJson() {
            const containers = ['actions-container', 'edit-actions-container'];
            containers.forEach(containerId => {
                const container = document.getElementById(containerId);
                if (!container) return;

                const actions = [];
                const actionItems = container.querySelectorAll('.action-item');
                actionItems.forEach(item => {
                    const type = item.querySelector('.action-type').value;
                    const value = item.querySelector('.action-value').value;
                    if (type && value) {
                        actions.push({ type, value });
                    }
                });

                const jsonInput = document.getElementById(containerId.replace('-container', '-json'));
                if (jsonInput) {
                    jsonInput.value = JSON.stringify(actions);
                }
            });
        }

        // Event listeners
        document.getElementById('add-action').addEventListener('click', () => addAction('actions-container'));
        document.getElementById('edit-add-action').addEventListener('click', () => addAction('edit-actions-container'));

        // Update JSON when inputs change
        document.addEventListener('input', updateActionsJson);
        document.addEventListener('change', updateActionsJson);

        // Initialize with one action for create modal
        document.addEventListener('DOMContentLoaded', () => {
            addAction('actions-container');
        });

        function editRule(ruleId) {
            fetch(`api/automation-rule-details.php?rule_id=${ruleId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_rule_id').value = data.id;
                    document.getElementById('edit_rule_name').value = data.rule_name;
                    document.getElementById('edit_description').value = data.description || '';
                    document.getElementById('edit_trigger_event').value = data.trigger_event;
                    document.getElementById('edit_priority').value = data.priority;
                    document.getElementById('edit_is_active').checked = data.is_active;

                    // Clear existing actions
                    const container = document.getElementById('edit-actions-container');
                    container.innerHTML = '';

                    // Add existing actions
                    const actions = JSON.parse(data.actions || '[]');
                    actions.forEach(action => {
                        addAction('edit-actions-container', action.type, action.value);
                    });

                    // Add at least one empty action if none exist
                    if (actions.length === 0) {
                        addAction('edit-actions-container');
                    }

                    new bootstrap.Modal(document.getElementById('editRuleModal')).show();
                })
                .catch(() => alert('Failed to load rule details.'));
        }

        function deleteRule(ruleId, ruleName) {
            if (confirm(`Are you sure you want to delete the rule "${ruleName}"? This action cannot be undone.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_rule">
                    <input type="hidden" name="rule_id" value="${ruleId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
