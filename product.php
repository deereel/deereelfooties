<?php
require_once 'auth/db.php';
require_once 'components/product-template.php';

// Get slug from URL
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if (empty($slug)) {
    // Redirect to products page if no slug provided
    header("Location: /products.php");
    exit;
}

// Render the product page
renderProductPage($slug);
?>
