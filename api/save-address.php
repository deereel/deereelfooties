<?php

error_log("Reached [filename].php", 0);

// API endpoint to save or update an address
header('Content-Type: application/json');
require_once '../auth/db.php';

// Check if user is logged in
session_start();
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get request data
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is in the expected format
if (isset($data['address'])) {
    // Format from address-modal.php
    $addressData = $data['address'];
    $userId = $data['user_id'];
} else {
    // Format from dashboard-address.js
    $addressData = $data;
    $userId = $data['user_id'];
}

// Validate required fields
$requiredFields = ['full_name', 'line1', 'city', 'state', 'country'];
foreach ($requiredFields as $field) {
    if (!isset($addressData[$field]) || empty($addressData[$field])) {
        echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required']);
        exit;
    }
}

try {
    // Check if this is an update or new address
    $isUpdate = isset($addressData['id']) && !empty($addressData['id']);
    
    // If setting as default, update all other addresses to non-default
    if (isset($addressData['is_default']) && $addressData['is_default'] == 1) {
        $stmt = $pdo->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
        $stmt->execute([$userId]);
    }
    
    if ($isUpdate) {
        // Update existing address
        $stmt = $pdo->prepare("
            UPDATE user_addresses 
            SET name = ?, address_type = ?, full_name = ?, line1 = ?, line2 = ?, 
                city = ?, state = ?, country = ?, phone = ?, is_default = ?
            WHERE address_id = ? AND user_id = ?
        ");
        
        $stmt->execute([
            $addressData['name'] ?? '',
            $addressData['address_type'] ?? 'shipping',
            $addressData['full_name'],
            $addressData['line1'],
            $addressData['line2'] ?? '',
            $addressData['city'],
            $addressData['state'],
            $addressData['country'],
            $addressData['phone'] ?? '',
            $addressData['is_default'] ?? 0,
            $addressData['id'],
            $userId
        ]);
        
        $message = 'Address updated successfully';
    } else {
        // Insert new address
        $stmt = $pdo->prepare("
            INSERT INTO user_addresses 
            (user_id, name, address_type, full_name, line1, line2, city, state, country, phone, is_default)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $userId,
            $addressData['name'] ?? '',
            $addressData['address_type'] ?? 'shipping',
            $addressData['full_name'],
            $addressData['line1'],
            $addressData['line2'] ?? '',
            $addressData['city'],
            $addressData['state'],
            $addressData['country'],
            $addressData['phone'] ?? '',
            $addressData['is_default'] ?? 0
        ]);
        
        $message = 'Address added successfully';
    }
    
    // Return success response
    echo json_encode(['success' => true, 'message' => $message]);
} catch (PDOException $e) {
    // Return error response
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}