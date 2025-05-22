<?php
require 'db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
  session_start();
  $_SESSION['user'] = ['name' => $user['name'], 'email' => $user['email']];
  header('Location: /dashboard.php');
  exit();
} else {
  echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
}
?>
