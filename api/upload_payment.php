<?php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Check if file was uploaded
if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit;
}

// Get order and user IDs
$orderId = isset($_POST['order_id']) ? $_POST['order_id'] : null;
$userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;

if (!$orderId) {
    echo json_encode(['success' => false, 'message' => 'Missing order ID']);
    exit;
}

// Create uploads directory if it doesn't exist
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/payments/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Get file info
$file = $_FILES['payment_proof'];
$fileName = $file['name'];
$fileType = $file['type'];
$fileTmpName = $file['tmp_name'];
$fileError = $file['error'];
$fileSize = $file['size'];

// Generate unique filename
$fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
$newFileName = 'payment_' . $orderId . '_' . time() . '.' . $fileExtension;
$targetFilePath = $uploadDir . $newFileName;

// Check file type
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
if (!in_array($fileType, $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and PDF files are allowed.']);
    exit;
}

// Check file size (5MB max)
if ($fileSize > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'File is too large. Maximum size is 5MB.']);
    exit;
}

// Move uploaded file
if (move_uploaded_file($fileTmpName, $targetFilePath)) {
    // File uploaded successfully, save to database
    try {
        // Save file path to database
        $relativePath = '/uploads/payments/' . $newFileName;
        
        $stmt = $pdo->prepare("INSERT INTO payment_proofs (order_id, user_id, file_path, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$orderId, $userId, $relativePath]);
        
        // Update order payment status
        $updateStmt = $pdo->prepare("UPDATE orders SET payment_status = 'uploaded' WHERE order_id = ?");
        $updateStmt->execute([$orderId]);
        
        echo json_encode(['success' => true, 'message' => 'Payment proof uploaded successfully', 'file_path' => $relativePath]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
}
?>