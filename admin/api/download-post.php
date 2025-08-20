<?php
require_once '../../config/database.php';

$postId = $_GET['id'] ?? '';
if (!$postId) exit('Invalid post ID');

$stmt = $pdo->prepare("SELECT * FROM social_posts WHERE post_id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) exit('Post not found');

// Check if GD extension is available
if (!extension_loaded('gd')) {
    // Fallback: create text file instead
    $content = "DeeReel Footies Social Media Post\n\n";
    $content .= "Topic: " . $post['topic'] . "\n\n";
    $content .= $post['content'] . "\n\n";
    $content .= "Visit deereelfooties.com";
    
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="deereel_post_' . $postId . '.txt"');
    echo $content;
    exit;
}

$width = 1080;
$height = 1350;
$image = imagecreatetruecolor($width, $height);

$bgColor = imagecolorallocate($image, 45, 45, 45);
$textColor = imagecolorallocate($image, 255, 255, 255);
$accentColor = imagecolorallocate($image, 255, 193, 7);

imagefill($image, 0, 0, $bgColor);

imagestring($image, 5, 50, 50, 'DeeReel Footies', $accentColor);

$lines = explode("\n", wordwrap($post['content'], 50, "\n"));
$y = 150;
foreach ($lines as $line) {
    imagestring($image, 3, 50, $y, $line, $textColor);
    $y += 25;
}

imagestring($image, 4, 50, $height - 80, 'Visit deereelfooties.com', $accentColor);

header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="deereel_post_' . $postId . '.png"');
imagepng($image);
imagedestroy($image);
?>