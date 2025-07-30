<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Women Footwears | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>
<body data-page="women">
  

  <!-- Hero Section -->
  <section class="relative">
    <div class="swiper hero-swiper">
      <div class="swiper-wrapper">
        <!-- Slide 1 -->
        <div class="swiper-slide">
          <div class="slide-bg" style="background-image: url('/images/women-hero.webp');"></div>
          <div class="slide-content">
            <div class="container mx-auto px-4">
              <div class="max-w-xl">
                <h1 class="text-5xl md:text-6xl font-light mb-4">Elegance Redefined</h1>
                <p class="text-xl mb-8">Sophisticated footwear crafted for the woman who leads with grace.</p>
                <a href="#categories" class="btn-primary px-8 py-3">Shop Women's</a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Slide 2 -->
        <div class="swiper-slide">
          <div class="slide-bg" style="background-image: url('/images/women-hero1.webp');"></div>
          <div class="slide-content">
            <div class="container mx-auto px-4">
              <div class="max-w-xl ml-auto text-right">
                <h1 class="text-5xl md:text-6xl font-light mb-4">Unstoppable Style</h1>
                <p class="text-xl mb-8">Where timeless beauty meets modern confidence in every stride.</p>
                <div class="flex flex-wrap gap-4 justify-end">
                  <a href="/customize.php" class="btn-primary px-8 py-3">Create Yours</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </section>

  <!-- Categories Section -->
  <section id="categories" class="py-16 bg-white">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-light mb-12 text-center">Shop by Category</h2>
      
      <!-- Category Grid - 5 Categories -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
        <!-- Shoes Category -->
        <a href="/products/women/women-shoes.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/category-women-shoes.webp" 
                 alt="Women's Shoes" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Shoes</h3>
                <p class="text-white/80 text-sm mb-2">Elegant & Stylish</p>
                <span class="inline-block px-3 py-1 border border-white text-white hover:bg-white hover:text-black transition text-xs">
                  Shop Now
                </span>
              </div>
            </div>
          </div>
        </a>

        <!-- Boots Category -->
        <a href="/products/women/women-boots.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/category-women-boots.webp" 
                 alt="Women's Boots" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Boots</h3>
                <p class="text-white/80 text-sm mb-2">Chic & Comfortable</p>
                <span class="inline-block px-3 py-1 border border-white text-white hover:bg-white hover:text-black transition text-xs">
                  Shop Now
                </span>
              </div>
            </div>
          </div>
        </a>

        <!-- Mules Category -->
        <a href="/products/women/women-mules.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/category-women-mules.webp" 
                 alt="Women's Mules" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Mules</h3>
                <p class="text-white/80 text-sm mb-2">Effortless Style</p>
                <span class="inline-block px-3 py-1 border border-white text-white hover:bg-white hover:text-black transition text-xs">
                  Shop Now
                </span>
              </div>
            </div>
          </div>
        </a>

        <!-- Slippers Category -->
        <a href="/products/women/women-slippers.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/category-women-slippers.webp" 
                 alt="Women's Slippers" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Slippers</h3>
                <p class="text-white/80 text-sm mb-2">Luxurious Comfort</p>
                <span class="inline-block px-3 py-1 border border-white text-white hover:bg-white hover:text-black transition text-xs">
                  Shop Now
                </span>
              </div>
            </div>
          </div>
        </a>

        <!-- Sneakers Category -->
        <a href="/products/women/women-sneakers.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/category-women-sneakers.webp" 
                 alt="Women's Sneakers" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Sneakers</h3>
                <p class="text-white/80 text-sm mb-2">Active & Trendy</p>
                <span class="inline-block px-3 py-1 border border-white text-white hover:bg-white hover:text-black transition text-xs">
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
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <?php
        try {
          // Calculate seed based on 2-day intervals
          $daysSinceEpoch = floor(time() / (60 * 60 * 24));
          $twoDayInterval = floor($daysSinceEpoch / 2);
          
          // Get all women's products and unisex products, shuffle with consistent seed
          $stmt = $pdo->prepare("SELECT * FROM products WHERE gender IN ('women', 'unisex') ORDER BY product_id");
          $stmt->execute();
          $allWomenProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          // Shuffle with seed for consistent results within 2-day period (different from new arrivals)
          mt_srand($twoDayInterval + 1000); // Different seed from men's and new arrivals
          shuffle($allWomenProducts);
          
          // Take first 15 as featured
          $featuredProducts = array_slice($allWomenProducts, 0, 15);
          
          if (count($featuredProducts) > 0) {
            foreach ($featuredProducts as $product):
              // Get second image from gallery
              $gallery = [];
              if (!empty($product['gallery'])) {
                $gallery = explode(',', $product['gallery']);
              } elseif (!empty($product['additional_images'])) {
                $gallery = explode(',', $product['additional_images']);
              }
              $secondImage = $product['main_image'];
              foreach($gallery as $img) {
                $img = trim($img);
                if ($img !== $product['main_image']) {
                  $secondImage = $img;
                  break;
                }
              }
        ?>
          <div class="group hover-accent product-card" data-product-id="<?= $product['product_id'] ?? $product['id'] ?? $product['slug'] ?>" data-price="<?= $product['price'] ?>" data-name="<?= $product['name'] ?>">
            <div class="relative">
              <a href="product.php?slug=<?= $product['slug'] ?>">
                <div class="relative aspect-[3/4] overflow-hidden mb-4">
                  <img src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?>" 
                       class="product-main-image object-cover w-full h-full group-hover:scale-105 transition duration-500"
                       data-main="<?= $product['main_image'] ?>" data-hover="<?= $secondImage ?>">
                </div>
                <h3 class="text-lg"><?= $product['name'] ?></h3>
                <p class="text-gray-500">₦<?= number_format($product['price']) ?></p>
              </a>
              <!-- Wishlist Icon -->
              <button class="wishlist-icon absolute top-2 right-2 bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-all"
                      data-product-id="<?= $product['product_id'] ?? $product['id'] ?? $product['slug'] ?>"
                      data-product-name="<?= htmlspecialchars($product['name']) ?>"
                      data-price="<?= $product['price'] ?>"
                      data-image="<?= $product['main_image'] ?>">
                <i class="far fa-heart"></i>
              </button>
            </div>
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
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <?php
        try {
          // Get women's and unisex products added in last 60 days
          $stmt = $pdo->prepare("SELECT * FROM products WHERE gender IN ('women', 'unisex') AND created_at >= DATE_SUB(NOW(), INTERVAL 60 DAY) ORDER BY created_at DESC");
          $stmt->execute();
          $recentWomenProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          // Calculate seed for 2-day shuffling
          $daysSinceEpoch = floor(time() / (60 * 60 * 24));
          $twoDayInterval = floor($daysSinceEpoch / 2);
          mt_srand($twoDayInterval + 1500);
          
          if (count($recentWomenProducts) >= 10) {
            shuffle($recentWomenProducts);
            $newProducts = array_slice($recentWomenProducts, 0, 10);
          } else {
            // Get all women's products to fill remaining slots
            $stmt = $pdo->prepare("SELECT * FROM products WHERE gender IN ('women', 'unisex') ORDER BY product_id");
            $stmt->execute();
            $allWomenProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Remove recent products from all products to avoid duplicates
            $recentIds = array_column($recentWomenProducts, 'product_id');
            $olderWomenProducts = array_filter($allWomenProducts, function($p) use ($recentIds) {
              return !in_array($p['product_id'], $recentIds);
            });
            
            shuffle($olderWomenProducts);
            $needed = 10 - count($recentWomenProducts);
            $fillerProducts = array_slice($olderWomenProducts, 0, $needed);
            $newProducts = array_merge($recentWomenProducts, $fillerProducts);
          }
          
          if (count($newProducts) > 0) {
            foreach ($newProducts as $product):
              // Get second image from gallery
              $gallery = [];
              if (!empty($product['gallery'])) {
                $gallery = explode(',', $product['gallery']);
              } elseif (!empty($product['additional_images'])) {
                $gallery = explode(',', $product['additional_images']);
              }
              $secondImage = $product['main_image'];
              foreach($gallery as $img) {
                $img = trim($img);
                if ($img !== $product['main_image']) {
                  $secondImage = $img;
                  break;
                }
              }
        ?>
          <div class="group hover-accent product-card" data-product-id="<?= $product['product_id'] ?? $product['id'] ?? $product['slug'] ?>" data-price="<?= $product['price'] ?>" data-name="<?= $product['name'] ?>">
            <div class="relative">
              <a href="product.php?slug=<?= $product['slug'] ?>">
                <div class="relative aspect-[3/4] overflow-hidden mb-4">
                  <img src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?>" 
                       class="product-main-image object-cover w-full h-full group-hover:scale-105 transition duration-500"
                       data-main="<?= $product['main_image'] ?>" data-hover="<?= $secondImage ?>">
                  <?php if (strtotime($product['created_at']) >= strtotime('-60 days')): ?>
                  <span class="absolute top-2 left-2 bg-black text-white text-xs px-2 py-1">NEW</span>
                  <?php endif; ?>
                </div>
                <h3 class="text-lg"><?= $product['name'] ?></h3>
                <p class="text-gray-500">₦<?= number_format($product['price']) ?></p>
              </a>
              <!-- Wishlist Icon -->
              <button class="wishlist-icon absolute top-2 right-2 bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-all"
                      data-product-id="<?= $product['product_id'] ?? $product['id'] ?? $product['slug'] ?>"
                      data-product-name="<?= htmlspecialchars($product['name']) ?>"
                      data-price="<?= $product['price'] ?>"
                      data-image="<?= $product['main_image'] ?>">
                <i class="far fa-heart"></i>
              </button>
            </div>
          </div>
        <?php 
            endforeach;
          } else {
            // Show placeholder if no new products
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
        <a href="/products.php?gender=women&new=1" class="btn-primary px-8 py-3">View All New Arrivals</a>
      </div>
    </div>
  </section>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  
  <!-- Swiper JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <link rel="stylesheet" href="/css/slider.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const swiper = new Swiper('.hero-swiper', {
        loop: true,
        autoplay: {
          delay: 5000,
          disableOnInteraction: false,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
      });
      
      // Product image hover functionality
      const productCards = document.querySelectorAll('.product-card');
      productCards.forEach(card => {
        const img = card.querySelector('.product-main-image');
        if (!img) return;
        
        const mainSrc = img.dataset.main;
        const hoverSrc = img.dataset.hover;
        
        if (hoverSrc && hoverSrc !== mainSrc) {
          card.addEventListener('mouseenter', () => {
            img.src = hoverSrc;
          });
          
          card.addEventListener('mouseleave', () => {
            img.src = mainSrc;
          });
        }
      });
    });
  </script>
</body>
</html>