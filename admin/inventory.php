<?php
session_start();
require_once '../auth/db.php';
require_once '../classes/InventoryManager.php';

$inventory = new InventoryManager($pdo);
$lowStockProducts = $inventory->getLowStockProducts();
$recentTransactions = $inventory->getTransactions();

// Get all products with stock info
$stmt = $pdo->query("SELECT product_id, name, stock_quantity, low_stock_threshold, sku, price
    FROM products ORDER BY name");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Log inventory viewing activity
logActivity($_SESSION['admin_user_id'], $_SESSION['admin_username'], 'view_inventory', 'inventory', 'read', null, 'Viewed inventory management page');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory Management | DeeReel Footies Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/admin.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-layout">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <main>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">📦 Inventory Management</h1>
                </div>
        
                <!-- Low Stock Alerts -->
                <?php if (count($lowStockProducts) > 0): ?>
                <div class="alert low-stock-alert">
                    <h5><i class="bi bi-exclamation-triangle"></i> Low Stock Alerts (<?= count($lowStockProducts) ?>)</h5>
                    <?php foreach ($lowStockProducts as $product): ?>
                        <div class="d-flex justify-content-between align-items-center py-1">
                            <span><?= $product['name'] ?></span>
                            <span class="badge bg-warning">Only <?= $product['stock_quantity'] ?> left</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
        
        <!-- Quick Stock Update -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Quick Stock Update</div>
                    <div class="card-body">
                        <form id="stockUpdateForm">
                            <div class="mb-3">
                                <select name="product_id" class="form-select" required>
                                    <option value="">Select Product</option>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?= $product['product_id'] ?>">
                                            <?= $product['name'] ?> (Current: <?= $product['stock_quantity'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <select name="type" class="form-select" required>
                                    <option value="in">Stock In (+)</option>
                                    <option value="out">Stock Out (-)</option>
                                    <option value="adjustment">Set Exact Amount</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="reason" class="form-control" placeholder="Reason (optional)">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Stock</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Products Stock Table -->
        <div class="card">
            <div class="card-header">All Products Stock</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Current Stock</th>
                                <th>Low Stock Threshold</th>
                                <th>Status</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product['name'] ?></td>
                                <td><?= $product['sku'] ?: 'N/A' ?></td>
                                <td>
                                    <span class="badge stock-badge <?= $product['stock_quantity'] <= $product['low_stock_threshold'] ? 'bg-danger' : 'bg-success' ?>">
                                        <?= $product['stock_quantity'] ?>
                                    </span>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm threshold-input" 
                                           value="<?= $product['low_stock_threshold'] ?>" 
                                           data-product-id="<?= $product['product_id'] ?>" 
                                           min="0" max="100" style="width: 80px;">
                                </td>
                                <td>
                                    <?php if ($product['stock_quantity'] <= $product['low_stock_threshold']): ?>
                                        <span class="badge bg-warning">Low Stock</span>
                                    <?php elseif ($product['stock_quantity'] == 0): ?>
                                        <span class="badge bg-danger">Out of Stock</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">In Stock</span>
                                    <?php endif; ?>
                                </td>
                                <td>₦<?= number_format($product['price']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="card mt-4">
            <div class="card-header">Recent Inventory Transactions</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Previous</th>
                                <th>New Stock</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentTransactions as $transaction): ?>
                            <tr>
                                <td><?= date('M j, H:i', strtotime($transaction['created_at'])) ?></td>
                                <td><?= $transaction['product_name'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $transaction['transaction_type'] === 'in' ? 'success' : ($transaction['transaction_type'] === 'out' ? 'danger' : ($transaction['transaction_type'] === 'sale' ? 'warning' : 'info')) ?>">
                                        <?= ucfirst($transaction['transaction_type']) ?>
                                    </span>
                                </td>
                                <td><?= $transaction['quantity'] ?></td>
                                <td><?= $transaction['previous_stock'] ?></td>
                                <td><?= $transaction['new_stock'] ?></td>
                                <td><?= $transaction['display_reason'] ?: $transaction['reason'] ?: '-' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('stockUpdateForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        try {
            const response = await fetch('../api/update-stock.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            if (data.success) {
                alert('Stock updated successfully!');
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            alert('Network error: ' + error.message);
        }
    });
    
    // Handle threshold updates
    document.querySelectorAll('.threshold-input').forEach(input => {
        input.addEventListener('change', async function() {
            const productId = this.dataset.productId;
            const threshold = this.value;
            
            try {
                const response = await fetch('../api/update-threshold.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        threshold: threshold
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.style.borderColor = '#28a745';
                    setTimeout(() => {
                        this.style.borderColor = '';
                        location.reload();
                    }, 1000);
                } else {
                    alert('Error updating threshold: ' + data.message);
                    this.style.borderColor = '#dc3545';
                }
            } catch (error) {
                alert('Network error: ' + error.message);
                this.style.borderColor = '#dc3545';
            }
        });
    });
    </script>
</body>
</html>
