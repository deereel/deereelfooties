<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../auth/db.php';

// Get product ID from URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($productId <= 0) {
    header('Location: products.php');
    exit;
}

// Get product data
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        header('Location: products.php');
        exit;
    }
} catch (PDOException $e) {
    $error = 'Error loading product: ' . $e->getMessage();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $updateStmt = $pdo->prepare("UPDATE products SET 
            name = ?, slug = ?, price = ?, gender = ?, category = ?, type = ?, 
            colors = ?, sizes = ?, short_description = ?, description = ?, 
            features = ?, details_care = ?, main_image = ?, additional_images = ?, 
            is_featured = ?, is_new_collection = ? 
            WHERE product_id = ?");
        
        $features = !empty($_POST['features']) ? json_encode(array_filter(explode("\n", $_POST['features']))) : null;
        
        $updateStmt->execute([
            $_POST['name'], $_POST['slug'], $_POST['price'], $_POST['gender'], 
            $_POST['category'], $_POST['type'], $_POST['colors'], $_POST['sizes'], 
            $_POST['short_description'], $_POST['description'], $features, 
            $_POST['details_care'], $_POST['main_image'], $_POST['additional_images'], 
            isset($_POST['is_featured']) ? 1 : 0, isset($_POST['is_new_collection']) ? 1 : 0, 
            $productId
        ]);
        
        $success = 'Product updated successfully!';
        
        // Refresh product data
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        $error = 'Error updating product: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Admin Dashboard</title>
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
                    <h1 class="h2">Edit Product</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="products.php" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Products
                        </a>
                    </div>
                </div>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            <!-- Basic Information -->
                            <h3 class="mb-3">Basic Information</h3>
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Product Name*</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="slug" class="form-label">Slug*</label>
                                    <input type="text" class="form-control" id="slug" name="slug" value="<?php echo htmlspecialchars($product['slug'] ?? ''); ?>" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4 mb-3">
                                    <label for="price" class="form-label">Price (₦)*</label>
                                    <input type="number" class="form-control" id="price" name="price" value="<?php echo $product['price'] ?? ''; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label">Gender*</label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="men" <?php echo ($product['gender'] ?? '') === 'men' ? 'selected' : ''; ?>>Men</option>
                                        <option value="women" <?php echo ($product['gender'] ?? '') === 'women' ? 'selected' : ''; ?>>Women</option>
                                        <option value="unisex" <?php echo ($product['gender'] ?? '') === 'unisex' ? 'selected' : ''; ?>>Unisex</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="category" class="form-label">Category*</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="shoes" <?php echo ($product['category'] ?? '') === 'shoes' ? 'selected' : ''; ?>>Shoes</option>
                                        <option value="boots" <?php echo ($product['category'] ?? '') === 'boots' ? 'selected' : ''; ?>>Boots</option>
                                        <option value="slippers" <?php echo ($product['category'] ?? '') === 'slippers' ? 'selected' : ''; ?>>Slippers</option>
                                        <option value="mules" <?php echo ($product['category'] ?? '') === 'mules' ? 'selected' : ''; ?>>Mules</option>
                                        <option value="sneakers" <?php echo ($product['category'] ?? '') === 'sneakers' ? 'selected' : ''; ?>>Sneakers</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4 mb-3">
                                    <label for="type" class="form-label">Type*</label>
                                    <input type="text" class="form-control" id="type" name="type" value="<?php echo htmlspecialchars($product['type'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="colors" class="form-label">Available Colors</label>
                                    <input type="text" class="form-control" id="colors" name="colors" value="<?php echo htmlspecialchars($product['colors'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="sizes" class="form-label">Available Sizes</label>
                                    <input type="text" class="form-control" id="sizes" name="sizes" value="<?php echo htmlspecialchars($product['sizes'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <!-- Description -->
                            <h3 class="mb-3 mt-4">Description</h3>
                            <div class="mb-3">
                                <label for="short_description" class="form-label">Short Description</label>
                                <input type="text" class="form-control" id="short_description" name="short_description" value="<?php echo htmlspecialchars($product['short_description'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Full Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="features" class="form-label">Features</label>
                                <textarea class="form-control" id="features" name="features" rows="3"><?php 
                                    $features = json_decode($product['features'] ?? '[]', true);
                                    echo htmlspecialchars(is_array($features) ? implode("\n", $features) : '');
                                ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="details_care" class="form-label">Details & Care</label>
                                <textarea class="form-control" id="details_care" name="details_care" rows="5"><?php echo htmlspecialchars($product['details_care'] ?? ''); ?></textarea>
                            </div>
                            
                            <!-- Images -->
                            <h3 class="mb-3 mt-4">Images</h3>
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="main_image" class="form-label">Main Image Path*</label>
                                    <input type="text" class="form-control" id="main_image" name="main_image" value="<?php echo htmlspecialchars($product['main_image'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="additional_images" class="form-label">Additional Images</label>
                                    <input type="text" class="form-control" id="additional_images" name="additional_images" value="<?php echo htmlspecialchars($product['additional_images'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <!-- Display Options -->
                            <h3 class="mb-3 mt-4">Display Options</h3>
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" <?php echo ($product['is_featured'] ?? 0) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="is_featured">Feature on homepage</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_new_collection" name="is_new_collection" <?php echo ($product['is_new_collection'] ?? 0) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="is_new_collection">Add to New Collection</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Update Product</button>
                                <a href="products.php" class="btn btn-secondary ms-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>