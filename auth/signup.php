<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        die('Invalid email or password too short.');
    }

    // Hash password
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed);

    if ($stmt->execute()) {
        header("Location: /index.php?signup=success");
        exit();
    } else {
        echo "Signup failed: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
