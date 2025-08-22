<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../auth/db.php';

// Get products with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Search and filter functionality
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$whereClause = '';
$params = [];
$conditions = [];

if (!empty($search)) {
    $conditions[] = "(name LIKE ? OR description LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if (!empty($categoryFilter)) {
    $conditions[] = "category = ?";
    $params[] = $categoryFilter;
}

if (!empty($conditions)) {
    $whereClause = " WHERE " . implode(" AND ", $conditions);
}

try {
    // Count total products for pagination
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM products" . $whereClause);
    $countStmt->execute($params);
    $totalProducts = $countStmt->fetchColumn();
    $totalPages = ceil($totalProducts / $limit);

    // Get products with pagination
    $productStmt = $pdo->prepare("SELECT * FROM products" . $whereClause . " ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
    $productStmt->execute($params);
    $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get categories for filter
    $categoryStmt = $pdo->prepare("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category");
    $categoryStmt->execute();
    $categories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $error = 'Error retrieving products: ' . $e->getMessage();
    $products = [];
    $categories = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="admin-layout">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <main>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Products</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <span class="badge bg-primary fs-6"><?php echo $totalProducts; ?> Total Products</span>
                        </div>
                        <a href="add-product.php" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Add Product
                        </a>
                    </div>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <!-- Search and Filter Bar -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-5">
                                <input type="text" name="search" class="form-control" placeholder="Search products by name or description..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-3">
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo htmlspecialchars($category); ?>" <?php echo $category === $categoryFilter ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search me-1"></i> Search
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="products.php" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-x-circle me-1"></i> Clear
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Products Grid -->
                <div class="row">
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                                <div class="card h-100">
                                    <div class="position-relative">
                                        <?php 
                                        $imageUrl = $product['image_url'] ?? $product['images'] ?? $product['main_image'] ?? '';
                                        if (!empty($imageUrl)): 
                                        ?>
                                            <img src="<?php echo htmlspecialchars($imageUrl); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 200px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Stock Status Badge -->
                                        <?php if (isset($product['stock']) && $product['stock'] <= 0): ?>
                                            <span class="position-absolute top-0 end-0 badge bg-danger m-2">Out of Stock</span>
                                        <?php elseif (isset($product['stock']) && $product['stock'] <= 5): ?>
                                            <span class="position-absolute top-0 end-0 badge bg-warning m-2">Low Stock</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h6>
                                        
                                        <?php if (!empty($product['category'])): ?>
                                            <small class="text-muted mb-2"><?php echo htmlspecialchars($product['category']); ?></small>
                                        <?php endif; ?>
                                        
                                        <p class="card-text small text-muted flex-grow-1">
                                            <?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 100)); ?>
                                            <?php if (strlen($product['description'] ?? '') > 100): ?>...<?php endif; ?>
                                        </p>
                                        
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong class="text-primary">₦<?php echo number_format($product['price'], 2); ?></strong>
                                                <?php if (isset($product['stock'])): ?>
                                                    <small class="text-muted">Stock: <?php echo $product['stock']; ?></small>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="btn-group w-100">
                                                <a href="edit-product.php?id=<?php echo $product['product_id'] ?? $product['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(<?php echo $product['product_id'] ?? $product['id']; ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-box text-muted" style="font-size: 4rem;"></i>
                                <h4 class="mt-3 text-muted">No Products Found</h4>
                                <?php if (!empty($search) || !empty($categoryFilter)): ?>
                                    <p class="text-muted">Try adjusting your search criteria or <a href="products.php">view all products</a></p>
                                <?php else: ?>
                                    <p class="text-muted">Start by <a href="add-product.php">adding your first product</a></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($categoryFilter); ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($categoryFilter); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($categoryFilter); ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                fetch('delete-product.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: productId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting product: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the product.');
                });
            }
        }
    </script>
</body>
</html>