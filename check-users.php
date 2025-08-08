<?php
require_once 'auth/db.php';

try {
    $stmt = $pdo->query("SELECT user_id, email, name FROM users LIMIT 10");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Users in Database:</h2>";
    if (count($users) > 0) {
        foreach ($users as $user) {
            echo "<p>ID: {$user['user_id']} - Email: {$user['email']} - Name: " . ($user['name'] ?? 'N/A') . "</p>";
        }
    } else {
        echo "<p>No users found in database</p>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>