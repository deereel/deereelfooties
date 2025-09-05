<?php
session_start();
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check permissions
try {
    $permissionMiddleware = new PermissionMiddleware('view_support_tickets');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    header('Location: login.php');
    exit;
}

// Check if user can manage tickets
$canManageTickets = false;
try {
    $managePermission = new PermissionMiddleware('manage_support_tickets');
    $managePermission->handle();
    $canManageTickets = true;
} catch (Exception $e) {
    $canManageTickets = false;
}

// Log activity
logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'view_support_tickets', 'support', 'read', null, 'Viewed support tickets management page');

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_ticket':
                if (!$canManageTickets) {
                    $message = 'You do not have permission to create tickets.';
                    $messageType = 'danger';
                    break;
                }

                $customerName = trim($_POST['customer_name'] ?? '');
                $customerEmail = trim($_POST['customer_email'] ?? '');
                $customerPhone = trim($_POST['customer_phone'] ?? '');
                $subject = trim($_POST['subject'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $category = $_POST['category'] ?? 'general';
                $priority = $_POST['priority'] ?? 'medium';
                $assignedTo = !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : null;

                if (empty($customerName) || empty($customerEmail) || empty($subject) || empty($description)) {
                    $message = 'Please fill in all required fields.';
                    $messageType = 'danger';
                    break;
                }

                // Generate ticket number
                $ticketNumber = 'TICK-' . date('Y') . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO support_tickets
                        (ticket_number, customer_name, customer_email, customer_phone, subject, description, category, priority, assigned_to, created_by)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $ticketNumber, $customerName, $customerEmail, $customerPhone,
                        $subject, $description, $category, $priority, $assignedTo, $_SESSION['admin_user_id']
                    ]);

                    $message = "Ticket {$ticketNumber} created successfully!";
                    $messageType = 'success';

                    // Log activity
                    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'create_ticket', 'support_ticket', 'create', $pdo->lastInsertId(), "Created support ticket {$ticketNumber}");

                } catch (Exception $e) {
                    $message = 'Failed to create ticket: ' . $e->getMessage();
                    $messageType = 'danger';
                }
                break;

            case 'update_ticket':
                if (!$canManageTickets) {
                    $message = 'You do not have permission to update tickets.';
                    $messageType = 'danger';
                    break;
                }

                $ticketId = $_POST['ticket_id'] ?? '';
                $status = $_POST['status'] ?? '';
                $priority = $_POST['priority'] ?? '';
                $assignedTo = !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : null;
                $resolutionNotes = trim($_POST['resolution_notes'] ?? '');

                if (empty($ticketId) || empty($status)) {
                    $message = 'Invalid ticket update request.';
                    $messageType = 'danger';
                    break;
                }

                try {
                    $updateFields = [];
                    $updateValues = [];

                    if (!empty($status)) {
                        $updateFields[] = 'status = ?';
                        $updateValues[] = $status;

                        if ($status === 'resolved' || $status === 'closed') {
                            $updateFields[] = 'resolved_at = CURRENT_TIMESTAMP';
                        }
                    }

                    if (!empty($priority)) {
                        $updateFields[] = 'priority = ?';
                        $updateValues[] = $priority;
                    }

                    $updateFields[] = 'assigned_to = ?';
                    $updateValues[] = $assignedTo;

                    if (!empty($resolutionNotes)) {
                        $updateFields[] = 'resolution_notes = ?';
                        $updateValues[] = $resolutionNotes;
                    }

                    $updateValues[] = $ticketId;

                    $stmt = $pdo->prepare("UPDATE support_tickets SET " . implode(', ', $updateFields) . " WHERE ticket_id = ?");
                    $stmt->execute($updateValues);

                    $message = 'Ticket updated successfully!';
                    $messageType = 'success';

                    // Log activity
                    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'update_ticket', 'support_ticket', 'update', $ticketId, "Updated ticket status to {$status}");

                } catch (Exception $e) {
                    $message = 'Failed to update ticket: ' . $e->getMessage();
                    $messageType = 'danger';
                }
                break;

            case 'add_reply':
                $ticketId = $_POST['ticket_id'] ?? '';
                $replyText = trim($_POST['reply_text'] ?? '');
                $isInternal = isset($_POST['is_internal']) ? 1 : 0;

                if (empty($ticketId) || empty($replyText)) {
                    $message = 'Please provide a reply.';
                    $messageType = 'danger';
                    break;
                }

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO ticket_replies (ticket_id, reply_text, replied_by, replied_by_type, is_internal)
                        VALUES (?, ?, ?, 'admin', ?)
                    ");
                    $stmt->execute([$ticketId, $replyText, $_SESSION['admin_user_id'], $isInternal]);

                    // Update ticket updated_at
                    $pdo->prepare("UPDATE support_tickets SET updated_at = CURRENT_TIMESTAMP WHERE ticket_id = ?")
                        ->execute([$ticketId]);

                    $message = 'Reply added successfully!';
                    $messageType = 'success';

                    // Log activity
                    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'add_reply', 'ticket_reply', 'create', $pdo->lastInsertId(), "Added reply to ticket {$ticketId}");

                } catch (Exception $e) {
                    $message = 'Failed to add reply: ' . $e->getMessage();
                    $messageType = 'danger';
                }
                break;
        }
    }
}

// Get filter parameters
$statusFilter = $_GET['status'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$priorityFilter = $_GET['priority'] ?? '';
$assignedFilter = $_GET['assigned'] ?? '';
$searchTerm = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 20;

// Build query
$query = "
    SELECT t.*, u.username as assigned_username, c.username as creator_username
    FROM support_tickets t
    LEFT JOIN admin_users u ON t.assigned_to = u.id
    LEFT JOIN admin_users c ON t.created_by = c.id
    WHERE 1=1
";

$params = [];

if (!empty($statusFilter)) {
    $query .= " AND t.status = ?";
    $params[] = $statusFilter;
}

if (!empty($categoryFilter)) {
    $query .= " AND t.category = ?";
    $params[] = $categoryFilter;
}

if (!empty($priorityFilter)) {
    $query .= " AND t.priority = ?";
    $params[] = $priorityFilter;
}

if (!empty($assignedFilter)) {
    if ($assignedFilter === 'me') {
        $query .= " AND t.assigned_to = ?";
        $params[] = $_SESSION['admin_user_id'];
    } elseif ($assignedFilter === 'unassigned') {
        $query .= " AND t.assigned_to IS NULL";
    } else {
        $query .= " AND t.assigned_to = ?";
        $params[] = $assignedFilter;
    }
}

if (!empty($searchTerm)) {
    $query .= " AND (t.ticket_number LIKE ? OR t.customer_name LIKE ? OR t.customer_email LIKE ? OR t.subject LIKE ?)";
    $searchParam = "%{$searchTerm}%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

$query .= " ORDER BY
    CASE t.priority
        WHEN 'urgent' THEN 1
        WHEN 'high' THEN 2
        WHEN 'medium' THEN 3
        WHEN 'low' THEN 4
    END,
    t.updated_at DESC
";

// Get total count for pagination
$countQuery = str_replace('SELECT t.*, u.username as assigned_username, c.username as creator_username', 'SELECT COUNT(*)', $query);
$stmt = $pdo->prepare($countQuery);
$stmt->execute($params);
$totalTickets = $stmt->fetchColumn();
$totalPages = ceil($totalTickets / $perPage);

// Add pagination to main query
$query .= " LIMIT " . (($page - 1) * $perPage) . ", {$perPage}";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get admin users for assignment
$adminUsers = $pdo->query("SELECT id, username FROM admin_users ORDER BY username")->fetchAll(PDO::FETCH_ASSOC);

// Get ticket statistics
$stats = $pdo->query("
    SELECT
        COUNT(*) as total,
        SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) as open,
        SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
        SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved,
        SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed,
        SUM(CASE WHEN priority = 'urgent' THEN 1 ELSE 0 END) as urgent,
        SUM(CASE WHEN priority = 'high' THEN 1 ELSE 0 END) as high
    FROM support_tickets
    WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
")->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Tickets - DRF Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .ticket-card { transition: transform 0.2s; }
        .ticket-card:hover { transform: translateY(-2px); }
        .priority-urgent { border-left: 4px solid #dc3545; }
        .priority-high { border-left: 4px solid #fd7e14; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }
        .status-open { background-color: #e3f2fd; }
        .status-in_progress { background-color: #fff3e0; }
        .status-waiting_customer { background-color: #f3e5f5; }
        .status-resolved { background-color: #e8f5e8; }
        .status-closed { background-color: #f5f5f5; }
        .ticket-stats { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="admin-layout">
        <?php include 'includes/sidebar.php'; ?>

        <div class="admin-content">
            <main>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="bi bi-headset me-2"></i>
                        Support Tickets
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTicketModal">
                            <i class="bi bi-plus-circle me-1"></i>New Ticket
                        </button>
                        <button class="btn btn-outline-secondary" onclick="refreshTickets()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                        </button>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card ticket-stats">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo $stats['total'] ?? 0; ?></div>
                                <small>Total Tickets</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card ticket-stats">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo $stats['open'] ?? 0; ?></div>
                                <small>Open</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card ticket-stats">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo $stats['in_progress'] ?? 0; ?></div>
                                <small>In Progress</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card ticket-stats">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo $stats['urgent'] ?? 0; ?></div>
                                <small>Urgent</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card ticket-stats">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo $stats['high'] ?? 0; ?></div>
                                <small>High Priority</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card ticket-stats">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo $stats['resolved'] ?? 0; ?></div>
                                <small>Resolved</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-2">
                                <select class="form-select" name="status">
                                    <option value="">All Status</option>
                                    <option value="open" <?php echo $statusFilter === 'open' ? 'selected' : ''; ?>>Open</option>
                                    <option value="in_progress" <?php echo $statusFilter === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="waiting_customer" <?php echo $statusFilter === 'waiting_customer' ? 'selected' : ''; ?>>Waiting Customer</option>
                                    <option value="resolved" <?php echo $statusFilter === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                    <option value="closed" <?php echo $statusFilter === 'closed' ? 'selected' : ''; ?>>Closed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="category">
                                    <option value="">All Categories</option>
                                    <option value="general" <?php echo $categoryFilter === 'general' ? 'selected' : ''; ?>>General</option>
                                    <option value="order" <?php echo $categoryFilter === 'order' ? 'selected' : ''; ?>>Order</option>
                                    <option value="product" <?php echo $categoryFilter === 'product' ? 'selected' : ''; ?>>Product</option>
                                    <option value="shipping" <?php echo $categoryFilter === 'shipping' ? 'selected' : ''; ?>>Shipping</option>
                                    <option value="payment" <?php echo $categoryFilter === 'payment' ? 'selected' : ''; ?>>Payment</option>
                                    <option value="returns" <?php echo $categoryFilter === 'returns' ? 'selected' : ''; ?>>Returns</option>
                                    <option value="technical" <?php echo $categoryFilter === 'technical' ? 'selected' : ''; ?>>Technical</option>
                                    <option value="other" <?php echo $categoryFilter === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="priority">
                                    <option value="">All Priorities</option>
                                    <option value="urgent" <?php echo $priorityFilter === 'urgent' ? 'selected' : ''; ?>>Urgent</option>
                                    <option value="high" <?php echo $priorityFilter === 'high' ? 'selected' : ''; ?>>High</option>
                                    <option value="medium" <?php echo $priorityFilter === 'medium' ? 'selected' : ''; ?>>Medium</option>
                                    <option value="low" <?php echo $priorityFilter === 'low' ? 'selected' : ''; ?>>Low</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="assigned">
                                    <option value="">All Assigned</option>
                                    <option value="me" <?php echo $assignedFilter === 'me' ? 'selected' : ''; ?>>Assigned to Me</option>
                                    <option value="unassigned" <?php echo $assignedFilter === 'unassigned' ? 'selected' : ''; ?>>Unassigned</option>
                                    <?php foreach ($adminUsers as $user): ?>
                                        <option value="<?php echo $user['id']; ?>" <?php echo $assignedFilter == $user['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($user['username']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="search" placeholder="Search tickets..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-1"></i>Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tickets List -->
                <div class="row">
                    <?php if (empty($tickets)): ?>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                    <h5 class="text-muted">No tickets found</h5>
                                    <p class="text-muted">Try adjusting your filters or create a new ticket.</p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($tickets as $ticket): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card ticket-card priority-<?php echo $ticket['priority']; ?> status-<?php echo $ticket['status']; ?>">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?php echo htmlspecialchars($ticket['ticket_number']); ?></strong>
                                            <span class="badge bg-<?php
                                                echo $ticket['priority'] === 'urgent' ? 'danger' :
                                                     ($ticket['priority'] === 'high' ? 'warning' :
                                                     ($ticket['priority'] === 'medium' ? 'info' : 'success'));
                                            ?> ms-2"><?php echo ucfirst($ticket['priority']); ?></span>
                                        </div>
                                        <div>
                                            <span class="badge bg-<?php
                                                echo $ticket['status'] === 'open' ? 'primary' :
                                                     ($ticket['status'] === 'in_progress' ? 'warning' :
                                                     ($ticket['status'] === 'waiting_customer' ? 'secondary' :
                                                     ($ticket['status'] === 'resolved' ? 'success' : 'light')));
                                            ?>"><?php echo ucfirst(str_replace('_', ' ', $ticket['status'])); ?></span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title"><?php echo htmlspecialchars($ticket['subject']); ?></h6>
                                        <p class="card-text text-muted small">
                                            <strong>Customer:</strong> <?php echo htmlspecialchars($ticket['customer_name']); ?><br>
                                            <strong>Email:</strong> <?php echo htmlspecialchars($ticket['customer_email']); ?><br>
                                            <strong>Category:</strong> <?php echo ucfirst($ticket['category']); ?><br>
                                            <strong>Created:</strong> <?php echo date('M d, Y H:i', strtotime($ticket['created_at'])); ?><br>
                                            <strong>Assigned:</strong> <?php echo $ticket['assigned_username'] ? htmlspecialchars($ticket['assigned_username']) : 'Unassigned'; ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                Updated: <?php echo date('M d, H:i', strtotime($ticket['updated_at'])); ?>
                                            </small>
                                            <div>
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewTicket(<?php echo $ticket['ticket_id']; ?>)">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                                <?php if ($canManageTickets): ?>
                                                    <button class="btn btn-sm btn-outline-secondary" onclick="editTicket(<?php echo $ticket['ticket_id']; ?>)">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Ticket pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&status=<?php echo urlencode($statusFilter); ?>&category=<?php echo urlencode($categoryFilter); ?>&priority=<?php echo urlencode($priorityFilter); ?>&assigned=<?php echo urlencode($assignedFilter); ?>&search=<?php echo urlencode($searchTerm); ?>">
                                    Previous
                                </a>
                            </li>
                            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo urlencode($statusFilter); ?>&category=<?php echo urlencode($categoryFilter); ?>&priority=<?php echo urlencode($priorityFilter); ?>&assigned=<?php echo urlencode($assignedFilter); ?>&search=<?php echo urlencode($searchTerm); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&status=<?php echo urlencode($statusFilter); ?>&category=<?php echo urlencode($categoryFilter); ?>&priority=<?php echo urlencode($priorityFilter); ?>&assigned=<?php echo urlencode($assignedFilter); ?>&search=<?php echo urlencode($searchTerm); ?>">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <!-- Create Ticket Modal -->
    <div class="modal fade" id="createTicketModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Support Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create_ticket">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Customer Name *</label>
                                    <input type="text" class="form-control" name="customer_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Customer Email *</label>
                                    <input type="email" class="form-control" name="customer_email" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Customer Phone</label>
                            <input type="tel" class="form-control" name="customer_phone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject *</label>
                            <input type="text" class="form-control" name="subject" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category">
                                        <option value="general">General</option>
                                        <option value="order">Order</option>
                                        <option value="product">Product</option>
                                        <option value="shipping">Shipping</option>
                                        <option value="payment">Payment</option>
                                        <option value="returns">Returns</option>
                                        <option value="technical">Technical</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Priority</label>
                                    <select class="form-select" name="priority">
                                        <option value="low">Low</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="high">High</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="4" required></textarea>
                        </div>
                        <?php if ($canManageTickets): ?>
                            <div class="mb-3">
                                <label class="form-label">Assign To</label>
                                <select class="form-select" name="assigned_to">
                                    <option value="">Unassigned</option>
                                    <?php foreach ($adminUsers as $user): ?>
                                        <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Ticket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Ticket Detail Modal -->
    <div class="modal fade" id="ticketDetailModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ticket Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="ticketDetailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
    <script>
        function viewTicket(ticketId) {
            // Load ticket details via AJAX
            fetch(`api/ticket-details.php?ticket_id=${ticketId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('ticketDetailContent').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('ticketDetailModal')).show();
                })
                .catch(error => {
                    alert('Failed to load ticket details: ' + error.message);
                });
        }

        function editTicket(ticketId) {
            // Similar to view but with edit capabilities
            viewTicket(ticketId);
        }

        function refreshTickets() {
            window.location.reload();
        }

        // Auto-refresh every 5 minutes
        setInterval(refreshTickets, 300000);
    </script>
</body>
</html>
