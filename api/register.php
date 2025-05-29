<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php-errors.log');

require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Log the request method and data
file_put_contents('../logs/register-debug.log', 'Request Method: ' . $_SERVER['REQUEST_METHOD'] . PHP_EOL, FILE_APPEND);
file_put_contents('../logs/register-debug.log', 'Raw Input: ' . file_get_contents('php://input') . PHP_EOL, FILE_APPEND);

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get registration data
$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('../logs/register-debug.log', 'Decoded Data: ' . print_r($data, true) . PHP_EOL, FILE_APPEND);

// Validate required fields
if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$name = $data['name'];
$email = $data['email'];
$password = $data['password'];

// Check if email already exists
$stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->execute([$email]);
$existingUser = $stmt->fetch();

if ($existingUser) {
    echo json_encode(['success' => false, 'message' => 'Email already registered']);
    exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    // Log the SQL operation
    file_put_contents('../logs/register-debug.log', 'Attempting to insert user: ' . $name . ', ' . $email . PHP_EOL, FILE_APPEND);
    
    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$name, $email, $hashedPassword]);
    
    // Get the new user ID
    $userId = $pdo->lastInsertId();
    
    // Log success
    file_put_contents('../logs/register-debug.log', 'User created with ID: ' . $userId . PHP_EOL, FILE_APPEND);
    
    // Return success with user data
    echo json_encode([
        'success' => true, 
        'message' => 'Registration successful',
        'user' => [
            'user_id' => $userId,
            'name' => $name,
            'email' => $email
        ]
    ]);
} catch (PDOException $e) {
    // Log the error
    file_put_contents('../logs/register-debug.log', 'Database Error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
    
    echo json_encode(['success' => false, 'message' => 'Error creating user: ' . $e->getMessage()]);
}
?>
