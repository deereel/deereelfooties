<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user'];
$page = 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DeeReel Footies</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Website CSS -->
    <link rel="stylesheet" href="/css/colors.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <style>
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .sidebar-fixed {
            position: sticky;
            top: 20px;
            height: calc(100vh - 40px);
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <?php include('components/navbar.php'); ?>

    <div class="container py-5">
        <div class="row">
            <!-- User Info -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">My Account</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-accent rounded-circle text-white d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                            <div>
                                <h4 class="mb-0"><?php echo htmlspecialchars($user['name']); ?></h4>
                                <p class="text-muted mb-0"><?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Dashboard Content -->
            <div class="col-md-3">
                <!-- Sidebar Navigation -->
                <div class="sidebar-fixed">
                    <div class="list-group mb-4 shadow" id="dashboard-tabs">
                        <a href="#" class="list-group-item list-group-item-action active" data-tab="dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" data-tab="orders">
                            <i class="fas fa-box me-2"></i> My Orders
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" data-tab="wishlist">
                            <i class="fas fa-heart me-2"></i> Wishlist
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" data-tab="address">
                            <i class="fas fa-address-book me-2"></i> Address Book
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" data-tab="personal">
                            <i class="fas fa-user-edit me-2"></i> My Data
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" data-tab="designs">
                            <i class="fas fa-palette me-2"></i> My Designs
                        </a>
                        
                        <div class="dropdown-divider my-2"></div>
                        
                        <a href="#" class="list-group-item list-group-item-action text-danger" data-tab="delete">
                            <i class="fas fa-user-times me-2"></i> Delete Account
                        </a>
                        <a href="#" class="list-group-item list-group-item-action text-danger" id="logout-btn">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <!-- Dashboard Tab -->
                <div id="dashboard-tab" class="tab-content active">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">Dashboard</h3>
                        </div>
                        <div class="card-body">
                            <p>Welcome back, <strong><?php echo htmlspecialchars($user['name']); ?></strong>!</p>
                            
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-box-open fa-2x mb-3 text-primary"></i>
                                            <h5>Orders</h5>
                                            <p class="mb-0">View your order history</p>
                                            <button class="btn btn-outline-primary mt-3 tab-link" data-tab="orders">View Orders</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-heart fa-2x mb-3 text-accent"></i>
                                            <h5>Wishlist</h5>
                                            <p class="mb-0">View your saved items</p>
                                            <button class="btn btn-outline-primary mt-3 tab-link" data-tab="wishlist">View Wishlist</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-palette fa-2x mb-3 text-success"></i>
                                            <h5>My Designs</h5>
                                            <p class="mb-0">View your custom designs</p>
                                            <button class="btn btn-outline-primary mt-3 tab-link" data-tab="designs">View Designs</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <i class="fas fa-user-edit fa-2x mb-3 text-info"></i>
                                            <h5>Account</h5>
                                            <p class="mb-0">Update your details</p>
                                            <button class="btn btn-outline-primary mt-3 tab-link" data-tab="personal">Edit Details</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Orders Tab -->
                <div id="orders-tab" class="tab-content">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">My Orders</h3>
                        </div>
                        <div class="card-body">
                            <div id="orders-container">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p class="mt-2">Loading your orders...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Wishlist Tab -->
                <div id="wishlist-tab" class="tab-content">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">My Wishlist</h3>
                        </div>
                        <div class="card-body">
                            <div id="wishlist-container">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p class="mt-2">Loading your wishlist...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Address Book Tab -->
                <div id="address-tab" class="tab-content">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">Address Book</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Shipping Address</h5>
                                            <button class="btn btn-sm btn-outline-primary">Edit</button>
                                        </div>
                                        <div class="card-body">
                                            <address>
                                                <?php echo htmlspecialchars($user['name']); ?><br>
                                                123 Main Street<br>
                                                Lekki Phase 1<br>
                                                Lagos, Nigeria<br>
                                                Phone: +234 801 234 5678
                                            </address>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Billing Address</h5>
                                            <button class="btn btn-sm btn-outline-primary">Edit</button>
                                        </div>
                                        <div class="card-body">
                                            <address>
                                                <?php echo htmlspecialchars($user['name']); ?><br>
                                                123 Main Street<br>
                                                Lekki Phase 1<br>
                                                Lagos, Nigeria<br>
                                                Phone: +234 801 234 5678
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Personal Data Tab -->
                <div id="personal-tab" class="tab-content">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">My Data</h3>
                        </div>
                        <div class="card-body">
                            <form id="personal-form">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fullName" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="fullName" value="<?php echo htmlspecialchars($user['name']); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="tel" class="form-control" id="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-select" id="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male" <?php echo (isset($user['gender']) && $user['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                                            <option value="female" <?php echo (isset($user['gender']) && $user['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                                            <option value="other" <?php echo (isset($user['gender']) && $user['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Designs Tab -->
                <div id="designs-tab" class="tab-content">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">My Designs</h3>
                        </div>
                        <div class="card-body">
                            <div id="designs-container">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p class="mt-2">Loading your designs...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Delete Account Tab -->
                <div id="delete-tab" class="tab-content">
                    <div class="card">
                        <div class="card-header bg-danger">
                            <h3 class="mb-0">Delete Account</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-exclamation-triangle me-2"></i> Warning</h5>
                                <p>Deleting your account is permanent and cannot be undone. All your data, including order history, saved designs, and personal information will be permanently removed.</p>
                            </div>
                            <form id="delete-form">
                                <div class="mb-3">
                                    <label for="deleteConfirm" class="form-label">Type "DELETE" to confirm</label>
                                    <input type="text" class="form-control" id="deleteConfirm" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Enter your password</label>
                                    <input type="password" class="form-control" id="password" required>
                                </div>
                                <button type="submit" class="btn btn-danger">Delete My Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Set current year in footer
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('current-year')) {
            document.getElementById('current-year').textContent = new Date().getFullYear();
        }
        
        // Get user data
        const userData = localStorage.getItem('DRFUser');
        let user = null;
        if (userData) {
            try {
                user = JSON.parse(userData);
            } catch (e) {
                console.error('Error parsing user data:', e);
            }
        }
        
        // Tab navigation
        const tabLinks = document.querySelectorAll('[data-tab]');
        const tabContents = document.querySelectorAll('.tab-content');
        
        function showTab(tabId) {
            // Hide all tabs
            tabContents.forEach(tab => tab.classList.remove('active'));
            tabLinks.forEach(link => link.classList.remove('active'));
            
            // Show selected tab
            document.getElementById(tabId + '-tab').classList.add('active');
            document.querySelector(`[data-tab="${tabId}"]`).classList.add('active');
            
            // Load data if needed
            if (user) {
                const userId = user.user_id || user.id;
                if (tabId === 'orders') loadOrders(userId);
                if (tabId === 'wishlist') loadWishlist(userId);
                if (tabId === 'designs') loadDesigns(userId);
            }
        }
        
        // Add click event to all tab links
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const tabId = this.getAttribute('data-tab');
                showTab(tabId);
            });
        });
        
        // Also add click events to dashboard card buttons
        document.querySelectorAll('.tab-link').forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                showTab(tabId);
            });
        });
        
        // Logout button
        document.getElementById('logout-btn').addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('DRFUser');
                fetch('/auth/logout.php').finally(() => {
                    window.location.href = '/index.php';
                });
            }
        });
        
        // Personal data form
        document.getElementById('personal-form').addEventListener('submit', function(e) {
            e.preventDefault();
            if (user) {
                const userData = {
                    user_id: user.user_id || user.id,
                    name: document.getElementById('fullName').value,
                    phone: document.getElementById('phone').value,
                    gender: document.getElementById('gender').value
                };
                
                // Update in database
                fetch('/api/update_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(userData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update local storage
                        user.name = userData.name;
                        user.phone = userData.phone;
                        user.gender = userData.gender;
                        localStorage.setItem('DRFUser', JSON.stringify(user));
                        
                        // Update navbar username
                        document.querySelectorAll('.user-name').forEach(el => {
                            el.textContent = user.name;
                        });
                        
                        alert('Personal information updated successfully!');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error updating user data:', error);
                    alert('An error occurred while updating your information.');
                });
            }
        });
        
        // Delete account form
        document.getElementById('delete-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const confirmText = document.getElementById('deleteConfirm').value;
            const password = document.getElementById('password').value;
            
            if (confirmText !== 'DELETE') {
                alert('Please type DELETE to confirm account deletion');
                return;
            }
            
            if (!password) {
                alert('Please enter your password');
                return;
            }
            
            if (confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.')) {
                const userId = user.user_id || user.id;
                
                fetch('/api/delete_account.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        password: password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        localStorage.removeItem('DRFUser');
                        alert('Your account has been deleted successfully.');
                        window.location.href = '/index.php';
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error deleting account:', error);
                    alert('An error occurred while trying to delete your account.');
                });
            }
        });
        
        // API data loading functions
        function loadOrders(userId) {
            const container = document.getElementById('orders-container');
            
            fetch(`/api/orders.php?user_id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.orders && data.orders.length > 0) {
                        let html = '';
                        data.orders.forEach(order => {
                            const date = new Date(order.created_at).toLocaleDateString();
                            html += `
                                <div class="card mb-3">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Order #${order.order_id}</h5>
                                        <span class="badge bg-${getStatusBadge(order.status)}">${order.status}</span>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Date:</strong> ${date}</p>
                                        <p><strong>Total:</strong> ₦${parseFloat(order.total).toLocaleString()}</p>
                                        <button class="btn btn-sm btn-outline-primary">View Details</button>
                                    </div>
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = `
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> You have no orders yet.
                                <a href="/products.php" class="alert-link">Start shopping</a>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading orders:', error);
                    container.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> Error loading orders.
                        </div>
                    `;
                });
        }
        
        function loadWishlist(userId) {
            const container = document.getElementById('wishlist-container');
            
            fetch(`/api/wishlist.php?user_id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.items && data.items.length > 0) {
                        let html = '<div class="row">';
                        data.items.forEach(item => {
                            html += `
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <img src="${item.image}" class="card-img-top" alt="${item.product_name}">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">${item.product_name}</h5>
                                            <p class="card-text product-price">₦${parseFloat(item.price).toLocaleString()}</p>
                                            <div class="mt-auto d-flex justify-content-between">
                                                <button class="btn btn-sm btn-primary">Add to Cart</button>
                                                <button class="btn btn-sm btn-outline-danger">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = `
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> Your wishlist is empty.
                                <a href="/products.php" class="alert-link">Browse products</a>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading wishlist:', error);
                    container.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> Error loading wishlist.
                        </div>
                    `;
                });
        }
        
        function loadDesigns(userId) {
            const container = document.getElementById('designs-container');
            
            fetch(`/api/get-designs.php?user_id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.designs && data.designs.length > 0) {
                        let html = '<div class="row">';
                        data.designs.forEach(design => {
                            const designData = JSON.parse(design.design_data);
                            const date = new Date(design.created_at).toLocaleDateString();
                            html += `
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <img src="${designData.image || '/images/default.jpg'}" class="card-img-top" alt="${designData.name || 'Design'}">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">${designData.name || 'Custom Design'}</h5>
                                            <p class="text-muted small">Created on ${date}</p>
                                            <p class="mb-1">Color: ${designData.color || 'N/A'}</p>
                                            <p class="mb-1">Material: ${designData.material || 'N/A'}</p>
                                            <p class="card-text product-price">₦${(designData.price || 0).toLocaleString()}</p>
                                            <div class="mt-auto d-flex justify-content-between">
                                                <button class="btn btn-sm btn-primary">Add to Cart</button>
                                                <a href="/customize.php?design_id=${design.design_id}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = `
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> You have no saved designs.
                                <a href="/customize.php" class="alert-link">Create a custom design</a>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading designs:', error);
                    container.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> Error loading designs.
                        </div>
                    `;
                });
        }
        
        function getStatusBadge(status) {
            switch(status?.toLowerCase()) {
                case 'completed': return 'success';
                case 'processing': return 'warning';
                case 'shipped': return 'info';
                case 'cancelled': return 'danger';
                default: return 'secondary';
            }
        }
    });
    </script>
</body>
</html>