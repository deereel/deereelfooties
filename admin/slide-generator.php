<?php
session_start();
require_once '../config/database.php';
require_once 'includes/admin-activity-logger.php';


if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit();
}

// Log slide generator viewing activity
logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'view_slide_generator', 'slide_generator', 'read', null, 'Viewed slide generator page');

if ($_POST['action'] ?? '' === 'generate_slides') {
    $topic = $_POST['topic'] ?? 'Shoe Care Tips';
    $slides = $_POST['slides'] ?? 5;
    
    $postId = uniqid('post_');
    $slideData = generateSlideContent($topic, $slides);
    
    // Save to database
    $stmt = $pdo->prepare("INSERT INTO social_posts (post_id, topic, content, slides, created_at, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$postId, $topic, json_encode($slideData), $slides, date('Y-m-d H:i:s'), 'draft']);
    
    $success = "Slides generated successfully!";
}

function generateSlideContent($topic, $numSlides) {
    $templates = [
        ['type' => 'hook', 'title' => '👟 Your Shoes Deserve Better!', 'subtitle' => 'Pro Tips Inside'],
        ['type' => 'tip', 'title' => 'Clean with soft brush', 'subtitle' => 'Use mild soap and gentle circular motions', 'number' => '01'],
        ['type' => 'tip', 'title' => 'Air dry only', 'subtitle' => 'Never use direct heat or sunlight', 'number' => '02'],
        ['type' => 'tip', 'title' => 'Use shoe trees', 'subtitle' => 'Maintain shape and absorb moisture', 'number' => '03'],
        ['type' => 'cta', 'title' => 'Shop Premium Footwear', 'subtitle' => 'deereelfooties.com']
    ];
    
    return array_slice($templates, 0, $numSlides);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slide Generator - DeeReel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-layout">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <main>
                <h2>Social Media Slide Generator</h2>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                    <script>
                        // Auto-generate preview when page loads with success
                        document.addEventListener('DOMContentLoaded', function() {
                            setTimeout(generateAndDownload, 500);
                        });
                    </script>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Generate New Slides</div>
                            <div class="card-body">
                                <form method="POST" id="slideForm">
                                    <input type="hidden" name="action" value="generate_slides">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Topic</label>
                                        <select name="topic" id="topicSelect" class="form-select">
                                            <option value="Shoe Care Tips">Shoe Care Tips</option>
                                            <option value="Style Matching">Style Matching</option>
                                            <option value="DIY Repairs">DIY Repairs</option>
                                            <option value="Fun Facts">Fun Facts</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Number of Slides</label>
                                        <select name="slides" id="slidesSelect" class="form-select">
                                            <option value="3">3 Slides</option>
                                            <option value="4">4 Slides</option>
                                            <option value="5" selected>5 Slides</option>
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Save to Database</button>
                                    <button type="button" class="btn btn-success" onclick="generateAndDownload()">Generate & Preview</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Preview</div>
                            <div class="card-body">
                                <div id="slidePreview"></div>
                                <button class="btn btn-warning mt-3" onclick="downloadSlides()" style="display:none;" id="downloadBtn">Download All Slides</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/slide-generator.js"></script>
</body>
</html>
