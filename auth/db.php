<?php
$host = 'localhost';
$db   = 'drf_database';
$user = 'root';
$pass = ''; // or your actual MySQL root password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
  echo json_encode(['error' => 'Database connection failed']);
  exit;
}
?>
