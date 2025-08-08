<?php
// This file shows how to update your products display to show stock status
// Add this code to your products.php and product.php files

function getStockStatus($stockQuantity, $threshold = 5) {
    if ($stockQuantity <= 0) {
        return ['status' => 'out_of_stock', 'message' => 'Out of Stock', 'class' => 'text-danger'];
    } elseif ($stockQuantity <= $threshold) {
        return ['status' => 'low_stock', 'message' => "Only {$stockQuantity} left", 'class' => 'text-warning'];
    } else {
        return ['status' => 'in_stock', 'message' => 'In Stock', 'class' => 'text-success'];
    }
}

// Example usage in product card:
/*
<?php $stockStatus = getStockStatus($product['stock_quantity'], $product['low_stock_threshold']); ?>
<div class="product-card">
    <img src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?>">
    <h3><?= $product['name'] ?></h3>
    <p>₦<?= number_format($product['price']) ?></p>
    <p class="<?= $stockStatus['class'] ?>"><?= $stockStatus['message'] ?></p>
    
    <?php if ($stockStatus['status'] !== 'out_of_stock'): ?>
        <button class="btn btn-primary add-to-cart" data-product-id="<?= $product['product_id'] ?>">
            Add to Cart
        </button>
    <?php else: ?>
        <button class="btn btn-secondary" disabled>Out of Stock</button>
    <?php endif; ?>
</div>
*/

echo "✅ Stock display functions created. Copy the code above to your product display files.";
?>