<?php
session_start();

if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../auth/db.php';
require_once '../middleware/PermissionMiddleware.php';

$permissionMiddleware = new PermissionMiddleware('manage_api');
$permissionMiddleware->handle();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - DRF Admin</title>
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
                    <h1 class="h2">API Documentation</h1>
                </div>

                <!-- Authentication -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Authentication</h5>
                    </div>
                    <div class="card-body">
                        <p>All API requests require authentication using an API key in the header:</p>
                        <pre><code>Authorization: Bearer YOUR_API_KEY</code></pre>
                    </div>
                </div>

                <!-- Orders API -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Orders API</h5>
                    </div>
                    <div class="card-body">
                        <h6>GET /api/orders</h6>
                        <p>Retrieve all orders</p>
                        <pre><code>curl -H "Authorization: Bearer YOUR_API_KEY" <?php echo $_SERVER['HTTP_HOST']; ?>/api/orders</code></pre>
                        
                        <h6 class="mt-3">GET /api/orders/{id}</h6>
                        <p>Retrieve specific order</p>
                        <pre><code>curl -H "Authorization: Bearer YOUR_API_KEY" <?php echo $_SERVER['HTTP_HOST']; ?>/api/orders/123</code></pre>
                        
                        <h6 class="mt-3">POST /api/orders</h6>
                        <p>Create new order</p>
                        <pre><code>curl -X POST -H "Authorization: Bearer YOUR_API_KEY" \
-H "Content-Type: application/json" \
-d '{"customer_name":"John Doe","items":[{"product_id":"1","quantity":2}]}' \
<?php echo $_SERVER['HTTP_HOST']; ?>/api/orders</code></pre>
                    </div>
                </div>

                <!-- Products API -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Products API</h5>
                    </div>
                    <div class="card-body">
                        <h6>GET /api/products</h6>
                        <p>Retrieve all products</p>
                        <pre><code>curl -H "Authorization: Bearer YOUR_API_KEY" <?php echo $_SERVER['HTTP_HOST']; ?>/api/products</code></pre>
                        
                        <h6 class="mt-3">GET /api/products/{id}</h6>
                        <p>Retrieve specific product</p>
                        <pre><code>curl -H "Authorization: Bearer YOUR_API_KEY" <?php echo $_SERVER['HTTP_HOST']; ?>/api/products/123</code></pre>
                    </div>
                </div>

                <!-- Response Format -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Response Format</h5>
                    </div>
                    <div class="card-body">
                        <p>All API responses follow this format:</p>
                        <pre><code>{
  "success": true,
  "data": {...},
  "message": "Success message",
  "timestamp": "2024-01-01T00:00:00Z"
}</code></pre>
                        
                        <h6 class="mt-3">Error Response</h6>
                        <pre><code>{
  "success": false,
  "error": "Error message",
  "code": 400,
  "timestamp": "2024-01-01T00:00:00Z"
}</code></pre>
                    </div>
                </div>

                <!-- Rate Limits -->
                <div class="card">
                    <div class="card-header">
                        <h5>Rate Limits</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            <li>1000 requests per hour per API key</li>
                            <li>Rate limit headers included in response</li>
                            <li>HTTP 429 returned when limit exceeded</li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>