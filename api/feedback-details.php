<?php
session_start();
require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

// Check permissions
try {
    $permissionMiddleware = new PermissionMiddleware('view_feedback');
    $permissionMiddleware->handle();
} catch (Exception $e) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$feedbackId = $_GET['feedback_id'] ?? '';

if (empty($feedbackId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Feedback ID is required']);
    exit;
}

// Get feedback details
$stmt = $pdo->prepare("
    SELECT f.*, u.username as assigned_username, c.username as creator_username
    FROM feedback f
    LEFT JOIN admin_users u ON f.assigned_to = u.id
    LEFT JOIN admin_users c ON f.created_by = c.id
    WHERE f.id = ?
");
$stmt->execute([$feedbackId]);
$feedback = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$feedback) {
    http_response_code(404);
    echo json_encode(['error' => 'Feedback not found']);
    exit;
}

// Get feedback responses
$stmt = $pdo->prepare("
    SELECT r.*, u.username as responder_name
    FROM feedback_responses r
    LEFT JOIN admin_users u ON r.responded_by = u.id
    WHERE r.feedback_id = ?
    ORDER BY r.created_at ASC
");
$stmt->execute([$feedbackId]);
$responses = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

// Get admin users for assignment
$adminUsers = $pdo->query("SELECT id, username FROM admin_users ORDER BY username")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="feedback-detail">
    <!-- Feedback Header -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1"><?php echo htmlspecialchars($feedback['subject']); ?></h5>
                <small class="text-muted">
                    Submitted by <?php echo htmlspecialchars($feedback['customer_name']); ?> on <?php echo date('M d, Y H:i', strtotime($feedback['created_at'])); ?>
                </small>
            </div>
            <div>
                <span class="badge bg-<?php
                    echo $feedback['priority'] === 'urgent' ? 'danger' :
                         ($feedback['priority'] === 'high' ? 'warning' :
                         ($feedback['priority'] === 'medium' ? 'info' : 'success'));
                ?>"><?php echo ucfirst($feedback['priority']); ?> Priority</span>
                <span class="badge bg-<?php
                    echo $feedback['status'] === 'new' ? 'primary' :
                         ($feedback['status'] === 'read' ? 'secondary' :
                         ($feedback['status'] === 'responded' ? 'success' : 'light'));
                ?> ms-2"><?php echo ucfirst($feedback['status']); ?></span>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Customer Information</h6>
                    <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($feedback['customer_name']); ?></p>
                    <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($feedback['customer_email']); ?></p>
                    <?php if ($feedback['customer_phone']): ?>
                        <p class="mb-1"><strong>Phone:</strong> <?php echo htmlspecialchars($feedback['customer_phone']); ?></p>
                    <?php endif; ?>
                    <p class="mb-1"><strong>Category:</strong> <?php echo ucfirst($feedback['category']); ?></p>
                    <?php if ($feedback['rating']): ?>
                        <p class="mb-1"><strong>Rating:</strong>
                            <span class="rating-stars ms-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi <?php echo $i <= $feedback['rating'] ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                                <?php endfor; ?>
                                (<?php echo $feedback['rating']; ?>/5)
                            </span>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <h6>Feedback Information</h6>
                    <p class="mb-1"><strong>Assigned To:</strong> <?php echo $feedback['assigned_username'] ? htmlspecialchars($feedback['assigned_username']) : 'Unassigned'; ?></p>
                    <p class="mb-1"><strong>Source:</strong> <?php echo ucfirst($feedback['source']); ?></p>
                    <p class="mb-1"><strong>Public:</strong> <?php echo $feedback['is_public'] ? '<span class="text-success">Yes</span>' : '<span class="text-muted">No</span>'; ?></p>
                    <p class="mb-1"><strong>Last Updated:</strong> <?php echo date('M d, Y H:i', strtotime($feedback['updated_at'])); ?></p>
                    <?php if ($feedback['responded_at']): ?>
                        <p class="mb-1"><strong>Responded At:</strong> <?php echo date('M d, Y H:i', strtotime($feedback['responded_at'])); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mt-3">
                <h6>Message</h6>
                <div class="border p-3 rounded">
                    <?php echo nl2br(htmlspecialchars($feedback['message'])); ?>
                </div>
            </div>
            <?php if ($feedback['response_notes']): ?>
                <div class="mt-3">
                    <h6>Response Notes</h6>
                    <div class="border p-3 rounded bg-light">
                        <?php echo nl2br(htmlspecialchars($feedback['response_notes'])); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Update Feedback Form (for managers) -->
    <?php if ($canManageFeedback): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Update Feedback</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="admin/feedback.php">
                    <input type="hidden" name="action" value="update_feedback">
                    <input type="hidden" name="feedback_id" value="<?php echo $feedback['id']; ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="new" <?php echo $feedback['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                                    <option value="read" <?php echo $feedback['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                                    <option value="responded" <?php echo $feedback['status'] === 'responded' ? 'selected' : ''; ?>>Responded</option>
                                    <option value="closed" <?php echo $feedback['status'] === 'closed' ? 'selected' : ''; ?>>Closed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Priority</label>
                                <select class="form-select" name="priority">
                                    <option value="low" <?php echo $feedback['priority'] === 'low' ? 'selected' : ''; ?>>Low</option>
                                    <option value="medium" <?php echo $feedback['priority'] === 'medium' ? 'selected' : ''; ?>>Medium</option>
                                    <option value="high" <?php echo $feedback['priority'] === 'high' ? 'selected' : ''; ?>>High</option>
                                    <option value="urgent" <?php echo $feedback['priority'] === 'urgent' ? 'selected' : ''; ?>>Urgent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Assign To</label>
                                <select class="form-select" name="assigned_to">
                                    <option value="">Unassigned</option>
                                    <?php foreach ($adminUsers as $user): ?>
                                        <option value="<?php echo $user['id']; ?>" <?php echo $feedback['assigned_to'] == $user['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($user['username']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">Update Feedback</button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Response Notes</label>
                        <textarea class="form-control" name="response_notes" rows="3" placeholder="Add response notes..."><?php echo htmlspecialchars($feedback['response_notes'] ?? ''); ?></textarea>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Responses Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">Responses & Updates</h6>
        </div>
        <div class="card-body">
            <?php if (empty($responses)): ?>
                <p class="text-muted text-center">No responses yet.</p>
            <?php else: ?>
                <div class="responses-container" style="max-height: 400px; overflow-y: auto;">
                    <?php foreach ($responses as $response): ?>
                        <div class="response-item mb-3 p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong><?php echo htmlspecialchars($response['responder_name'] ?? 'System'); ?></strong>
                                    <?php if ($response['is_internal']): ?>
                                        <span class="badge bg-warning ms-2">Internal</span>
                                    <?php endif; ?>
                                </div>
                                <small class="text-muted"><?php echo date('M d, Y H:i', strtotime($response['created_at'])); ?></small>
                            </div>
                            <div class="response-content">
                                <?php echo nl2br(htmlspecialchars($response['response_text'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Add Response Form -->
            <?php if ($canRespondFeedback): ?>
                <div class="mt-4">
                    <h6>Add Response</h6>
                    <form method="POST" action="admin/feedback.php">
                        <input type="hidden" name="action" value="add_response">
                        <input type="hidden" name="feedback_id" value="<?php echo $feedback['id']; ?>">
                        <div class="mb-3">
                            <textarea class="form-control" name="response_text" rows="4" placeholder="Type your response here..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_internal" id="is_internal_<?php echo $feedback['id']; ?>">
                                <label class="form-check-label" for="is_internal_<?php echo $feedback['id']; ?>">
                                    Internal note (not visible to customer)
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Response</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.rating-stars {
    color: #ffc107;
    font-size: 0.875rem;
}
.rating-stars .bi-star-fill { color: #ffc107; }
.rating-stars .bi-star { color: #dee2e6; }
</style>

<script>
// Auto-scroll to bottom of responses
document.addEventListener('DOMContentLoaded', function() {
    const responsesContainer = document.querySelector('.responses-container');
    if (responsesContainer) {
        responsesContainer.scrollTop = responsesContainer.scrollHeight;
    }
});
</script>
