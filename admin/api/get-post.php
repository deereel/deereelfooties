<?php
require_once '../../config/database.php';

$postId = $_GET['id'] ?? '';
if (!$postId) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid post ID']);
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM social_posts WHERE post_id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    http_response_code(404);
    echo json_encode(['error' => 'Post not found']);
    exit();
}

header('Content-Type: application/json');
echo json_encode($post);
?>