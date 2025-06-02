<?php
require_once 'auth/db.php';
include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php');
?>

<body data-page="women">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

  <!-- Hero Section -->
  <section class="relative">
    <div class="swiper hero-swiper">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <div class="slide-bg" style="background-image: url('images/Oxford Cap Toe 600.webp');"></div>
          <div class="slide-content">
            <div class="container mx-auto px-4">
              <div class="max-w-xl">
                <h1 class="text-5xl md:text-6xl font-light mb-4">Women's Collection</h1>
                <p class="text-xl mb-8">Elegant handcrafted footwear for the modern woman.</p>
                <a href="#categories" class="btn-primary px-8 py-3 bg-white text-black hover:bg-gray-100 transition">Explore Now</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Categories Section -->
  <section id="categories" class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-light mb-12 text-center">Shop by Category</h2>
      
      <!-- Category Grid - Using existing product images -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <!-- Shoes Category -->
        <a href="/products/women/women-shoes.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/Oxford Cap Toe 600.webp" 
                 alt="Women's Shoes" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Shoes</h3>
                <p class="text-white/80 text-sm mb-2">Elegant & Professional</p>
                <span class="inline-block px-3 py-1 border border-white text-white group-hover:bg-white group-hover:text-black transition text-xs">
                  Shop Now
                </span>
              </div>
            </div>
          </div>
        </a>

        <!-- Boots Category -->
        <a href="/products/women/women-boots.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/penny loafer 600.webp" 
                 alt="Women's Boots" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Boots</h3>
                <p class="text-white/80 text-sm mb-2">Style & Comfort</p>
                <span class="inline-block px-3 py-1 border border-white text-white group-hover:bg-white group-hover:text-black transition text-xs">
                  Shop Now
                </span>
              </div>
            </div>
          </div>
        </a>

        <!-- Mules Category -->
        <a href="/products/women/women-mules.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/cram solid oxford.webp" 
                 alt="Women's Mules" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Mules</h3>
                <p class="text-white/80 text-sm mb-2">Easy Elegance</p>
                <span class="inline-block px-3 py-1 border border-white text-white group-hover:bg-white group-hover:text-black transition text-xs">
                  Shop Now
                </span>
              </div>
            </div>
          </div>
        </a>

        <!-- Slippers Category -->
        <a href="/products/women/women-slippers.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/penny loafer 600.webp" 
                 alt="Women's Slippers" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Slippers</h3>
                <p class="text-white/80 text-sm mb-2">Comfort & Style</p>
                <span class="inline-block px-3 py-1 border border-white text-white group-hover:bg-white group-hover:text-black transition text-xs">
                  Shop Now
                </span>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>

  <!-- Featured Products Section -->
  <section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-light mb-12 text-center">Featured Products</h2>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
  <?php
  try {
    // Get featured women's products
    $stmt = $pdo->prepare("SELECT * FROM products WHERE gender = 'women' AND is_featured = 1 ORDER BY created_at DESC LIMIT 4");
    $stmt->execute();
    $featuredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($featuredProducts) > 0) {
      foreach ($featuredProducts as $product):
  ?>
    <div class="group hover-accent">
      <!-- Add wishlist button inside each product card -->
      <div class="absolute top-2 right-2 z-10">
        <button class="add-to-wishlist-icon bg-white rounded-full p-2 shadow-sm hover:shadow-md transition" 
                data-product-id="<?= $product['product_id'] ?? $product['slug'] ?>"
                data-product-name="<?= $product['name'] ?>"
                data-product-price="<?= $product['price'] ?>"
                data-product-image="<?= $product['main_image'] ?>">
          <i class="far fa-heart text-gray-600 hover:text-red-500"></i>
        </button>
      </div>
      <a href="product.php?slug=<?= $product['slug'] ?>">
        <div class="relative aspect-[3/4] overflow-hidden mb-4">
          <img src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?>" 
               class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
        </div>
        <h3 class="text-lg"><?= $product['name'] ?></h3>
        <p class="text-gray-500">₦<?= number_format($product['price']) ?></p>
      </a>
    </div>
  <?php 
      endforeach;
    } else {
      // Show placeholder if no featured products
      for ($i = 0; $i < 4; $i++):
  ?>
    <div class="group hover-accent">
      <a href="/products/women/women-shoes.php">
        <div class="relative aspect-[3/4] overflow-hidden mb-4 bg-gray-200">
          <div class="absolute inset-0 flex items-center justify-center">
            <span class="text-gray-400">No image</span>
          </div>
        </div>
        <h3 class="text-lg">Sample Product</h3>
        <p class="text-gray-500">₦0</p>
      </a>
    </div>
  <?php 
      endfor;
    }
  } catch (PDOException $e) {
    echo '<div class="col-span-full text-center py-8">Error loading products</div>';
  }
  ?>
</div>
      
      <div class="text-center mt-12">
        <a href="/products.php?gender=women" class="btn-primary px-8 py-3">View All Women's Products</a>
      </div>
    </div>
  </section>

  <!-- New Arrivals Section -->
  <section class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-light mb-12 text-center">New Arrivals</h2>      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php
        try {
          // Get new collection women's products
          $stmt = $pdo->prepare("SELECT * FROM products WHERE gender = 'women' AND is_new_collection = 1 ORDER BY created_at DESC LIMIT 4");
          $stmt->execute();
          $newProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          if (count($newProducts) > 0) {
            foreach ($newProducts as $product):
        ?>
          <div class="group hover-accent">
            <!-- Add wishlist button inside each product card -->
            <div class="absolute top-2 right-2 z-10">
              <button class="add-to-wishlist-icon bg-white rounded-full p-2 shadow-sm hover:shadow-md transition" 
                      data-product-id="<?= $product['product_id'] ?? $product['slug'] ?>"
                      data-product-name="<?= $product['name'] ?>"
                      data-product-price="<?= $product['price'] ?>"
                      data-product-image="<?= $product['main_image'] ?>">
                <i class="far fa-heart text-gray-600 hover:text-red-500"></i>
              </button>
            </div>
            <a href="product.php?slug=<?= $product['slug'] ?>">
              <div class="relative aspect-[3/4] overflow-hidden mb-4">
                <img src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?>" 
                     class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
              </div>
              <h3 class="text-lg"><?= $product['name'] ?></h3>
              <p class="text-gray-500">₦<?= number_format($product['price']) ?></p>
            </a>
          </div>
        <?php 
            endforeach;
          } else {
            // Show placeholder if no new collection products
            for ($i = 0; $i < 4; $i++):
        ?>
          <div class="group hover-accent">
            <a href="/products/women/women-shoes.php">
              <div class="relative aspect-[3/4] overflow-hidden mb-4 bg-gray-200">
                <div class="absolute inset-0 flex items-center justify-center">
                  <span class="text-gray-400">No image</span>
                </div>
              </div>
              <h3 class="text-lg">Sample Product</h3>
              <p class="text-gray-500">₦0</p>
            </a>
          </div>
        <?php 
            endfor;
          }
        } catch (PDOException $e) {
          echo '<div class="col-span-full text-center py-8">Error loading products</div>';
        }
        ?>
      </div>
    </div>
  </section>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>

  <!-- Scroll to Top Button -->
  <a href="#" class="btn btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>


  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/wishlist-modal.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/cart-modal.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
<<<<<<< HEAD
=======



>>>>>>> parent of f36b17c (checkout page)
  
</body>
</html>
