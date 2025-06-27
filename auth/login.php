<?php
// Prevent PHP from showing errors in the output
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
require_once 'db.php';

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $response = ['success' => false];
    
    try {
        // Get form data
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $response['error'] = 'Please fill in all fields';
            echo json_encode($response);
            exit;
        }
        
        $pdo = new PDO("mysql:host=localhost;dbname=drf_database;charset=utf8mb4", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            
            // Set session data - use user_id instead of id
            $_SESSION['user'] = [
                'id' => $user['user_id'] ?? $user['id'],
                'name' => $user['username'] ?? $user['name'],
                'email' => $user['email']
            ];
            
            // Return user data for client-side storage
            $response = [
                'success' => true,
                'user' => [
                    'id' => $user['user_id'] ?? $user['id'],
                    'name' => $user['username'] ?? $user['name'],
                    'email' => $user['email']
                ]
            ];
        } else {
            $response['error'] = 'Invalid email or password';
        }
    } catch (Exception $e) {
        $response['error'] = 'Login failed. Please try again.';
        error_log("Login error: " . $e->getMessage());
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