<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Only accept POST requests
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = ['name' => $user['name'], 'email' => $user['email']];
    header('Location: /dashboard.php');
    exit();
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
}
?>
