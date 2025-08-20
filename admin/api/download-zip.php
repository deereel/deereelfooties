<?php
require_once '../../config/database.php';

$postId = $_GET['id'] ?? '';
if (!$postId) exit('Invalid post ID');

$stmt = $pdo->prepare("SELECT * FROM social_posts WHERE post_id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) exit('Post not found');

$slides = json_decode($post['content'], true);
if (!$slides) exit('No slides found');

// Create ZIP file
$zip = new ZipArchive();
$zipName = "deereel_post_{$postId}.zip";
$zipPath = "../../uploads/temp/{$zipName}";

// Create temp directory if it doesn't exist
if (!is_dir('../../uploads/temp')) {
    mkdir('../../uploads/temp', 0777, true);
}

if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
    foreach ($slides as $index => $slidePath) {
        if (file_exists($slidePath)) {
            $zip->addFile($slidePath, "slide_" . ($index + 1) . ".png");
        }
    }
    
    // Add post info
    $postInfo = "DeeReel Footies Social Media Post\n\n";
    $postInfo .= "Topic: " . $post['topic'] . "\n";
    $postInfo .= "Generated: " . $post['created_at'] . "\n";
    $postInfo .= "Slides: " . $post['slides'] . "\n\n";
    $postInfo .= "Visit deereelfooties.com";
    
    $zip->addFromString("post_info.txt", $postInfo);
    $zip->close();
    
    // Download ZIP
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipName . '"');
    header('Content-Length: ' . filesize($zipPath));
    readfile($zipPath);
    
    // Clean up
    unlink($zipPath);
} else {
    exit('Failed to create ZIP file');
}
?>