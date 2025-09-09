<?php
session_start();
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check permissions
try {
    $permissionMiddleware = new PermissionMiddleware('view_returns');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    header('Location: login.php');
    exit;
}

// Check if user can manage returns
$canManageReturns = false;
try {
    $managePermission = new PermissionMiddleware('manage_returns');
    $managePermission->handle();
    $canManageReturns = true;
} catch (Exception $e) {
    $canManageReturns = false;
}

// Check if user can process refunds
$canProcessRefunds = false;
try {
    $refundPermission = new PermissionMiddleware('process_refunds');
    $refundPermission->handle();
    $canProcessRefunds = true;
} catch (Exception $e) {
    $canProcessRefunds = false;
}

// Log activity function is already defined in auth/db.php

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_return':
                if (!$canManageReturns) {
                    $message = 'You do not have permission to create return requests.';
                    $messageType = 'danger';
                    break;
                }

                $orderId = $_POST['order_id'] ?? '';
                $customerId = $_POST['customer_id'] ?? '';
                $reason = $_POST['reason'] ?? '';
                $notes = trim($_POST['notes'] ?? '');

                if (empty($orderId) || empty($customerId) || empty($reason)) {
                    $message = 'Please fill in all required fields.';
                    $messageType = 'danger';
                    break;
                }

                try {
                    $stmt = $pdo->prepare("INSERT INTO returns (order_id, customer_id, reason, notes, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
                    $stmt->execute([$orderId, $customerId, $reason, $notes]);

                    $message = "Return request created successfully!";
                    $messageType = 'success';

                    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'create_return', 'returns', 'create', $pdo->lastInsertId(), "Created return request for order {$orderId}");

                } catch (Exception $e) {
                    $message = 'Failed to create return request: ' . $e->getMessage();
                    $messageType = 'danger';
                }
                break;

            case 'update_return':
                if (!$canManageReturns) {
                    $message = 'You do not have permission to update return requests.';
                    $messageType = 'danger';
                    break;
                }

                $returnId = $_POST['return_id'] ?? '';
                $status = $_POST['status'] ?? '';
                $notes = trim($_POST['notes'] ?? '');

                if (empty($returnId) || empty($status)) {
                    $message = 'Invalid return update request.';
                    $messageType = 'danger';
                    break;
                }

                try {
                    $stmt = $pdo->prepare("UPDATE returns SET status = ?, notes = ? WHERE id = ?");
                    $stmt->execute([$status, $notes, $returnId]);

                    $message = 'Return request updated successfully!';
                    $messageType = 'success';

                    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'update_return', 'returns', 'update', $returnId, "Updated return status to {$status}");

                } catch (Exception $e) {
                    $message = 'Failed to update return request: ' . $e->getMessage();
                    $messageType = 'danger';
                }
                break;

            case 'process_refund':
                if (!$canProcessRefunds) {
                    $message = 'You do not have permission to process refunds.';
                    $messageType = 'danger';
                    break;
                }

                $returnId = $_POST['return_id'] ?? '';
                $refundAmount = $_POST['refund_amount'] ?? 0;

                if (empty($returnId) || $refundAmount <= 0) {
                    $message = 'Invalid refund request.';
                    $messageType = 'danger';
                    break;
                }

                try {
                    $stmt = $pdo->prepare("INSERT INTO refunds (return_id, amount, processed_by, processed_at) VALUES (?, ?, ?, NOW())");
                    $stmt->execute([$returnId, $refundAmount, $_SESSION['admin_user_id']]);

                    $stmt = $pdo->prepare("UPDATE returns SET status = 'refunded' WHERE id = ?");
                    $stmt->execute([$returnId]);

                    $message = 'Refund processed successfully!';
                    $messageType = 'success';

                    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'process_refund', 'refunds', 'create', $pdo->lastInsertId(), "Processed refund for return {$returnId}");

                } catch (Exception $e) {
                    $message = 'Failed to process refund: ' . $e->getMessage();
                    $messageType = 'danger';
                }
                break;
        }
    }
}

// Pagination and filtering
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 20;
$statusFilter = $_GET['status'] ?? '';
$searchTerm = $_GET['search'] ?? '';

// Build query
$query = "SELECT r.*, o.order_id as order_number, u.name as customer_name FROM returns r
          LEFT JOIN orders o ON r.order_id = o.order_id
          LEFT JOIN users u ON r.customer_id = u.user_id
          WHERE 1=1";

$params = [];

if (!empty($statusFilter)) {
    $query .= " AND r.status = ?";
    $params[] = $statusFilter;
}

if (!empty($searchTerm)) {
    $query .= " AND (o.order_id LIKE ? OR u.name LIKE ? OR r.reason LIKE ?)";
    $searchParam = "%{$searchTerm}%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

$query .= " ORDER BY r.created_at DESC";

// Get total count for pagination
$countQuery = str_replace("SELECT r.*, o.order_number, u.username as customer_name", "SELECT COUNT(*)", $query);
$stmt = $pdo->prepare($countQuery);
$stmt->execute($params);
$totalReturns = $stmt->fetchColumn();
$totalPages = ceil($totalReturns / $perPage);

// Add pagination to main query
$query .= " LIMIT " . (($page - 1) * $perPage) . ", {$perPage}";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$returns = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Returns & Refunds - DRF Admin</title>
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
                    <h1><i class="bi bi-arrow-counterclockwise me-2"></i>Returns & Refunds</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createReturnModal">
                        <i class="bi bi-plus-circle me-1"></i>New Return Request
                    </button>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="GET" class="row g-3 mb-3">
                    <div class="col-md-3">
                        <select class="form-select" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" <?php echo $statusFilter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo $statusFilter === 'approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="received" <?php echo $statusFilter === 'received' ? 'selected' : ''; ?>>Received</option>
                            <option value="refunded" <?php echo $statusFilter === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
                            <option value="rejected" <?php echo $statusFilter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="search" placeholder="Search by order number, customer or reason" value="<?php echo htmlspecialchars($searchTerm); ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                    </div>
                </form>

                <?php if (empty($returns)): ?>
                    <div class="alert alert-info">No return requests found.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($returns as $return): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($return['order_number']); ?></td>
                                        <td><?php echo htmlspecialchars($return['customer_name']); ?></td>
                                        <td><?php echo htmlspecialchars($return['reason']); ?></td>
                                        <td><?php echo ucfirst($return['status']); ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($return['created_at'])); ?></td>
                                        <td>
                                            <?php if ($canManageReturns): ?>
                                                <button class="btn btn-sm btn-outline-secondary" onclick="editReturn(<?php echo $return['id']; ?>)">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($canProcessRefunds && $return['status'] === 'approved'): ?>
                                                <button class="btn btn-sm btn-outline-success" onclick="processRefund(<?php echo $return['id']; ?>)">
                                                    <i class="bi bi-cash-stack"></i> Refund
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <nav aria-label="Return requests pagination" class="mt-3">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&status=<?php echo urlencode($statusFilter); ?>&search=<?php echo urlencode($searchTerm); ?>">Previous</a>
                                </li>
                                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo urlencode($statusFilter); ?>&search=<?php echo urlencode($searchTerm); ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&status=<?php echo urlencode($statusFilter); ?>&search=<?php echo urlencode($searchTerm); ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <!-- Create Return Modal -->
    <div class="modal fade" id="createReturnModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Return Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create_return">
                        <div class="mb-3">
                            <label for="order_id" class="form-label">Order ID *</label>
                            <input type="text" class="form-control" id="order_id" name="order_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer ID *</label>
                            <input type="text" class="form-control" id="customer_id" name="customer_id" required>
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason *</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Return</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Return Modal -->
    <div class="modal fade" id="editReturnModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="editReturnForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Return Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_return">
                        <input type="hidden" name="return_id" id="edit_return_id">
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="received">Received</option>
                                <option value="refunded">Refunded</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Return</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Process Refund Modal -->
    <div class="modal fade" id="processRefundModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="processRefundForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Process Refund</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="process_refund">
                        <input type="hidden" name="return_id" id="refund_return_id">
                        <div class="mb-3">
                            <label for="refund_amount" class="form-label">Refund Amount</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="refund_amount" name="refund_amount" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Process Refund</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
    <script>
        function editReturn(returnId) {
            fetch(`api/return-details.php?return_id=${returnId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_return_id').value = data.id;
                    document.getElementById('edit_status').value = data.status;
                    document.getElementById('edit_notes').value = data.notes || '';
                    new bootstrap.Modal(document.getElementById('editReturnModal')).show();
                })
                .catch(() => alert('Failed to load return details.'));
        }

        function processRefund(returnId) {
            document.getElementById('refund_return_id').value = returnId;
            new bootstrap.Modal(document.getElementById('processRefundModal')).show();
        }
    </script>
</body>
</html>
