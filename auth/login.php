<?php
require 'db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
  echo json_encode(['success' => true, 'user' => ['name' => $user['name'], 'email' => $user['email']]]);
} else {
  echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
}
?>
