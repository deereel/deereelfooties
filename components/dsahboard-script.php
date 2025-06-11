<script>
    // Set current year in footer
    document.addEventListener('DOMContentLoaded', function() {               
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