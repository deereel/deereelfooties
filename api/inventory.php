<?php
require_once '../auth/db.php';
require_once '../classes/InventoryManager.php';

// Set headers for JSON response
header('Content-Type: application/json');

// Handle different HTTP methods
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // Update stock quantity
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate required fields
        if (!isset($data['product_id']) || !isset($data['quantity'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields: product_id and quantity']);
            exit;
        }

        try {
            $inventoryManager = new InventoryManager($pdo);

            // Use the updateStock method with adjustment type
            $type = $data['type'] ?? 'adjustment';
            $reason = $data['reason'] ?? 'Stock adjustment via API';

            $result = $inventoryManager->updateStock(
                $data['product_id'],
                $data['quantity'],
                $type,
                $reason
            );

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Stock updated successfully',
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity']
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update stock']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error updating stock: ' . $e->getMessage()]);
        }
        break;

    case 'GET':
        // Get inventory information
        if (isset($_GET['product_id'])) {
            // Get specific product inventory
            $stmt = $pdo->prepare("SELECT product_id, name, stock_quantity, low_stock_threshold FROM products WHERE product_id = ?");
            $stmt->execute([$_GET['product_id']]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                echo json_encode(['success' => true, 'data' => $product]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
            }
        } else {
            // Get all products inventory
            $stmt = $pdo->query("SELECT product_id, name, stock_quantity, low_stock_threshold FROM products ORDER BY name");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $products]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>
