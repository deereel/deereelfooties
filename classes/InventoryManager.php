<?php
class InventoryManager {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Update stock quantity
    public function updateStock($productId, $quantity, $type = 'adjustment', $reason = '', $referenceId = '') {
        try {
            $this->pdo->beginTransaction();
            
            // Get current stock
            $stmt = $this->pdo->prepare("SELECT stock_quantity FROM products WHERE product_id = ?");
            $stmt->execute([$productId]);
            $currentStock = $stmt->fetchColumn();
            
            if ($currentStock === false) {
                throw new Exception("Product not found");
            }
            
            // Calculate new stock
            $newStock = $currentStock;
            if ($type === 'in') {
                $newStock += $quantity;
            } elseif ($type === 'out' || $type === 'sale') {
                $newStock -= $quantity;
            } else { // adjustment
                $newStock = $quantity;
            }
            
            // Prevent negative stock
            if ($newStock < 0) {
                throw new Exception("Insufficient stock");
            }
            
            // Update product stock
            $stmt = $this->pdo->prepare("UPDATE products SET stock_quantity = ? WHERE product_id = ?");
            $stmt->execute([$newStock, $productId]);
            
            // Record transaction
            $stmt = $this->pdo->prepare("INSERT INTO inventory_transactions 
                (product_id, transaction_type, quantity, previous_stock, new_stock, reason, reference_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$productId, $type, $quantity, $currentStock, $newStock, $reason, $referenceId]);
            
            // Check for low stock alert
            $this->checkLowStock($productId);
            
            $this->pdo->commit();
            return true;
            
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }
    
    // Check and create/resolve low stock alerts
    private function checkLowStock($productId) {
        $stmt = $this->pdo->prepare("SELECT stock_quantity, low_stock_threshold, name
            FROM products WHERE product_id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            if ($product['stock_quantity'] <= $product['low_stock_threshold']) {
                // Stock is low - check if alert already exists
                $stmt = $this->pdo->prepare("SELECT id FROM low_stock_alerts
                    WHERE product_id = ? AND status = 'active'");
                $stmt->execute([$productId]);

                if (!$stmt->fetch()) {
                    // Create new alert
                    $stmt = $this->pdo->prepare("INSERT INTO low_stock_alerts
                        (product_id, current_stock, threshold) VALUES (?, ?, ?)");
                    $stmt->execute([$productId, $product['stock_quantity'], $product['low_stock_threshold']]);
                }
            } else {
                // Stock is above threshold - resolve any active alerts
                $stmt = $this->pdo->prepare("UPDATE low_stock_alerts SET status = 'resolved', resolved_at = NOW()
                    WHERE product_id = ? AND status = 'active'");
                $stmt->execute([$productId]);
            }
        }
    }
    
    // Get low stock products
    public function getLowStockProducts() {
        $stmt = $this->pdo->query("SELECT p.*, a.created_at as alert_date 
            FROM products p 
            JOIN low_stock_alerts a ON p.product_id = a.product_id 
            WHERE a.status = 'active' AND p.stock_quantity <= p.low_stock_threshold
            ORDER BY p.stock_quantity ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Get inventory transactions
    public function getTransactions($productId = null, $limit = 50) {
        // First check if customer_name column exists
        try {
            $checkStmt = $this->pdo->prepare("SHOW COLUMNS FROM inventory_transactions LIKE 'customer_name'");
            $checkStmt->execute();
            $hasCustomerColumn = $checkStmt->rowCount() > 0;
        } catch (Exception $e) {
            $hasCustomerColumn = false;
        }
        
        if ($hasCustomerColumn) {
            $sql = "SELECT t.*, p.name as product_name,
                    CASE 
                        WHEN t.transaction_type = 'sale' AND t.customer_name IS NOT NULL 
                        THEN CONCAT(t.reason, ' - Customer: ', t.customer_name)
                        ELSE t.reason
                    END as display_reason
                FROM inventory_transactions t 
                JOIN products p ON t.product_id = p.product_id";
        } else {
            $sql = "SELECT t.*, p.name as product_name, t.reason as display_reason
                FROM inventory_transactions t 
                JOIN products p ON t.product_id = p.product_id";
        }
        
        $params = [];
        if ($productId) {
            $sql .= " WHERE t.product_id = ?";
            $params[] = $productId;
        }
        
        $sql .= " ORDER BY t.created_at DESC LIMIT " . (int)$limit;
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>