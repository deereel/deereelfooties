<?php
session_start();
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check permissions
try {
    $permissionMiddleware = new PermissionMiddleware('view_support_tickets');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$ticketId = $_GET['ticket_id'] ?? '';

if (empty($ticketId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Ticket ID is required']);
    exit;
}

// Get ticket details
$stmt = $pdo->prepare("
    SELECT t.*, u.username as assigned_username, c.username as creator_username
    FROM support_tickets t
    LEFT JOIN admin_users u ON t.assigned_to = u.id
    LEFT JOIN admin_users c ON t.created_by = c.id
    WHERE t.ticket_id = ?
");
$stmt->execute([$ticketId]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    http_response_code(404);
    echo json_encode(['error' => 'Ticket not found']);
    exit;
}

// Get ticket replies
$stmt = $pdo->prepare("
    SELECT r.*, u.username as replier_name
    FROM ticket_replies r
    LEFT JOIN admin_users u ON r.replied_by = u.id
    WHERE r.ticket_id = ?
    ORDER BY r.created_at ASC
");
$stmt->execute([$ticketId]);
$replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get admin users for assignment
$adminUsers = $pdo->query("SELECT id, username FROM admin_users ORDER BY username")->fetchAll(PDO::FETCH_ASSOC);

// Check if user can manage tickets
$canManageTickets = false;
try {
    $managePermission = new PermissionMiddleware('manage_support_tickets');
    $managePermission->handle();
    $canManageTickets = true;
} catch (Exception $e) {
    $canManageTickets = false;
}
?>

<div class="ticket-detail">
    <!-- Ticket Header -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1"><?php echo htmlspecialchars($ticket['ticket_number']); ?> - <?php echo htmlspecialchars($ticket['subject']); ?></h5>
                <small class="text-muted">
                    Created by <?php echo htmlspecialchars($ticket['creator_username'] ?? 'System'); ?> on <?php echo date('M d, Y H:i', strtotime($ticket['created_at'])); ?>
                </small>
            </div>
            <div>
                <span class="badge bg-<?php
                    echo $ticket['priority'] === 'urgent' ? 'danger' :
                         ($ticket['priority'] === 'high' ? 'warning' :
                         ($ticket['priority'] === 'medium' ? 'info' : 'success'));
                ?>"><?php echo ucfirst($ticket['priority']); ?> Priority</span>
                <span class="badge bg-<?php
                    echo $ticket['status'] === 'open' ? 'primary' :
                         ($ticket['status'] === 'in_progress' ? 'warning' :
                         ($ticket['status'] === 'waiting_customer' ? 'secondary' :
                         ($ticket['status'] === 'resolved' ? 'success' : 'light')));
                ?> ms-2"><?php echo ucfirst(str_replace('_', ' ', $ticket['status'])); ?></span>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Customer Information</h6>
                    <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($ticket['customer_name']); ?></p>
                    <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($ticket['customer_email']); ?></p>
                    <?php if ($ticket['customer_phone']): ?>
                        <p class="mb-1"><strong>Phone:</strong> <?php echo htmlspecialchars($ticket['customer_phone']); ?></p>
                    <?php endif; ?>
                    <p class="mb-1"><strong>Category:</strong> <?php echo ucfirst($ticket['category']); ?></p>
                </div>
                <div class="col-md-6">
                    <h6>Ticket Information</h6>
                    <p class="mb-1"><strong>Assigned To:</strong> <?php echo $ticket['assigned_username'] ? htmlspecialchars($ticket['assigned_username']) : 'Unassigned'; ?></p>
                    <p class="mb-1"><strong>Last Updated:</strong> <?php echo date('M d, Y H:i', strtotime($ticket['updated_at'])); ?></p>
                    <?php if ($ticket['resolved_at']): ?>
                        <p class="mb-1"><strong>Resolved At:</strong> <?php echo date('M d, Y H:i', strtotime($ticket['resolved_at'])); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mt-3">
                <h6>Description</h6>
                <div class="border p-3 rounded">
                    <?php echo nl2br(htmlspecialchars($ticket['description'])); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Ticket Form (for managers) -->
    <?php if ($canManageTickets): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Update Ticket</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="support-tickets.php">
                    <input type="hidden" name="action" value="update_ticket">
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket['ticket_id']; ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="open" <?php echo $ticket['status'] === 'open' ? 'selected' : ''; ?>>Open</option>
                                    <option value="in_progress" <?php echo $ticket['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                    <option value="waiting_customer" <?php echo $ticket['status'] === 'waiting_customer' ? 'selected' : ''; ?>>Waiting Customer</option>
                                    <option value="resolved" <?php echo $ticket['status'] === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                    <option value="closed" <?php echo $ticket['status'] === 'closed' ? 'selected' : ''; ?>>Closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Priority</label>
                                <select class="form-select" name="priority">
                                    <option value="low" <?php echo $ticket['priority'] === 'low' ? 'selected' : ''; ?>>Low</option>
                                    <option value="medium" <?php echo $ticket['priority'] === 'medium' ? 'selected' : ''; ?>>Medium</option>
                                    <option value="high" <?php echo $ticket['priority'] === 'high' ? 'selected' : ''; ?>>High</option>
                                    <option value="urgent" <?php echo $ticket['priority'] === 'urgent' ? 'selected' : ''; ?>>Urgent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Assign To</label>
                                <select class="form-select" name="assigned_to">
                                    <option value="">Unassigned</option>
                                    <?php foreach ($adminUsers as $user): ?>
                                        <option value="<?php echo $user['id']; ?>" <?php echo $ticket['assigned_to'] == $user['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($user['username']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">Update Ticket</button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Resolution Notes</label>
                        <textarea class="form-control" name="resolution_notes" rows="3" placeholder="Add resolution notes..."><?php echo htmlspecialchars($ticket['resolution_notes'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Replies Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">Replies & Updates</h6>
        </div>
        <div class="card-body">
            <?php if (empty($replies)): ?>
                <p class="text-muted text-center">No replies yet.</p>
            <?php else: ?>
                <div class="replies-container" style="max-height: 400px; overflow-y: auto;">
                    <?php foreach ($replies as $reply): ?>
                        <div class="reply-item mb-3 p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong><?php echo htmlspecialchars($reply['replier_name'] ?? 'System'); ?></strong>
                                    <?php if ($reply['is_internal']): ?>
                                        <span class="badge bg-warning ms-2">Internal</span>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted"><?php echo date('M d, Y H:i', strtotime($reply['created_at'])); ?></small>
                            </div>
                            <div class="reply-content">
                                <?php echo nl2br(htmlspecialchars($reply['reply_text'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Add Reply Form -->
            <div class="mt-4">
                <h6>Add Reply</h6>
                <form method="POST" action="support-tickets.php">
                    <input type="hidden" name="action" value="add_reply">
                    <input type="hidden" name="ticket_id" value="<?php echo $ticket['ticket_id']; ?>">
                    <div class="mb-3">
                        <textarea class="form-control" name="reply_text" rows="4" placeholder="Type your reply here..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_internal" id="is_internal_<?php echo $ticket['ticket_id']; ?>">
                            <label class="form-check-label" for="is_internal_<?php echo $ticket['ticket_id']; ?>">
                                Internal note (not visible to customer)
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-scroll to bottom of replies
document.addEventListener('DOMContentLoaded', function() {
    const repliesContainer = document.querySelector('.replies-container');
    if (repliesContainer) {
        repliesContainer.scrollTop = repliesContainer.scrollHeight;
    }
});
</script>
