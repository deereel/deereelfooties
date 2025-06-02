<?php
require_once '../auth/db.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get products (with optional filters)
        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];
        
        // Apply filters if provided
        if (isset($_GET['gender'])) {
            $sql .= " AND gender = ?";
            $params[] = $_GET['gender'];
        }
        
        if (isset($_GET['category'])) {
            $sql .= " AND category = ?";
            $params[] = $_GET['category'];
        }
        
        if (isset($_GET['type'])) {
            $sql .= " AND type = ?";
            $params[] = $_GET['type'];
        }
        
        if (isset($_GET['featured'])) {
            $sql .= " AND is_featured = 1";
        }
        
        if (isset($_GET['new_collection'])) {
            $sql .= " AND is_new_collection = 1";
        }
        
        // Add sorting
        $sql .= " ORDER BY created_at DESC";
        
        // Execute query
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'data' => $products]);
        break;
        
    case 'POST':
        // Add new product
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        $requiredFields = ['name', 'slug', 'price', 'gender', 'category', 'type', 'main_image'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
                exit;
            }
        }
        
        // Check if slug already exists
        $stmt = $pdo->prepare("SELECT product_id FROM products WHERE slug = ?");
        $stmt->execute([$data['slug']]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Product with this slug already exists']);
            exit;
        }
        
        // Insert new product
        $sql = "INSERT INTO products (name, slug, description, details_care, short_description, price, gender, 
                category, type, colors, sizes, main_image, additional_images, features, 
                is_featured, is_new_collection) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $pdo->prepare($sql);
        
        try {
            $stmt->execute([
                $data['name'],
                $data['slug'],
                $data['description'] ?? '',
                $data['details_care'] ?? '',
                $data['short_description'] ?? '',
                $data['price'],
                $data['gender'],
                $data['category'],
                $data['type'],
                $data['colors'] ?? '',
                $data['sizes'] ?? '',
                $data['main_image'],
                $data['additional_images'] ?? '',
                $data['features'] ?? '',
                $data['is_featured'] ?? 0,
                $data['is_new_collection'] ?? 0
            ]);
            
            $productId = $pdo->lastInsertId();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Product added successfully',
                'product_id' => $productId
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error adding product: ' . $e->getMessage()]);
        }
        break;

    // Add this to your api/products.php file in the switch statement
    case 'PUT':
        // Update existing product
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['product_id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing product_id']);
            exit;
        }
        
        // Build update query
        $updateFields = [];
        $params = [];
        
        $fields = [
            'name', 'slug', 'price', 'gender', 'category', 'type', 
            'description', 'details_care', 'short_description', 'colors', 'sizes', 
            'main_image', 'additional_images', 'features', 
            'is_featured', 'is_new_collection'
        ];
        
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $updateFields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        
        // Add product_id as the last parameter
        $params[] = $data['product_id'];
        
        if (!empty($updateFields)) {
            $sql = "UPDATE products SET " . implode(", ", $updateFields) . " WHERE product_id = ?";
            $stmt = $pdo->prepare($sql);
            
            try {
                $stmt->execute($params);
                echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Error updating product: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No fields to update']);
        }
        break;

    case 'DELETE':
        // Delete product
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['product_id'])) {
            echo json_encode(['success' => false, 'message' => 'Missing product_id']);
            exit;
        }
        
        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
        try {
            $stmt->execute([$data['product_id']]);
            echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error deleting product: ' . $e->getMessage()]);
        }
        break;

        
    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>