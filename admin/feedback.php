<?php
session_start();
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check permissions
try {
    $permissionMiddleware = new PermissionMiddleware('view_feedback');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    header('Location: login.php');
    exit;
}

// Check if user can manage feedback
$canManageFeedback = false;
try {
    $managePermission = new PermissionMiddleware('manage_feedback');
    $managePermission->handle();
    $canManageFeedback = true;
} catch (Exception $e) {
    $canManageFeedback = false;
}

// Check if user can respond to feedback
$canRespondFeedback = false;
try {
    $respondPermission = new PermissionMiddleware('respond_feedback');
    $respondPermission->handle();
    $canRespondFeedback = true;
} catch (Exception $e) {
    $canRespondFeedback = false;
}

// Log activity
logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'view_feedback', 'feedback', 'read', null, 'Viewed feedback management page');

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create_feedback':
                if (!$canManageFeedback) {
                    $message = 'You do not have permission to create feedback.';
                    $messageType = 'danger';
                    break;
                }

                $customerName = trim($_POST['customer_name'] ?? '');
                $customerEmail = trim($_POST['customer_email'] ?? '');
                $customerPhone = trim($_POST['customer_phone'] ?? '');
                $subject = trim($_POST['subject'] ?? '');
                $message_text = trim($_POST['message'] ?? '');
                $category = $_POST['category'] ?? 'general';
                $rating = !empty($_POST['rating']) ? (int)$_POST['rating'] : null;
                $priority = $_POST['priority'] ?? 'medium';
                $assignedTo = !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : null;
                $isPublic = isset($_POST['is_public']) ? 1 : 0;

                if (empty($customerName) || empty($customerEmail) || empty($subject) || empty($message_text)) {
                    $message = 'Please fill in all required fields.';
                    $messageType = 'danger';
                    break;
                }

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO feedback
                        (customer_name, customer_email, customer_phone, subject, message, category, rating, priority, assigned_to, created_by, is_public)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $customerName, $customerEmail, $customerPhone, $subject, $message_text,
                        $category, $rating, $priority, $assignedTo, $_SESSION['admin_user_id'], $isPublic
                    ]);

                    $message = "Feedback created successfully!";
                    $messageType = 'success';

                    // Log activity
                    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'create_feedback', 'feedback', 'create', $pdo->lastInsertId(), "Created feedback: {$subject}");

                } catch (Exception $e) {
                    $message = 'Failed to create feedback: ' . $e->getMessage();
                    $messageType = 'danger';
                }
                break;

            case 'update_feedback':
                if (!$canManageFeedback) {
                    $message = 'You do not have permission to update feedback.';
                    $messageType = 'danger';
                    break;
                }

                $feedbackId = $_POST['feedback_id'] ?? '';
                $status = $_POST['status'] ?? '';
                $priority = $_POST['priority'] ?? '';
                $assignedTo = !empty($_POST['assigned_to']) ? $_POST['assigned_to'] : null;
                $responseNotes = trim($_POST['response_notes'] ?? '');

                if (empty($feedbackId) || empty($status)) {
                    $message = 'Invalid feedback update request.';
                    $messageType = 'danger';
                    break;
                }

                try {
                    $updateFields = [];
                    $updateValues = [];

                    if (!empty($status)) {
                        $updateFields[] = 'status = ?';
                        $updateValues[] = $status;

                        if ($status === 'responded') {
                            $updateFields[] = 'responded_at = CURRENT_TIMESTAMP';
                        }
                    }

                    if (!empty($priority)) {
                        $updateFields[] = 'priority = ?';
                        $updateValues[] = $priority;
                    }

                    $updateFields[] = 'assigned_to = ?';
                    $updateValues[] = $assignedTo;

                    if (!empty($responseNotes)) {
                        $updateFields[] = 'response_notes = ?';
                        $updateValues[] = $responseNotes;
                    }

                    $updateValues[] = $feedbackId;

                    $stmt = $pdo->prepare("UPDATE feedback SET " . implode(', ', $updateFields) . " WHERE id = ?");
                    $stmt->execute($updateValues);

                    $message = 'Feedback updated successfully!';
                    $messageType = 'success';

                    // Log activity
                    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'update_feedback', 'feedback', 'update', $feedbackId, "Updated feedback status to {$status}");

                } catch (Exception $e) {
                    $message = 'Failed to update feedback: ' . $e->getMessage();
                    $messageType = 'danger';
                }
                break;

            case 'add_response':
                if (!$canRespondFeedback) {
                    $message = 'You do not have permission to respond to feedback.';
                    $messageType = 'danger';
                    break;
                }

                $feedbackId = $_POST['feedback_id'] ?? '';
                $responseText = trim($_POST['response_text'] ?? '');
                $isInternal = isset($_POST['is_internal']) ? 1 : 0;

                if (empty($feedbackId) || empty($responseText)) {
                    $message = 'Please provide a response.';
                    $messageType = 'danger';
                    break;
                }

                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO feedback_responses (feedback_id, response_text, responded_by, responded_by_type, is_internal)
                        VALUES (?, ?, ?, 'admin', ?)
                    ");
                    $stmt->execute([$feedbackId, $responseText, $_SESSION['admin_user_id'], $isInternal]);

                    // Update feedback status to responded if not already
                    $pdo->prepare("UPDATE feedback SET status = 'responded', responded_at = CURRENT_TIMESTAMP WHERE id = ? AND status != 'closed'")
                        ->execute([$feedbackId]);

                    $message = 'Response added successfully!';
                    $messageType = 'success';

                    // Log activity
                    logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'respond_feedback', 'feedback_response', 'create', $pdo->lastInsertId(), "Added response to feedback {$feedbackId}");

                } catch (Exception $e) {
                    $message = 'Failed to add response: ' . $e->getMessage();
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
    SELECT f.*, u.username as assigned_username, c.username as creator_username
    FROM feedback f
    LEFT JOIN admin_users u ON f.assigned_to = u.id
    LEFT JOIN admin_users c ON f.created_by = c.id
    WHERE 1=1
";

$params = [];

if (!empty($statusFilter)) {
    $query .= " AND f.status = ?";
    $params[] = $statusFilter;
}

if (!empty($categoryFilter)) {
    $query .= " AND f.category = ?";
    $params[] = $categoryFilter;
}

if (!empty($priorityFilter)) {
    $query .= " AND f.priority = ?";
    $params[] = $priorityFilter;
}

if (!empty($assignedFilter)) {
    if ($assignedFilter === 'me') {
        $query .= " AND f.assigned_to = ?";
        $params[] = $_SESSION['admin_user_id'];
    } elseif ($assignedFilter === 'unassigned') {
        $query .= " AND f.assigned_to IS NULL";
    } else {
        $query .= " AND f.assigned_to = ?";
        $params[] = $assignedFilter;
    }
}

if (!empty($searchTerm)) {
    $query .= " AND (f.customer_name LIKE ? OR f.customer_email LIKE ? OR f.subject LIKE ? OR f.message LIKE ?)";
    $searchParam = "%{$searchTerm}%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

$query .= " ORDER BY
    CASE f.priority
        WHEN 'urgent' THEN 1
        WHEN 'high' THEN 2
        WHEN 'medium' THEN 3
        WHEN 'low' THEN 4
    END,
    CASE f.status
        WHEN 'new' THEN 1
        WHEN 'read' THEN 2
        WHEN 'responded' THEN 3
        WHEN 'closed' THEN 4
    END,
    f.created_at DESC
";

// Get total count for pagination
$countQuery = str_replace('SELECT f.*, u.username as assigned_username, c.username as creator_username', 'SELECT COUNT(*)', $query);
$stmt = $pdo->prepare($countQuery);
$stmt->execute($params);
$totalFeedback = $stmt->fetchColumn();
$totalPages = ceil($totalFeedback / $perPage);

// Add pagination to main query
$query .= " LIMIT " . (($page - 1) * $perPage) . ", {$perPage}";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get admin users for assignment
$adminUsers = $pdo->query("SELECT id, username FROM admin_users ORDER BY username")->fetchAll(PDO::FETCH_ASSOC);

// Get feedback statistics
$stats = $pdo->query("
    SELECT
        COUNT(*) as total,
        SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_count,
        SUM(CASE WHEN status = 'read' THEN 1 ELSE 0 END) as read_count,
        SUM(CASE WHEN status = 'responded' THEN 1 ELSE 0 END) as responded_count,
        SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed_count,
        AVG(CASE WHEN rating IS NOT NULL THEN rating ELSE NULL END) as avg_rating,
        SUM(CASE WHEN priority = 'urgent' THEN 1 ELSE 0 END) as urgent_count,
        SUM(CASE WHEN priority = 'high' THEN 1 ELSE 0 END) as high_count
    FROM feedback
    WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
")->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback - DRF Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .feedback-card { transition: transform 0.2s; }
        .feedback-card:hover { transform: translateY(-2px); }
        .priority-urgent { border-left: 4px solid #dc3545; }
        .priority-high { border-left: 4px solid #fd7e14; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }
        .status-new { background-color: #e3f2fd; }
        .status-read { background-color: #f3e5f5; }
        .status-responded { background-color: #e8f5e8; }
        .status-closed { background-color: #f5f5f5; }
        .feedback-stats { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .rating-stars {
            color: #ffc107;
            font-size: 0.875rem;
        }
        .rating-stars .bi-star-fill { color: #ffc107; }
        .rating-stars .bi-star { color: #dee2e6; }
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
                        <i class="bi bi-chat-quote me-2"></i>
                        Customer Feedback
                    </h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createFeedbackModal">
                            <i class="bi bi-plus-circle me-1"></i>New Feedback
                        </button>
                        <button class="btn btn-outline-secondary" onclick="refreshFeedback()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                        </button>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card feedback-stats">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo $stats['total'] ?? 0; ?></div>
                                <small>Total Feedback</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card feedback-stats">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo $stats['new_count'] ?? 0; ?></div>
                                <small>New</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card feedback-stats">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo $stats['read_count'] ?? 0; ?></div>
                                <small>Read</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card feedback-stats">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo $stats['responded_count'] ?? 0; ?></div>
                                <small>Responded</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card feedback-stats">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1"><?php echo $stats['urgent_count'] ?? 0; ?></div>
                                <small>Urgent</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card feedback-stats">
                            <div class="card-body p-3 text-center">
                                <div class="h4 mb-1">
                                    <?php
                                    $avgRating = $stats['avg_rating'] ?? 0;
                                    echo $avgRating > 0 ? number_format($avgRating, 1) : 'N/A';
                                    ?>
                                </div>
                                <small>Avg Rating</small>
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
                                    <option value="new" <?php echo $statusFilter === 'new' ? 'selected' : ''; ?>>New</option>
                                    <option value="read" <?php echo $statusFilter === 'read' ? 'selected' : ''; ?>>Read</option>
                                    <option value="responded" <?php echo $statusFilter === 'responded' ? 'selected' : ''; ?>>Responded</option>
                                    <option value="closed" <?php echo $statusFilter === 'closed' ? 'selected' : ''; ?>>Closed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="category">
                                    <option value="">All Categories</option>
                                    <option value="general" <?php echo $categoryFilter === 'general' ? 'selected' : ''; ?>>General</option>
                                    <option value="product" <?php echo $categoryFilter === 'product' ? 'selected' : ''; ?>>Product</option>
                                    <option value="service" <?php echo $categoryFilter === 'service' ? 'selected' : ''; ?>>Service</option>
                                    <option value="website" <?php echo $categoryFilter === 'website' ? 'selected' : ''; ?>>Website</option>
                                    <option value="suggestion" <?php echo $categoryFilter === 'suggestion' ? 'selected' : ''; ?>>Suggestion</option>
                                    <option value="complaint" <?php echo $categoryFilter === 'complaint' ? 'selected' : ''; ?>>Complaint</option>
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
                                <input type="text" class="form-control" name="search" placeholder="Search feedback..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-1"></i>Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Feedback List -->
                <div class="row">
                    <?php if (empty($feedbacks)): ?>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <i class="bi bi-chat-quote display-4 text-muted mb-3"></i>
                                    <h5 class="text-muted">No feedback found</h5>
                                    <p class="text-muted">Try adjusting your filters or create new feedback.</p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($feedbacks as $feedback): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card feedback-card priority-<?php echo $feedback['priority']; ?> status-<?php echo $feedback['status']; ?>">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?php echo htmlspecialchars($feedback['subject']); ?></strong>
                                            <span class="badge bg-<?php
                                                echo $feedback['priority'] === 'urgent' ? 'danger' :
                                                     ($feedback['priority'] === 'high' ? 'warning' :
                                                     ($feedback['priority'] === 'medium' ? 'info' : 'success'));
                                            ?> ms-2"><?php echo ucfirst($feedback['priority']); ?></span>
                                        </div>
                                        <div>
                                            <span class="badge bg-<?php
                                                echo $feedback['status'] === 'new' ? 'primary' :
                                                     ($feedback['status'] === 'read' ? 'secondary' :
                                                     ($feedback['status'] === 'responded' ? 'success' : 'light'));
                                            ?>"><?php echo ucfirst($feedback['status']); ?></span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text text-muted small">
                                            <strong>From:</strong> <?php echo htmlspecialchars($feedback['customer_name']); ?><br>
                                            <strong>Email:</strong> <?php echo htmlspecialchars($feedback['customer_email']); ?><br>
                                            <strong>Category:</strong> <?php echo ucfirst($feedback['category']); ?><br>
                                            <?php if ($feedback['rating']): ?>
                                                <strong>Rating:</strong>
                                                <span class="rating-stars ms-2">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="bi <?php echo $i <= $feedback['rating'] ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                                                    <?php endfor; ?>
                                                </span><br>
                                            <?php endif; ?>
                                            <strong>Created:</strong> <?php echo date('M d, Y H:i', strtotime($feedback['created_at'])); ?><br>
                                            <strong>Assigned:</strong> <?php echo $feedback['assigned_username'] ? htmlspecialchars($feedback['assigned_username']) : 'Unassigned'; ?>
                                        </p>
                                        <p class="card-text">
                                            <?php echo htmlspecialchars(substr($feedback['message'], 0, 150)); ?>
                                            <?php if (strlen($feedback['message']) > 150): ?>...<?php endif; ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                Updated: <?php echo date('M d, H:i', strtotime($feedback['updated_at'])); ?>
                                            </small>
                                            <div>
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewFeedback(<?php echo $feedback['id']; ?>)">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                                <?php if ($canManageFeedback): ?>
                                                    <button class="btn btn-sm btn-outline-secondary" onclick="editFeedback(<?php echo $feedback['id']; ?>)">
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
                    <nav aria-label="Feedback pagination" class="mt-4">
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

    <!-- Create Feedback Modal -->
    <div class="modal fade" id="createFeedbackModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create_feedback">
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
                                        <option value="product">Product</option>
                                        <option value="service">Service</option>
                                        <option value="website">Website</option>
                                        <option value="suggestion">Suggestion</option>
                                        <option value="complaint">Complaint</option>
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
                            <label class="form-label">Rating (Optional)</label>
                            <select class="form-select" name="rating">
                                <option value="">No Rating</option>
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message *</label>
                            <textarea class="form-control" name="message" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_public" id="is_public">
                                <label class="form-check-label" for="is_public">
                                    Make this feedback public (visible to other customers)
                                </label>
                            </div>
                        </div>
                        <?php if ($canManageFeedback): ?>
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
                        <button type="submit" class="btn btn-primary">Create Feedback</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Feedback Detail Modal -->
    <div class="modal fade" id="feedbackDetailModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Feedback Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="feedbackDetailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/admin.js"></script>
    <script>
        function viewFeedback(feedbackId) {
            // Load feedback details via AJAX
            fetch(`api/feedback-details.php?feedback_id=${feedbackId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('feedbackDetailContent').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('feedbackDetailModal')).show();
                })
                .catch(error => {
                    alert('Failed to load feedback details: ' + error.message);
                });
        }

        function editFeedback(feedbackId) {
            // Similar to view but with edit capabilities
            viewFeedback(feedbackId);
        }

        function refreshFeedback() {
            window.location.reload();
        }

        // Auto-refresh every 5 minutes
        setInterval(refreshFeedback, 300000);
    </script>
</body>
</html>
