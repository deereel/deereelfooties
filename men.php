<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Men Footwears | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>
<body data-page="men">
  

  <!-- Hero Section -->
  <section class="relative">
    <div class="swiper hero-swiper">
      <div class="swiper-wrapper">
        <!-- Slide 1 -->
        <div class="swiper-slide">
          <div class="slide-bg" style="background-image: url('/images/men-hero.webp');"></div>
          <div class="slide-content">
            <div class="container mx-auto px-4">
              <div class="max-w-xl">
                <h1 class="text-5xl md:text-6xl font-light mb-4">Built for Leaders</h1>
                <p class="text-xl mb-8">Handcrafted footwear that commands respect and exudes confidence.</p>
                <a href="#categories" class="btn-primary px-8 py-3">Shop Men's</a>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Slide 2 -->
        <div class="swiper-slide">
          <div class="slide-bg" style="background-image: url('/images/men-hero1.webp');"></div>
          <div class="slide-content">
            <div class="container mx-auto px-4">
              <div class="max-w-xl ml-auto text-right">
                <h1 class="text-5xl md:text-6xl font-light mb-4">Power Moves</h1>
                <p class="text-xl mb-8">Every step tells your story of success and determination.</p>
                <div class="flex flex-wrap gap-4 justify-end">
                  <a href="/customize.php" class="btn-primary px-8 py-3">Customize Now</a>
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
        <a href="/products/men/men-shoes.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/category-men-shoes.webp" 
                 alt="Men's Shoes" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Shoes</h3>
                <p class="text-white/80 text-sm mb-2">Formal & Professional</p>
                <span class="inline-block px-3 py-1 border border-white text-white hover:bg-white hover:text-black transition text-xs">
                  Shop Now
                </span>
              </div>
            </div>
          </div>
        </a>

        <!-- Boots Category -->
        <a href="/products/men/men-boots.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/category-men-boots.webp" 
                 alt="Men's Boots" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Boots</h3>
                <p class="text-white/80 text-sm mb-2">Style & Durability</p>
                <span class="inline-block px-3 py-1 border border-white text-white hover:bg-white hover:text-black transition text-xs">
                  Shop Now
                </span>
              </div>
            </div>
          </div>
        </a>

        <!-- Mules Category -->
        <a href="/products/men/men-mules.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/category-men-mules.webp" 
                 alt="Men's Mules" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Mules</h3>
                <p class="text-white/80 text-sm mb-2">Easy Slip-On Style</p>
                <span class="inline-block px-3 py-1 border border-white text-white hover:bg-white hover:text-black transition text-xs">
                  Shop Now
                </span>
              </div>
            </div>
          </div>
        </a>

        <!-- Slippers Category -->
        <a href="/products/men/men-slippers.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/category-men-slippers.webp" 
                 alt="Men's Slippers" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Slippers</h3>
                <p class="text-white/80 text-sm mb-2">Comfort & Casual</p>
                <span class="inline-block px-3 py-1 border border-white text-white hover:bg-white hover:text-black transition text-xs">
                  Shop Now
                </span>
              </div>
            </div>
          </div>
        </a>

        <!-- Sneakers Category -->
        <a href="/products/men/men-sneakers.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[4/3] overflow-hidden">
            <img src="/images/category-men-sneakers.webp" 
                 alt="Men's Sneakers" 
                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-4">
              <div>
                <h3 class="text-xl font-light text-white mb-1">Sneakers</h3>
                <p class="text-white/80 text-sm mb-2">Sport & Casual</p>
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
          
          // Get all men's products and unisex products, shuffle with consistent seed
          $stmt = $pdo->prepare("SELECT * FROM products WHERE gender IN ('men', 'unisex') ORDER BY product_id");
          $stmt->execute();
          $allMenProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          // Shuffle with seed for consistent results within 2-day period (different from new arrivals)
          mt_srand($twoDayInterval + 500);
          shuffle($allMenProducts);
          
          // Take first 15 as featured
          $featuredProducts = array_slice($allMenProducts, 0, 15);
          
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
            <a href="/products/men/men-shoes.php">
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
        <a href="/products.php?gender=men" class="btn-primary px-8 py-3">View All Men's Products</a>
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
          // Get men's and unisex products added in last 60 days
          $stmt = $pdo->prepare("SELECT * FROM products WHERE gender IN ('men', 'unisex') AND created_at >= DATE_SUB(NOW(), INTERVAL 60 DAY) ORDER BY created_at DESC");
          $stmt->execute();
          $recentMenProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          // Calculate seed for 2-day shuffling
          $daysSinceEpoch = floor(time() / (60 * 60 * 24));
          $twoDayInterval = floor($daysSinceEpoch / 2);
          mt_srand($twoDayInterval + 1000);
          
          if (count($recentMenProducts) >= 10) {
            shuffle($recentMenProducts);
            $newProducts = array_slice($recentMenProducts, 0, 10);
          } else {
            // Get all men's products to fill remaining slots
            $stmt = $pdo->prepare("SELECT * FROM products WHERE gender IN ('men', 'unisex') ORDER BY product_id");
            $stmt->execute();
            $allMenProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Remove recent products from all products to avoid duplicates
            $recentIds = array_column($recentMenProducts, 'product_id');
            $olderMenProducts = array_filter($allMenProducts, function($p) use ($recentIds) {
              return !in_array($p['product_id'], $recentIds);
            });
            
            shuffle($olderMenProducts);
            $needed = 10 - count($recentMenProducts);
            $fillerProducts = array_slice($olderMenProducts, 0, $needed);
            $newProducts = array_merge($recentMenProducts, $fillerProducts);
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
            <a href="/products/men/men-shoes.php">
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
        <a href="/products.php?gender=men&new=1" class="btn-primary px-8 py-3">View All New Arrivals</a>
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