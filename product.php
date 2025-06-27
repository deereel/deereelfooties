<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';


// Get product ID or slug from URL
$productId = isset($_GET['id']) ? $_GET['id'] : '';
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// If no ID or slug provided, check if the URL path contains the slug
if (empty($productId) && empty($slug)) {
    // Get the URL path
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $pathParts = explode('/', trim($path, '/'));
    
    // If the path has at least one part, use the last part as the slug
    if (count($pathParts) > 0) {
        $lastPart = end($pathParts);
        // Remove .php extension if present
        $slug = str_replace('.php', '', $lastPart);
    }
}

// If still no ID or slug, redirect to products page
if (empty($productId) && empty($slug)) {
    header("Location: /products.php");
    exit;
}

$productFound = false;
$product = null;

try {
    // Connect to database
    $pdo = new PDO("mysql:host=localhost;dbname=drf_database;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Query to get product data
    if (!empty($productId)) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->execute([$productId]);
    } else {
        // Try exact match first
        $stmt = $pdo->prepare("SELECT * FROM products WHERE slug = ?");
        $stmt->execute([$slug]);
    }
    
    $dbProduct = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($dbProduct) {
        $productFound = true;
        
        // Format product data
        $productId = $dbProduct['product_id'] ?? $dbProduct['id'] ?? 0;
        $productName = $dbProduct['name'] ?? $dbProduct['product_name'] ?? 'Product';
        $productPrice = $dbProduct['price'] ?? 0;
        $productDescription = $dbProduct['description'] ?? $dbProduct['short_description'] ?? '';
        $productFeatures = $dbProduct['features'] ?? '';
        
        // Get product images
        $mainImage = $dbProduct['main_image'] ?? $dbProduct['image'] ?? '/images/product-placeholder.jpg';
        
        $gallery = [];
        if (!empty($dbProduct['gallery'])) {
            $gallery = explode(',', $dbProduct['gallery']);
        } else if (!empty($dbProduct['additional_images'])) {
            $gallery = explode(',', $dbProduct['additional_images']);
        }
        
        // Add main image to gallery if not already included
        if (!in_array($mainImage, $gallery)) {
            array_unshift($gallery, $mainImage);
        }
        
        // Get product colors
        $colors = [];
        if (!empty($dbProduct['colors'])) {
            $colors = explode(',', $dbProduct['colors']);
        }
        
        // Get product sizes
        $sizes = [];
        if (!empty($dbProduct['sizes'])) {
            $sizes = explode(',', $dbProduct['sizes']);
        }
        
        // Get product widths
        $widths = [];
        if (!empty($dbProduct['widths'])) {
            $widths = explode(',', $dbProduct['widths']);
        }
        
        // Get product type and gender for breadcrumb
        $productType = $dbProduct['type'] ?? $dbProduct['category'] ?? 'shoes';
        $productGender = $dbProduct['gender'] ?? 'men';
    }
} catch (Exception $e) {
    // Log error
    error_log("Error fetching product: " . $e->getMessage());
    $productFound = false;
}

// Include header



 

?>
<!DOCTYPE html>
<html>
<head>
  <title><?= htmlspecialchars($productName) ?> | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>

<body class="bg-background" data-page="<?= $productType ?>">
  

  <!-- Main Content -->
  <main>
    <?php if ($productFound): ?>
      <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <div class="mb-8">
          <div class="flex items-center text-sm text-gray-500">
            <a href="/index.php">Home</a>
            <span class="mx-2">/</span>
            <a href="/<?= htmlspecialchars($productGender) ?>.php"><?= ucfirst(htmlspecialchars($productGender)) ?></a>
            <span class="mx-2">/</span>
            <span><?= htmlspecialchars($productName) ?></span>
          </div>
        </div>

        <!-- Product Details -->
        <div class="row">
          <div class="col-md-6">
            <!-- Main Product Image -->
            <div class="border mb-3">
              <img id="mainImage" src="<?= htmlspecialchars($mainImage) ?>" class="img-fluid w-100" alt="<?= htmlspecialchars($productName) ?>">
            </div>
        
            <!-- Thumbnail Gallery -->
            <?php if (!empty($gallery)): ?>
            <div class="d-flex flex-row flex-wrap gap-2">
              <?php foreach($gallery as $image): ?>
              <img src="<?= htmlspecialchars($image) ?>" class="img-thumbnail thumb" style="width: 80px; cursor: pointer;" onclick="changeImage(this)">
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
          </div>
        
          <div class="col-md-6">
            <!-- Product Info -->
            <h3 class="fw-bold"><?= htmlspecialchars($productName) ?></h3>
            <p class="text-muted"><?= htmlspecialchars($productDescription) ?></p>
            
            <?php if (!empty($productFeatures)): ?>
            <ul class="list-disc pl-5 mb-4 text-gray-600">
              <?php 
              $features = explode("\n", $productFeatures);
              foreach($features as $feature): 
                if (trim($feature) !== ''):
              ?>
              <li><?= htmlspecialchars(trim($feature)) ?></li>
              <?php 
                endif;
              endforeach; 
              ?>
            </ul>
            <?php endif; ?>
            
            <p class="text-2xl mb-4">₦<?= number_format($productPrice, 0) ?></p>

            <div class="mb-6">
              <p class="text-sm text-gray-500">Produced on order - Ships within 5 - 7 business days</p>
            </div>

            <!-- Color Selection -->
            <?php if (!empty($colors)): ?>
            <div class="mb-4">
              <h3 class="font-medium mb-2">Color</h3>
              <div class="flex space-x-2" id="color-options">
                <?php 
                $colorMap = [
                  'black' => '#000000',
                  'dark brown' => '#5c3a21',
                  'brown' => '#8b4513',
                  'tan' => '#d2b48c',
                  'navy' => '#1a2456',
                  'burgundy' => '#800020',
                  'grey' => '#808080',
                  'white' => '#ffffff'
                ];
                
                foreach($colors as $color): 
                  $colorLower = strtolower($color);
                  $bgColor = $colorMap[$colorLower] ?? '#000000';
                ?>
                <button class="color-option w-8 h-8 rounded-full" 
                        style="background-color: <?= $bgColor ?>;" 
                        data-color="<?= htmlspecialchars($color) ?>" 
                        aria-label="<?= htmlspecialchars($color) ?>"></button>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>

            <!-- Size Selection -->
            <?php if (!empty($sizes)): ?>
            <div class="mb-6">
              <div class="flex justify-between items-center mb-2">
                <h3 class="font-medium">Size</h3>
                <button class="text-sm underline" id="size-guide-btn">Size Guide</button>
              </div>
              <div class="grid grid-cols-4 gap-2" id="size-options">
                <?php foreach($sizes as $size): ?>
                <button class="border border-gray-300 py-2 hover:border-black size-option" 
                        data-size="<?= htmlspecialchars($size) ?>">
                  <?= htmlspecialchars($size) ?>
                </button>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>

            <!-- Width Selection -->
            <?php if (!empty($widths)): ?>
            <div class="mb-8">
              <h3 class="font-medium mb-2">Width</h3>
              <div class="grid grid-cols-3 gap-2" id="width-options">
                <?php foreach($widths as $width): 
                  $widthLabel = $width;
                  if ($width == 'D') $widthLabel = 'D (Standard)';
                  if ($width == 'E') $widthLabel = 'E (Wide)';
                  if ($width == 'EE') $widthLabel = 'EE (Extra Wide)';
                ?>
                <button class="border border-gray-300 py-2 hover:border-black width-option" 
                        data-width="<?= htmlspecialchars($width) ?>">
                  <?= htmlspecialchars($widthLabel) ?>
                </button>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
            
            <!-- Quantity Selector -->
            <div class="flex items-center gap-2 border border-gray-300 px-2 py-1 mb-4 w-max">
              <button class="px-3 py-1 quantity-btn" data-action="decrease">-</button>
              <input type="number" id="quantity" value="1" min="1" class="w-12 text-center focus:outline-none no-spinner">
              <button class="px-3 py-1 quantity-btn" data-action="increase">+</button>
            </div>

            <!-- Hidden Inputs -->
            <input type="hidden" id="selected-color" value="">
            <input type="hidden" id="selected-size" value="">
            <input type="hidden" id="selected-width" value="">
            <input type="hidden" id="selected-quantity" value="1">

            <!-- Add to Cart Button -->
            <div class="mb-6">
              <button class="btn btn-dark w-full" id="add-to-cart-btn">Add to Cart</button>
            </div>

            <!-- Additional Options -->
            <div class="flex flex-col sm:flex-row gap-4 mb-8">
              <button class="border border-black px-4 py-2 flex-1 hover:bg-black hover:text-white transition" id="add-to-wishlist-btn">
                ADD TO WISHLIST
              </button>
              <button class="border border-black px-4 py-2 flex-1 hover:bg-black hover:text-white transition" onclick="window.location.href='/customize.php'">
                CUSTOMIZE THIS SHOE
              </button>
            </div>

            <!-- Product Details Accordion -->
            <div class="border-t pt-6 space-y-4">
              <details class="group">
                <summary class="flex justify-between items-center cursor-pointer">
                  <span class="font-medium">Description</span>
                  <span class="transform group-open:rotate-180 transition-transform">
                    <i class="fas fa-chevron-down"></i>
                  </span>
                </summary>
                <div class="pt-4 pb-2 text-gray-600">
                  <?= $productDescription ?>
                </div>
              </details>
            </div>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="container my-5 text-center">
        <div class="alert alert-warning">
          <h2>Product Not Found</h2>
          <p>Sorry, the product "<?= htmlspecialchars($slug) ?>" does not exist or has been removed.</p>
          <a href="/products.php" class="btn btn-primary mt-3">Browse All Products</a>
        </div>
      </div>
    <?php endif; ?>
  </main>

  <?php include('components/footer.php'); ?>
  <?php include('components/account-modal.php'); ?>
  <?php include('components/scripts.php'); ?>
  
  <!-- Size Guide Modal -->
  <div id="size-guide-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Size Guide</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>EU Size</th>
                <th>UK Size</th>
                <th>US Size</th>
                <th>Foot Length (cm)</th>
              </tr>
            </thead>
            <tbody>
              <tr><td>40</td><td>6</td><td>7</td><td>25.0</td></tr>
              <tr><td>41</td><td>7</td><td>8</td><td>25.7</td></tr>
              <tr><td>42</td><td>8</td><td>9</td><td>26.3</td></tr>
              <tr><td>43</td><td>9</td><td>10</td><td>27.0</td></tr>
              <tr><td>44</td><td>10</td><td>11</td><td>27.7</td></tr>
              <tr><td>45</td><td>11</td><td>12</td><td>28.3</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    // Image gallery functionality
    function changeImage(element) {
      document.getElementById('mainImage').src = element.src;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize size guide modal
      const sizeGuideBtn = document.getElementById('size-guide-btn');
      if (sizeGuideBtn) {
        sizeGuideBtn.addEventListener('click', function(e) {
          e.preventDefault();
          const sizeGuideModal = new bootstrap.Modal(document.getElementById('size-guide-modal'));
          sizeGuideModal.show();
        });
      }
      
      // Color selection
      const colorOptions = document.querySelectorAll('.color-option');
      colorOptions.forEach(btn => {
        btn.addEventListener('click', function() {
          colorOptions.forEach(b => b.classList.remove('ring-2', 'ring-black', 'ring-offset-2'));
          btn.classList.add('ring-2', 'ring-black', 'ring-offset-2');
          document.getElementById('selected-color').value = btn.dataset.color;
        });
      });
      
      // Size selection
      const sizeOptions = document.querySelectorAll('.size-option');
      sizeOptions.forEach(btn => {
        btn.addEventListener('click', function() {
          sizeOptions.forEach(b => b.classList.remove('bg-black', 'text-white', 'border-black'));
          btn.classList.add('bg-black', 'text-white', 'border-black');
          document.getElementById('selected-size').value = btn.dataset.size;
        });
      });
      
      // Width selection
      const widthOptions = document.querySelectorAll('.width-option');
      widthOptions.forEach(btn => {
        btn.addEventListener('click', function() {
          widthOptions.forEach(b => b.classList.remove('bg-black', 'text-white', 'border-black'));
          btn.classList.add('bg-black', 'text-white', 'border-black');
          document.getElementById('selected-width').value = btn.dataset.width;
        });
      });
      
      // Quantity controls
      const quantityInput = document.getElementById('quantity');
      const decreaseBtn = document.querySelector('[data-action="decrease"]');
      const increaseBtn = document.querySelector('[data-action="increase"]');
      
      if (decreaseBtn) {
        decreaseBtn.addEventListener('click', function() {
          const currentValue = parseInt(quantityInput.value);
          if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            document.getElementById('selected-quantity').value = currentValue - 1;
          }
        });
      }
      
      if (increaseBtn) {
        increaseBtn.addEventListener('click', function() {
          const currentValue = parseInt(quantityInput.value);
          if (currentValue < 10) {
            quantityInput.value = currentValue + 1;
            document.getElementById('selected-quantity').value = currentValue + 1;
          }
        });
      }
      
      // Add to cart button
      const addToCartBtn = document.getElementById('add-to-cart-btn');
      if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
          const color = document.getElementById('selected-color').value;
          const size = document.getElementById('selected-size').value;
          const width = document.getElementById('selected-width').value;
          
          // Validate selections
          if (!color && colorOptions.length > 0) {
            alert('Please select a color');
            return;
          }
          
          if (!size && sizeOptions.length > 0) {
            alert('Please select a size');
            return;
          }
          
          if (!width && widthOptions.length > 0) {
            alert('Please select a width');
            return;
          }
          
          // Add to cart
          if (window.cartHandler) {
            const product = {
              id: '<?= $productId ?>',
              name: '<?= addslashes($productName) ?>',
              price: <?= $productPrice ?>,
              image: '<?= addslashes($mainImage) ?>',
              color: color,
              size: size,
              width: width,
              quantity: parseInt(quantityInput.value)
            };
            
            window.cartHandler.addToCart(product);
            alert('Product added to cart!');
          } else {
            alert('Cart functionality is not available');
          }
        });
      }
    });
  </script>
</body>
</html>