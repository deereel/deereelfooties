<?php
session_start();
require_once '../config/database.php';
require_once '../classes/SocialMediaGenerator.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$generator = new SocialMediaGenerator($pdo);

if ($_POST['action'] ?? '' === 'generate') {
    // Fallback to PHP generation if Python fails
    try {
        $result = $generator->generateWithPython();
        if ($result && isset($result['slides'])) {
            $success = "Post generated successfully with " . count($result['slides']) . " slides!";
        } else {
            throw new Exception("Python generation failed");
        }
    } catch (Exception $e) {
        // Use original PHP method as fallback
        $slides = rand(3, 5);
        $post = $generator->createPost($slides);
        $success = "Post generated successfully (PHP fallback)!";
    }
}

if ($_POST['action'] ?? '' === 'schedule') {
    $postId = $_POST['post_id'] ?? '';
    $platform = $_POST['platform'] ?? '';
    $time = $_POST['schedule_time'] ?? '';
    if ($postId && $platform && $time) {
        $generator->schedulePost($postId, $platform, $time);
        $success = "Post scheduled successfully!";
    }
}

$posts = $generator->getPosts(20);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Manager - DeeReel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h2>Social Media Manager</h2>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Generate New Post</div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="action" value="generate">
                                    <button type="submit" class="btn btn-primary">Generate Random Post</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">Generated Posts</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Topic</th>
                                        <th>Content Preview</th>
                                        <th>Slides</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($posts as $post): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($post['topic']) ?></td>
                                        <td><?= substr(htmlspecialchars($post['content']), 0, 100) ?>...</td>
                                        <td><?= $post['slides'] ?></td>
                                        <td><span class="badge bg-<?= $post['status'] === 'scheduled' ? 'success' : 'secondary' ?>"><?= $post['status'] ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick="viewPost('<?= $post['post_id'] ?>')">View</button>
                                            <button class="btn btn-sm btn-success" onclick="schedulePost('<?= $post['post_id'] ?>')">Schedule</button>
                                            <button class="btn btn-sm btn-warning" onclick="downloadPost('<?= $post['post_id'] ?>')">Download</button>
                                            <button class="btn btn-sm btn-secondary" onclick="downloadZip('<?= $post['post_id'] ?>')">Download ZIP</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Schedule Modal -->
    <div class="modal fade" id="scheduleModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Schedule Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="action" value="schedule">
                        <input type="hidden" name="post_id" id="schedulePostId">
                        
                        <div class="mb-3">
                            <label class="form-label">Platform</label>
                            <select name="platform" class="form-select" required>
                                <option value="instagram">Instagram</option>
                                <option value="tiktok">TikTok</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Schedule Time</label>
                            <select name="schedule_time" class="form-select" required>
                                <option value="06:00">6:00 AM</option>
                                <option value="18:00">6:00 PM</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Schedule Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/social-media.js"></script>
</body>
</html>