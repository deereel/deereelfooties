<?php
// Prevent PHP from showing errors in the output
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
require_once 'db.php';
require_once 'security.php';

// Close session immediately to release lock
session_write_close();

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $response = ['success' => false];
    
    try {
        // Get input data (handle both form data and JSON)
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input) {
            $email = trim($input['email'] ?? '');
            $password = $input['password'] ?? '';
        } else {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
        }
        
        // Rate limiting
        session_start();
        if (!checkRateLimit('login', 5, 900)) {
            session_write_close();
            $response['error'] = 'Too many login attempts. Try again in 15 minutes.';
            echo json_encode($response);
            exit;
        }
        session_write_close();
        
        // Input validation
        $email = sanitizeInput($email);
        if (empty($email) || empty($password)) {
            $response['error'] = 'Please fill in all fields';
            echo json_encode($response);
            exit;
        }
        
        if (!validateEmail($email)) {
            $response['error'] = 'Invalid email format';
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
            
            // Restart session to set data, then close immediately
            session_start();
            $_SESSION['user'] = [
                'id' => $user['user_id'] ?? $user['id'],
                'name' => $user['username'] ?? $user['name'],
                'email' => $user['email']
            ];
            session_write_close();
            
            // Return user data for client-side storage
            $response = [
                'success' => true,
                'user' => [
                    'id' => $user['user_id'] ?? $user['id'],
                    'user_id' => $user['user_id'] ?? $user['id'],
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