<?php
class OrderProcessor {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Process new order
    public function processOrder($orderId) {
        try {
            $this->pdo->beginTransaction();
            
            // Get order details
            $order = $this->getOrder($orderId);
            if (!$order) throw new Exception("Order not found");
            
            // Update inventory
            $this->updateInventoryForOrder($orderId);
            
            // Update order status
            $this->updateOrderStatus($orderId, 'processing');
            
            // Send confirmation email/WhatsApp
            $this->sendOrderConfirmation($order);
            
            $this->pdo->commit();
            return true;
            
        } catch (Exception $e) {
            $this->pdo->rollback();
            throw $e;
        }
    }
    
    // Update inventory for order items
    private function updateInventoryForOrder($orderId) {
        $stmt = $this->pdo->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($items as $item) {
            // Reduce stock
            $stmt = $this->pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
            $stmt->execute([$item['quantity'], $item['product_id']]);
            
            // Log inventory transaction
            $stmt = $this->pdo->prepare("INSERT INTO inventory_transactions (product_id, transaction_type, quantity, reason, reference_id) VALUES (?, 'out', ?, 'Order sale', ?)");
            $stmt->execute([$item['product_id'], $item['quantity'], "ORDER-$orderId"]);
        }
    }
    
    // Update order status
    public function updateOrderStatus($orderId, $status) {
        $stmt = $this->pdo->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE order_id = ?");
        $stmt->execute([$status, $orderId]);
        
        // Log status change
        $stmt = $this->pdo->prepare("INSERT INTO order_status_history (order_id, status, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$orderId, $status]);
    }
    
    // Get order details
    private function getOrder($orderId) {
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Send order confirmation
    private function sendOrderConfirmation($order) {
        // WhatsApp notification (simplified)
        $message = "Order #{$order['order_id']} confirmed! Total: ₦" . number_format($order['total_amount']) . ". We'll process your order within 24 hours.";
        // In production, integrate with WhatsApp Business API
    }
    
    // Auto-process pending orders
    public function autoProcessPendingOrders() {
        $stmt = $this->pdo->query("SELECT order_id FROM orders WHERE status = 'pending' AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($orders as $order) {
            try {
                $this->processOrder($order['order_id']);
            } catch (Exception $e) {
                error_log("Auto-process failed for order {$order['order_id']}: " . $e->getMessage());
            }
        }
    }
}
?>