<?php

function renderProductPage($slug) {
    global $pdo;
    
    // Get product data
    $stmt = $pdo->prepare("SELECT * FROM products WHERE slug = ?");
    $stmt->execute([$slug]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        header("HTTP/1.0 404 Not Found");
        echo "Product not found";
        exit;
    }
    
    // Parse features
    $features = json_decode($product['features'] ?? '[]', true);
    
    // Parse additional images
    $additionalImages = array_filter(explode(',', $product['additional_images'] ?? ''));
    
    // Parse colors
    $colors = array_filter(explode(',', $product['colors'] ?? ''));
    
    // Parse sizes
    $sizes = array_filter(explode(',', $product['sizes'] ?? ''));
    
    // Include header
    include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php');
?>
<body class="bg-background" data-page="<?= $product['slug'] ?>">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

  <!-- Main Content -->
  <main>
    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <div class="mb-8">
        <div class="flex items-center text-sm text-gray-500">
          <a href="/index.php">Home</a>
          <span class="mx-2">/</span>
          <a href="/<?= $product['gender'] ?>.php"><?= ucfirst($product['gender']) ?></a>
          <span class="mx-2">/</span>
          <span><?= $product['name'] ?></span>
        </div>
      </div>

      <!-- Product Details -->
      <div class="row">
        <div class="col-md-6">
          <!-- Main Product Image -->
          <div class="border mb-3">
            <img id="mainImage" src="<?= $product['main_image'] ?>" class="img-fluid w-100" alt="<?= $product['name'] ?>">
          </div>
      
          <!-- Thumbnail Gallery -->
          <div class="d-flex flex-row flex-wrap gap-2">
            <img src="<?= $product['main_image'] ?>" class="img-thumbnail thumb" style="width: 80px; cursor: pointer;" onclick="changeImage(this)">
            <?php foreach ($additionalImages as $img): ?>
            <img src="<?= $img ?>" class="img-thumbnail thumb" style="width: 80px; cursor: pointer;" onclick="changeImage(this)">
            <?php endforeach; ?>
          </div>
        </div>
      
        <div class="col-md-6">
          <!-- Product Info -->
          <h3 class="fw-bold"><?= $product['name'] ?></h3>
          <p class="text-muted"><?= $product['short_description'] ?></p>
          <ul class="list-disc pl-5 mb-4 text-gray-600">
            <?php foreach ($features as $feature): ?>
            <li><?= $feature ?></li>
            <?php endforeach; ?>
          </ul>
          <p class="text-2xl mb-4">₦<?= number_format($product['price']) ?></p>

          <div class="mb-6">
            <div class="flex items-center mb-2">
              <div class="flex">
                <i class="fas fa-star text-yellow-500"></i>
                <i class="fas fa-star text-yellow-500"></i>
                <i class="fas fa-star text-yellow-500"></i>
                <i class="fas fa-star text-yellow-500"></i>
                <i class="fas fa-star-half-alt text-yellow-500"></i>
              </div>
              <span class="ml-2 text-sm text-gray-500">4.5 (24 reviews)</span>
            </div>
            <p class="text-sm text-gray-500">Produced on order - Ships within 5 - 7 business days</p>
          </div>

          <!-- Size Selection -->
           
          <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
              <h3 class="font-medium">Size</h3>
              <button class="text-sm underline" id="size-guide-btn">Size Guide</button>
            </div>
            <div class="flex flex-wrap gap-2">
              <?php foreach ($sizes as $size): ?>
              <div class="size-filter w-10 h-10 flex items-center justify-center border border-gray-300 rounded-full 
                          text-sm font-medium cursor-pointer transition"
                  data-size="<?= $size ?>"><?= $size ?></div>
              <?php endforeach; ?>
            </div>
          </div>
        

          <!-- FILTER BY COLOR -->
          <div class="mb-6">
            <h3 class="font-medium mb-3">FILTER BY COLOR</h3>
            <div class="flex flex-wrap gap-2">
              <?php 
              $colorMap = [
                'black' => 'black',
                'brown' => '#92400e',
                'tan' => '#d97706',
                'burgundy' => '#991b1b',
                'navy' => '#1e3a8a',
                'white' => 'white',
                'green' => 'green',
                'dark brown' => '#5c3a21'
              ];
              
              foreach ($colors as $color): 
                $colorLower = strtolower($color);
                $bgColor = isset($colorMap[$colorLower]) ? $colorMap[$colorLower] : '#000000';
              ?>
              <div class="w-6 h-6 rounded-full cursor-pointer border border-gray-300 color-filter" 
                  style="background-color: <?= $bgColor ?>;" 
                  data-color="<?= $color ?>"></div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Width Selection -->
          <div class="mb-8">
            <h3 class="font-medium mb-2">Width</h3>
            <div class="grid grid-cols-3 gap-2" id="width-options">
              <button class="border border-gray-300 py-2 hover:border-black width-option" data-width="D">D (Standard)</button>
              <button class="border border-gray-300 py-2 hover:border-black width-option" data-width="E">E (Wide)</button>
              <button class="border border-gray-300 py-2 hover:border-black width-option" data-width="EE">EE (Extra Wide)</button>
            </div>
          </div>

          <!-- Quantity Selection and Add to Cart Button -->
          <div class="mb-4">
            <div class="d-flex mb-3">
              <div class="flex border border-gray-300 w-32">
                <button class="px-4 py-2 quantity-btn" data-action="decrease">-</button>
                <input type="number" value="1" min="1" class="w-12 text-center focus:outline-none" id="quantity">
                <button class="px-4 py-2 quantity-btn" data-action="increase">+</button>
              </div>
              
              <div class="flex-grow ms-3">
                <input type="hidden" id="selected-color" value="">
                <input type="hidden" id="selected-size" value="">
                <input type="hidden" id="selected-width" value="">
                <input type="hidden" id="selected-quantity" value="1">
                <button class="btn btn-primary w-100 h-100" id="add-to-cart-btn">Add to Cart</button>
              </div>
            </div>
          </div>

          <!-- Additional Options -->
          <div class="d-flex gap-3 mb-8">
            <button class="add-to-wishlist border border-black px-4 py-2 flex-1 hover:bg-black hover:text-white transition" 
                    data-product-id="<?= $product['product_id'] ?? $product['slug'] ?>">
              <i class="far fa-heart mr-2"></i> ADD TO WISHLIST
            </button>
            <button class="border border-black px-4 py-2 flex-1 hover:bg-black hover:text-white transition" id="customize-btn">
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
                <p><?= $product['description'] ?></p>
              </div>
            </details>

            <details class="group border-t pt-4">
              <summary class="flex justify-between items-center cursor-pointer">
                <span class="font-medium">Details & Care</span>
                <span class="transform group-open:rotate-180 transition-transform">
                  <i class="fas fa-chevron-down"></i>
                </span>
              </summary>
              <div class="pt-4 pb-2 text-gray-600">
                <?php if (!empty($product['details_care'])): ?>
                  <?= $product['details_care'] ?>
                <?php else: ?>
                  <ul class="list-disc pl-5">
                    <li>Last: Inca</li>
                    <li>Construction: Goodyear welted</li>
                    <li>Upper: Premium calfskin leather</li>
                    <li>Lining: Full leather</li>
                    <li>Sole: Oak-tanned leather</li>
                    <li>Heel: Stacked leather with rubber top piece</li>
                    <li>Wipe with a clean, dry cloth</li>
                    <li>Apply quality shoe cream or polish as needed</li>
                    <li>Use shoe trees between wears (not included)</li>
                    <li>Allow 24 hours between wears</li>
                  </ul>
                <?php endif; ?>
              </div>
            </details>


            <details class="group border-t pt-4">
              <summary class="flex justify-between items-center cursor-pointer">
                <span class="font-medium">Shipping & Returns</span>
                <span class="transform group-open:rotate-180 transition-transform">
                  <i class="fas fa-chevron-down"></i>
                </span>
              </summary>
              <div class="pt-4 pb-2 text-gray-600">
                <p>Free shipping details on the cart page.</p>
                <p class="mt-2">Standard shipping: 3-5 business days (Nigeria), 5-7 business days (International)</p>
                <p class="mt-2">Express shipping: 1-2 business days (Nigeria), 2-3 business days (International)</p>
                <p class="mt-2">Returns accepted within 30 days of delivery for unworn shoes in original packaging.</p>
              </div>
            </details>
          </div>
        </div>
      </div>

      <!-- Size Guide Modal -->
      <div id="size-guide-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white p-8 max-w-3xl w-full max-h-[90vh] overflow-y-auto">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-light">Size Guide</h2>
            <button id="close-size-guide" class="text-2xl">&times;</button>
          </div>
          <div class="mb-6">
            <h3 class="font-medium mb-2">Men's Size Conversion Chart</h3>
            <div class="overflow-x-auto">
              <table class="w-full border-collapse">
                <thead>
                  <tr class="bg-gray-100">
                    <th class="border p-2 text-left">UK</th>
                    <th class="border p-2 text-left">US</th>
                    <th class="border p-2 text-left">EU</th>
                    <th class="border p-2 text-left">JP</th>
                    <th class="border p-2 text-left">Foot Length (cm)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border p-2">6</td>
                    <td class="border p-2">7</td>
                    <td class="border p-2">40</td>
                    <td class="border p-2">25</td>
                    <td class="border p-2">25.0</td>
                  </tr>
                  <tr>
                    <td class="border p-2">7</td>
                    <td class="border p-2">8</td>
                    <td class="border p-2">41</td>
                    <td class="border p-2">26</td>
                    <td class="border p-2">26.0</td>
                  </tr>
                  <tr>
                    <td class="border p-2">8</td>
                    <td class="border p-2">9</td>
                    <td class="border p-2">42</td>
                    <td class="border p-2">27</td>
                    <td class="border p-2">27.0</td>
                  </tr>
                  <tr>
                    <td class="border p-2">9</td>
                    <td class="border p-2">10</td>
                    <td class="border p-2">43</td>
                    <td class="border p-2">28</td>
                    <td class="border p-2">28.0</td>
                  </tr>
                  <tr>
                    <td class="border p-2">10</td>
                    <td class="border p-2">11</td>
                    <td class="border p-2">44</td>
                    <td class="border p-2">29</td>
                    <td class="border p-2">29.0</td>
                  </tr>
                  <tr>
                    <td class="border p-2">11</td>
                    <td class="border p-2">12</td>
                    <td class="border p-2">45</td>
                    <td class="border p-2">30</td>
                    <td class="border p-2">30.0</td>
                  </tr>
                  <tr>
                    <td class="border p-2">12</td>
                    <td class="border p-2">13</td>
                    <td class="border p-2">46</td>
                    <td class="border p-2">31</td>
                    <td class="border p-2">31.0</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

     <!-- Size Guide Modal -->
    <div id="size-guide-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
      <div class="bg-white p-8 max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-light">Size Guide</h2>
          <button id="close-size-guide" class="text-2xl">&times;</button>
        </div>
        <div class="mb-6">
          <h3 class="font-medium mb-2">Men's Size Conversion Chart</h3>
          <div class="overflow-x-auto">
            <table class="w-full border-collapse">
              <thead>
                <tr class="bg-gray-100">
                  <th class="border p-2 text-left">UK</th>
                  <th class="border p-2 text-left">US</th>
                  <th class="border p-2 text-left">EU</th>
                  <th class="border p-2 text-left">JP</th>
                  <th class="border p-2 text-left">Foot Length (cm)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="border p-2">6</td>
                  <td class="border p-2">7</td>
                  <td class="border p-2">40</td>
                  <td class="border p-2">25</td>
                  <td class="border p-2">25.0</td>
                </tr>
                <tr>
                  <td class="border p-2">7</td>
                  <td class="border p-2">8</td>
                  <td class="border p-2">41</td>
                  <td class="border p-2">26</td>
                  <td class="border p-2">26.0</td>
                </tr>
                <tr>
                  <td class="border p-2">8</td>
                  <td class="border p-2">9</td>
                  <td class="border p-2">42</td>
                  <td class="border p-2">27</td>
                  <td class="border p-2">27.0</td>
                </tr>
                <tr>
                  <td class="border p-2">9</td>
                  <td class="border p-2">10</td>
                  <td class="border p-2">43</td>
                  <td class="border p-2">28</td>
                  <td class="border p-2">28.0</td>
                </tr>
                <tr>
                  <td class="border p-2">10</td>
                  <td class="border p-2">11</td>
                  <td class="border p-2">44</td>
                  <td class="border p-2">29</td>
                  <td class="border p-2">29.0</td>
                </tr>
                <tr>
                  <td class="border p-2">11</td>
                  <td class="border p-2">12</td>
                  <td class="border p-2">45</td>
                  <td class="border p-2">30</td>
                  <td class="border p-2">30.0</td>
                </tr>
                <tr>
                  <td class="border p-2">12</td>
                  <td class="border p-2">13</td>
                  <td class="border p-2">46</td>
                  <td class="border p-2">31</td>
                  <td class="border p-2">31.0</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div>
          <h3 class="font-medium mb-2">How to Measure Your Foot</h3>
          <ol class="list-decimal pl-5 text-gray-600">
            <li class="mb-2">Stand on a piece of paper with your heel against a wall.</li>
            <li class="mb-2">Mark the longest part of your foot on the paper.</li>
            <li class="mb-2">Measure the distance from the wall to the mark in centimeters.</li>
            <li class="mb-2">Use this measurement to find your size in the chart above.</li>
            <li class="mb-2">If you're between sizes, we recommend sizing up.</li>
          </ol>
        </div>
      </div>
    </div>

    <!-- Added to Cart Modal -->
    <div id="added-to-cart-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
      <div class="bg-white p-8 max-w-md w-full">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-medium">Added to Cart</h2>
          <button id="close-cart-modal" class="text-2xl">&times;</button>
        </div>
        <div class="flex items-center mb-6">
          <div class="w-20 h-20 relative overflow-hidden mr-4">
            <img id="cart-product-image" src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?>" class="object-cover w-full h-full">
          </div>
          <div>
            <h3 class="font-medium" id="cart-product-name"><?= $product['name'] ?></h3>
            <p class="text-gray-500" id="cart-product-details"></p>
            <p id="cart-product-price">₦<?= number_format($product['price']) ?></p>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="/cart.php" class="bg-black text-white px-4 py-2 text-center flex-1 hover:bg-gray-800 transition">
            VIEW CART
          </a>
          <button id="continue-shopping" class="border border-black px-4 py-2 flex-1 hover:bg-black hover:text-white transition">
            CONTINUE SHOPPING
          </button>
        </div>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  <script src="/js/product.js"></script>
  
  <script>
    // Image change function
    function changeImage(img) {
      document.getElementById('mainImage').src = img.src;
      
      // Remove selection from all thumbnails
      document.querySelectorAll('.thumb').forEach(thumb => {
        thumb.classList.remove('selected');
      });
      
      // Add selection to clicked thumbnail
      img.classList.add('selected');
    }
  </script>

  
    <script>
    // Image change function
    function changeImage(img) {
        document.getElementById('mainImage').src = img.src;
        
        // Remove selection from all thumbnails
        document.querySelectorAll('.thumb').forEach(thumb => {
        thumb.classList.remove('selected');
        });
        
        // Add selection to clicked thumbnail
        img.classList.add('selected');
    }

    // Initialize color selection
    document.addEventListener('DOMContentLoaded', function() {
        // Color selection
        const colorOptions = document.querySelectorAll('.color-filter');
        colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selection from all options
            colorOptions.forEach(opt => opt.classList.remove('selected'));
            // Add selection to clicked option
            this.classList.add('selected');
            // Update hidden input
            document.getElementById('selected-color').value = this.dataset.color;
        });
        });
        
        // Size selection
        const sizeOptions = document.querySelectorAll('.size-filter');
        sizeOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selection from all options
            sizeOptions.forEach(opt => opt.classList.remove('selected'));
            // Add selection to clicked option
            this.classList.add('selected');
            // Update hidden input
            document.getElementById('selected-size').value = this.dataset.size;
        });
        });
        
        // Width selection
        const widthOptions = document.querySelectorAll('.width-option');
        widthOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selection from all options
            widthOptions.forEach(opt => {
            opt.classList.remove('selected');
            opt.classList.remove('bg-black');
            opt.classList.remove('text-white');
            });
            // Add selection to clicked option
            this.classList.add('selected');
            this.classList.add('bg-black');
            this.classList.add('text-white');
            // Update hidden input
            document.getElementById('selected-width').value = this.dataset.width;
        });
        });
        
        // Size guide modal
        const sizeGuideBtn = document.getElementById('size-guide-btn');
        const sizeGuideModal = document.getElementById('size-guide-modal');
        const closeSizeGuide = document.getElementById('close-size-guide');
        
        if (sizeGuideBtn && sizeGuideModal) {
        sizeGuideBtn.addEventListener('click', function(e) {
            e.preventDefault();
            sizeGuideModal.classList.remove('hidden');
        });
        }
        
        if (closeSizeGuide && sizeGuideModal) {
        closeSizeGuide.addEventListener('click', function() {
            sizeGuideModal.classList.add('hidden');
        });
        }
        
        if (sizeGuideModal) {
        sizeGuideModal.addEventListener('click', function(e) {
            if (e.target === this) {
            this.classList.add('hidden');
            }
        });
        }
    });
    </script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Size guide modal
        const sizeGuideBtn = document.getElementById('size-guide-btn');
        const sizeGuideModal = document.getElementById('size-guide-modal');
        const closeSizeGuide = document.getElementById('close-size-guide');
        
        if (sizeGuideBtn && sizeGuideModal) {
          sizeGuideBtn.addEventListener('click', function(e) {
            e.preventDefault();
            sizeGuideModal.classList.remove('hidden');
          });
        }
        
        if (closeSizeGuide && sizeGuideModal) {
          closeSizeGuide.addEventListener('click', function() {
            sizeGuideModal.classList.add('hidden');
          });
        }
        
        // Added to cart modal
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const addedToCartModal = document.getElementById('added-to-cart-modal');
        const closeCartModal = document.getElementById('close-cart-modal');
        const continueShoppingBtn = document.getElementById('continue-shopping');
        
        if (addToCartBtn && addedToCartModal) {
          addToCartBtn.addEventListener('click', function() {
            const color = document.getElementById('selected-color').value;
            const size = document.getElementById('selected-size').value;
            const width = document.getElementById('selected-width').value;
            
            if (!color || !size || !width) {
              alert('Please select color, size and width');
              return;
            }
            
            // Update cart modal details
            document.getElementById('cart-product-details').textContent = 
              `Size: ${size} | Color: ${color} | Width: ${width}`;
              
            // Show modal
            addedToCartModal.classList.remove('hidden');
          });
        }
        
        if (closeCartModal && addedToCartModal) {
          closeCartModal.addEventListener('click', function() {
            addedToCartModal.classList.add('hidden');
          });
        }
        
        if (continueShoppingBtn && addedToCartModal) {
          continueShoppingBtn.addEventListener('click', function() {
            addedToCartModal.classList.add('hidden');
          });
        }
        
        // Close modals when clicking outside
        window.addEventListener('click', function(e) {
          if (e.target === sizeGuideModal) {
            sizeGuideModal.classList.add('hidden');
          }
          if (e.target === addedToCartModal) {
            addedToCartModal.classList.add('hidden');
          }
        });
      });
    </script>
       

</body>
</html>
<?php
}
?>
