<?php
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'root', '', 'drf_shop');

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'DB Connection failed']));
}

$name = $_POST['name'] ?? '';
$address = $_POST['address'] ?? '';
$proofPath = '';

if (isset($_FILES['proof'])) {
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
    $proofPath = $uploadDir . basename($_FILES['proof']['name']);
    move_uploaded_file($_FILES['proof']['tmp_name'], $proofPath);
}

$stmt = $conn->prepare("INSERT INTO customers (name, address, proof) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $address, $proofPath);
$stmt->execute();

echo json_encode(['success' => true]);
?>
