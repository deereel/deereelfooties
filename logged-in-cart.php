<?php
require_once 'auth/db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
    header('Location: /index.php');
    exit();
}

// Get current user from session with proper error handling
$currentUser = null;
$userId = null;

if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    $currentUser = $_SESSION['user'];
    $userId = $currentUser['id'] ?? $currentUser['user_id'] ?? null;
} elseif (isset($_SESSION['user_id'])) {
    // Fallback to old session format
    $userId = $_SESSION['user_id'];
    $currentUser = [
        'id' => $userId,
        'name' => $_SESSION['username'] ?? 'User',
        'email' => $_SESSION['user_email'] ?? ''
    ];
}

// If still no user ID, redirect
if (!$userId) {
    error_log("No user ID found in session. Session data: " . print_r($_SESSION, true));
    header('Location: /index.php');
    exit();
}

// Database connection
require_once 'config/database.php';

$cartItems = [];
$totalAmount = 0;

try {
    // Get cart items for logged-in user
    $stmt = $pdo->prepare("
        SELECT c.*, p.name, p.price, p.image_url, p.description 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ? 
        ORDER BY c.created_at DESC
    ");
    
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate total
    foreach ($cartItems as $item) {
        $itemTotal = floatval($item['price']) * intval($item['quantity']);
        $totalAmount += $itemTotal;
    }
    
} catch (PDOException $e) {
    error_log("Cart fetch error: " . $e->getMessage());
    $cartItems = [];
}




$pageTitle = "Shopping Cart";
$currentPage = 'cart';
?>

<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
<title><?= htmlspecialchars($pageTitle) ?> - DeeReel Footies</title>


<body data-page="logged-in-cart">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
    
    <main class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Your Shopping Cart
                </h1>
                
                <?php if (empty($cartItems)): ?>
                    <!-- Empty Cart -->
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                        <h3 class="text-muted">Your cart is empty</h3>
                        <p class="text-muted mb-4">Add some amazing footwear to get started!</p>
                        <a href="/products.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Continue Shopping
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Cart Items -->
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Cart Items (<?= count($cartItems) ?>)</h5>
                                </div>
                                <div class="card-body p-0">
                                    <?php foreach ($cartItems as $index => $item): ?>
                                        <div class="cart-item border-bottom p-3" data-cart-id="<?= htmlspecialchars($item['id']) ?>">
                                            <div class="row align-items-center">
                                                <!-- Product Image -->
                                                <div class="col-md-2">
                                                    <img src="<?= htmlspecialchars($item['image_url'] ?? '/images/placeholder.jpg') ?>" 
                                                         alt="<?= htmlspecialchars($item['name']) ?>" 
                                                         class="img-fluid rounded">
                                                </div>
                                                
                                                <!-- Product Details -->
                                                <div class="col-md-4">
                                                    <h6 class="mb-1"><?= htmlspecialchars($item['name']) ?></h6>
                                                    <small class="text-muted">
                                                        <?php if (!empty($item['color'])): ?>
                                                            Color: <?= htmlspecialchars($item['color']) ?><br>
                                                        <?php endif; ?>
                                                        <?php if (!empty($item['size'])): ?>
                                                            Size: <?= htmlspecialchars($item['size']) ?><br>
                                                        <?php endif; ?>
                                                        <?php if (!empty($item['width'])): ?>
                                                            Width: <?= htmlspecialchars($item['width']) ?>
                                                        <?php endif; ?>
                                                    </small>
                                                </div>
                                                
                                                <!-- Quantity Controls -->
                                                <div class="col-md-2">
                                                    <div class="input-group input-group-sm">
                                                        <button class="btn btn-outline-secondary quantity-btn" 
                                                                type="button" 
                                                                data-action="decrease" 
                                                                data-cart-id="<?= htmlspecialchars($item['id']) ?>">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <input type="number" 
                                                               class="form-control text-center quantity-input" 
                                                               value="<?= intval($item['quantity']) ?>" 
                                                               min="1" 
                                                               max="10"
                                                               data-cart-id="<?= htmlspecialchars($item['id']) ?>">
                                                        <button class="btn btn-outline-secondary quantity-btn" 
                                                                type="button" 
                                                                data-action="increase" 
                                                                data-cart-id="<?= htmlspecialchars($item['id']) ?>">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <!-- Price -->
                                                <div class="col-md-2">
                                                    <div class="text-center">
                                                        <div class="fw-bold item-price">
                                                            ₦<?= number_format(floatval($item['price']) * intval($item['quantity'])) ?>
                                                        </div>
                                                        <small class="text-muted">
                                                            ₦<?= number_format(floatval($item['price'])) ?> each
                                                        </small>
                                                    </div>
                                                </div>
                                                
                                                <!-- Remove Button -->
                                                <div class="col-md-2">
                                                    <button class="btn btn-outline-danger btn-sm remove-item" 
                                                            data-cart-id="<?= htmlspecialchars($item['id']) ?>"
                                                            title="Remove item">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Order Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span class="subtotal">₦<?= number_format($totalAmount) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Shipping:</span>
                                        <span class="shipping-cost">₦5,000</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <strong>Total:</strong>
                                        <strong class="total-amount">₦<?= number_format($totalAmount + 5000) ?></strong>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="/checkout.php" class="btn btn-primary btn-lg">
                                            <i class="fas fa-credit-card me-2"></i>
                                            Proceed to Checkout
                                        </a>
                                        <a href="/products.php" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            Continue Shopping
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <?php include 'components/footer.php'; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity controls
        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.dataset.action;
                const cartId = this.dataset.cartId;
                const input = document.querySelector(`input[data-cart-id="${cartId}"]`);
                
                let currentValue = parseInt(input.value);
                
                if (action === 'increase' && currentValue < 10) {
                    input.value = currentValue + 1;
                } else if (action === 'decrease' && currentValue > 1) {
                    input.value = currentValue - 1;
                }
                
                updateCartItem(cartId, input.value);
            });
        });
        
        // Quantity input change
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const cartId = this.dataset.cartId;
                const quantity = Math.max(1, Math.min(10, parseInt(this.value) || 1));
                this.value = quantity;
                updateCartItem(cartId, quantity);
            });
        });
        
        // Remove item
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove this item?')) {
                    const cartId = this.dataset.cartId;
                    removeCartItem(cartId);
                }
            });
        });
        
        function updateCartItem(cartId, quantity) {
            fetch('/api/update-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    cart_id: cartId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Refresh to update totals
                } else {
                    alert('Error updating cart: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating cart');
            });
        }
        
        function removeCartItem(cartId) {
            fetch('/api/remove-from-cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    cart_id: cartId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Refresh page
                } else {
                    alert('Error removing item: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error removing item');
            });
        }
    });
    </script>
</body>
</html>