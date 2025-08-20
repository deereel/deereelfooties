<?php
require_once '../../config/database.php';
require_once '../../classes/SocialMediaGenerator.php';

$input = json_decode(file_get_contents('php://input'), true);

if ($input['action'] === 'auto_generate') {
    $generator = new SocialMediaGenerator($pdo);
    
    // Generate post
    $slides = rand(3, 5);
    $post = $generator->createPost($slides);
    
    // Auto-schedule for alternating platforms
    $lastPost = $pdo->query("SELECT platform FROM social_posts WHERE status = 'scheduled' ORDER BY created_at DESC LIMIT 1")->fetch();
    $platform = ($lastPost && $lastPost['platform'] === 'instagram') ? 'tiktok' : 'instagram';
    
    $time = (date('H') < 12) ? '06:00' : '18:00';
    $generator->schedulePost($post['id'], $platform, $time);
    
    echo json_encode(['success' => true, 'post_id' => $post['id'], 'platform' => $platform]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action']);
}
?>