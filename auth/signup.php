<?php
// Prevent PHP from showing errors in the output
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
require_once 'db.php';

// Handle signup request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $response = ['success' => false];
    
    try {
        // Get form data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($name) || empty($email) || empty($password)) {
            $response['error'] = 'Please fill in all fields';
            echo json_encode($response);
            exit;
        }
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['error'] = 'Please enter a valid email address';
            echo json_encode($response);
            exit;
        }
        
        // Validate password (at least 6 characters)
        if (strlen($password) < 6) {
            $response['error'] = 'Password must be at least 6 characters long';
            echo json_encode($response);
            exit;
        }
        
        $pdo = new PDO("mysql:host=localhost;dbname=drf_database;charset=utf8mb4", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $response['error'] = 'Email already in use';
            echo json_encode($response);
            exit;
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$name, $email, $hashedPassword]);
        
        $userId = $pdo->lastInsertId();
        
        // Set session data
        $_SESSION['user'] = [
            'id' => $userId,
            'name' => $name,
            'email' => $email
        ];
        
        // Return user data for client-side storage
        $response = [
            'success' => true,
            'user' => [
                'id' => $userId,
                'user_id' => $userId,
                'name' => $name,
                'email' => $email
            ]
        ];
    } catch (Exception $e) {
        $response['error'] = 'Registration failed. Please try again.';
        error_log("Signup error: " . $e->getMessage());
    }
    
    // Return JSON response
    echo json_encode($response);
    exit;
}

// If not a POST request, return JSON error
header('Content-Type: application/json');
echo json_encode(['success' => false, 'error' => 'Invalid request method']);
exit;
?>