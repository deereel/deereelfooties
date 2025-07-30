<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>DeeReel Footies | Handcrafted Luxury Shoes for Men and Women</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>

<body data-page="index">
  <!-- Hero Slider Section -->
  <section class="relative">
    <div class="swiper hero-swiper">
      <div class="swiper-wrapper">
        <!-- Slide 1 -->
        <div class="swiper-slide">
          <div class="slide-bg" style="background-image: url('/images/hero-1.webp');"></div>
          <div class="slide-content">
            <div class="container mx-auto px-4">
              <div class="max-w-xl">
                <h1 class="text-5xl md:text-6xl font-light mb-4">Handcrafted Excellence</h1>
                <p class="text-xl mb-8">Premium footwear made with passion and precision</p>
                <div class="flex flex-wrap gap-4">
                  <a href="/men.php" class="btn-primary px-8 py-3">Shop Men</a>
                  <a href="/women.php" class="btn-outline px-8 py-3">Shop Women</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Slide 2 -->
        <div class="swiper-slide">
          <div class="slide-bg" style="background-image: url('/images/hero-2.webp');"></div>
          <div class="slide-content">
            <div class="container mx-auto px-4">
              <div class="max-w-xl ml-auto text-right">
                <h1 class="text-5xl md:text-6xl font-light mb-4">Luxury Redefined</h1>
                <p class="text-xl mb-8">Experience the perfect blend of comfort and style</p>
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

  <!-- Featured Categories -->
  <section class="py-20 bg-white">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-light mb-4">Craftsmanship Meets Style</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Explore our collections of handcrafted footwear, designed for those who appreciate quality and elegance. Each pair is meticulously crafted using traditional Goodyear welted construction by skilled Nigerian artisans with premium sustainable leather.</p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <a href="/men.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[16/9] overflow-hidden">
            <img src="/images/penny loafer 600.webp" alt="Premium Men's Handcrafted Loafers and Dress Shoes Collection" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-8">
              <div>
                <h3 class="text-3xl font-light text-white mb-2">Men's Collection</h3>
                <p class="text-white/80 mb-4">Sophisticated designs for the modern gentleman</p>
                <span class="inline-block px-4 py-2 border border-white text-white group-hover:bg-white group-hover:text-black transition">Explore Now</span>
              </div>
            </div>
          </div>
        </a>
        
        <a href="/women.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[16/9] overflow-hidden">
            <img src="/images/Oxford Cap Toe 600.webp" alt="Elegant Women's Handcrafted Oxford and Designer Shoes Collection" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end p-8">
              <div>
                <h3 class="text-3xl font-light text-white mb-2">Women's Collection</h3>
                <p class="text-white/80 mb-4">Elegant designs for the modern woman</p>
                <span class="inline-block px-4 py-2 border border-white text-white group-hover:bg-white group-hover:text-black transition">Explore Now</span>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>

  <!-- New Arrivals Section -->
  <section class="py-20 bg-background">
    <div class="container mx-auto px-4">
      <div class="flex flex-wrap items-center justify-between mb-12">
        <h2 class="text-3xl font-light">New Arrivals</h2>
        <a href="/products.php?new=1" class="text-primary hover:underline flex items-center">
          View All <i class="fas fa-arrow-right ml-2"></i>
        </a>
      </div>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
        <?php
        try {
          // Get products added in last 60 days
          $stmt = $pdo->prepare("SELECT * FROM products WHERE created_at >= DATE_SUB(NOW(), INTERVAL 60 DAY) ORDER BY created_at DESC");
          $stmt->execute();
          $recentProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          // Calculate seed for 2-day shuffling
          $daysSinceEpoch = floor(time() / (60 * 60 * 24));
          $twoDayInterval = floor($daysSinceEpoch / 2);
          mt_srand($twoDayInterval + 2000);
          
          if (count($recentProducts) >= 5) {
            shuffle($recentProducts);
            $newProducts = array_slice($recentProducts, 0, 5);
          } else {
            // Get all products to fill remaining slots
            $stmt = $pdo->prepare("SELECT * FROM products ORDER BY product_id");
            $stmt->execute();
            $allProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Remove recent products from all products to avoid duplicates
            $recentIds = array_column($recentProducts, 'product_id');
            $olderProducts = array_filter($allProducts, function($p) use ($recentIds) {
              return !in_array($p['product_id'], $recentIds);
            });
            
            shuffle($olderProducts);
            $needed = 5 - count($recentProducts);
            $fillerProducts = array_slice($olderProducts, 0, $needed);
            $newProducts = array_merge($recentProducts, $fillerProducts);
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
          <div class="group product-card" data-product-id="<?= $product['product_id'] ?? $product['id'] ?? $product['slug'] ?>" data-price="<?= $product['price'] ?>" data-name="<?= $product['name'] ?>">
            <a href="product.php?slug=<?= $product['slug'] ?>" class="block">
              <div class="relative overflow-hidden rounded-lg mb-4 shadow-sm group-hover:shadow-md transition">
                <div class="aspect-[3/4] overflow-hidden">
                  <img src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?>" 
                       class="product-main-image w-full h-full object-cover transform group-hover:scale-105 transition duration-500"
                       data-main="<?= $product['main_image'] ?>" data-hover="<?= $secondImage ?>">
                </div>
                <?php if (strtotime($product['created_at']) >= strtotime('-60 days')): ?>
                <span class="absolute top-4 left-4 bg-primary text-white text-xs px-2 py-1">NEW</span>
                <?php endif; ?>
                <!-- Wishlist Icon -->
                <button class="wishlist-icon absolute top-2 right-2 bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-all"
                        data-product-id="<?= $product['product_id'] ?? $product['id'] ?? $product['slug'] ?>"
                        data-product-name="<?= htmlspecialchars($product['name']) ?>"
                        data-price="<?= $product['price'] ?>"
                        data-image="<?= $product['main_image'] ?>">
                  <i class="far fa-heart"></i>
                </button>
              </div>
              <h3 class="font-medium text-lg"><?= $product['name'] ?></h3>
              <p class="text-accent font-semibold">₦<?= number_format($product['price']) ?></p>
            </a>
          </div>
        <?php 
            endforeach;
          } else {
            // Show placeholder if no new collection products
            for ($i = 0; $i < 4; $i++):
        ?>
          <div class="group">
            <a href="/products.php" class="block">
              <div class="relative overflow-hidden rounded-lg mb-4 shadow-sm group-hover:shadow-md transition">
                <div class="aspect-[3/4] bg-gray-200 flex items-center justify-center">
                  <span class="text-gray-400">Coming Soon</span>
                </div>
              </div>
              <h3 class="font-medium text-lg">New Product</h3>
              <p class="text-accent font-semibold">₦0</p>
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

  <!-- Our story with Large Image -->
  <section class="py-20 bg-white">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="order-2 lg:order-1">
          <h2 class="text-4xl font-light mb-6">Handcrafted Excellence</h2>
          <p class="text-gray-600 mb-8">Each pair of DeeReel Footies is meticulously crafted by skilled Nigerian artisans using premium sustainable leather and traditional Goodyear welted construction techniques passed down through generations. Our handcrafted shoes combine durability, comfort, and luxury for discerning customers seeking quality footwear.</p>
          <ul class="space-y-4 mb-8">
            <li class="flex items-start">
              <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
              <span>Premium quality leather sourced from sustainable tanneries</span>
            </li>
            <li class="flex items-start">
              <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
              <span>Goodyear welted construction for durability and easy resoling</span>
            </li>
            <li class="flex items-start">
              <i class="fas fa-check-circle text-primary mt-1 mr-3"></i>
              <span>Handcrafted by skilled artisans with decades of experience</span>
            </li>
          </ul>
          <a href="/shoemaking.php" class="btn-primary px-8 py-3">Learn More</a>
        </div>
        <!-- Replace the craftsmanship image div with this code -->
        <div class="order-1 lg:order-2 relative group">
          <img src="/images/craftsmanship.webp" alt="Craftsmanship" class="rounded-lg shadow-lg w-full">
          <div id="video-overlay" class="absolute inset-0 bg-black bg-opacity-70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
            <iframe id="craft-video" width="100%" height="100%" src="" title="Making Shoe Patterns" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="display: none;"></iframe>
          </div>
          <div id="play-button" class="absolute inset-0 flex items-center justify-center opacity-70 group-hover:opacity-100 transition-opacity duration-300 cursor-pointer">
            <i class="fas fa-play-circle text-white text-5xl hover:text-gray-200 transition"></i>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Featured Products Section -->
  <section class="py-20 bg-background">
    <div class="container mx-auto px-4">
      <div class="flex flex-wrap items-center justify-between mb-12">
        <h2 class="text-3xl font-light">Featured Products</h2>
        <a href="/products.php?featured=1" class="text-primary hover:underline flex items-center">
          View All <i class="fas fa-arrow-right ml-2"></i>
        </a>
      </div>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <?php
        try {
          // Calculate seed based on 2-day intervals
          $daysSinceEpoch = floor(time() / (60 * 60 * 24));
          $twoDayInterval = floor($daysSinceEpoch / 2);
          
          // Get all products and shuffle with consistent seed
          $stmt = $pdo->prepare("SELECT * FROM products ORDER BY product_id");
          $stmt->execute();
          $allProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          // Shuffle with seed for consistent results within 2-day period
          mt_srand($twoDayInterval);
          shuffle($allProducts);
          
          // Take first 5 as featured
          $featuredProducts = array_slice($allProducts, 0, 5);
          
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
          <div class="group product-card" data-product-id="<?= $product['product_id'] ?? $product['id'] ?? $product['slug'] ?>" data-price="<?= $product['price'] ?>" data-name="<?= $product['name'] ?>">
            <a href="product.php?slug=<?= $product['slug'] ?>" class="block">
              <div class="relative overflow-hidden rounded-lg mb-4 shadow-sm group-hover:shadow-md transition">
                <div class="aspect-[3/4] overflow-hidden">
                  <img src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?>" 
                       class="product-main-image w-full h-full object-cover transform group-hover:scale-105 transition duration-500"
                       data-main="<?= $product['main_image'] ?>" data-hover="<?= $secondImage ?>">
                </div>
                <?php if ($product['is_featured']): ?>
                <span class="absolute top-4 left-4 bg-primary text-white text-xs px-2 py-1">FEATURED</span>
                <?php endif; ?>
                <!-- Wishlist Icon -->
                <button class="wishlist-icon absolute top-2 right-2 bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-all"
                        data-product-id="<?= $product['product_id'] ?? $product['id'] ?? $product['slug'] ?>"
                        data-product-name="<?= htmlspecialchars($product['name']) ?>"
                        data-price="<?= $product['price'] ?>"
                        data-image="<?= $product['main_image'] ?>">
                  <i class="far fa-heart"></i>
                </button>
              </div>
              <h3 class="font-medium text-lg"><?= $product['name'] ?></h3>
              <p class="text-accent font-semibold">₦<?= number_format($product['price']) ?></p>
            </a>
          </div>
        <?php 
            endforeach;
          } else {
            // Show placeholder if no featured products
            for ($i = 0; $i < 5; $i++):
        ?>
          <div class="group">
            <a href="/products.php" class="block">
              <div class="relative overflow-hidden rounded-lg mb-4 shadow-sm group-hover:shadow-md transition">
                <div class="aspect-[3/4] bg-gray-200 flex items-center justify-center">
                  <span class="text-gray-400">Coming Soon</span>
                </div>
              </div>
              <h3 class="font-medium text-lg">Featured Product</h3>
              <p class="text-accent font-semibold">₦0</p>
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

  <!-- Testimonials Section -->
  <section class="py-20 bg-white">
    <div class="container mx-auto px-4">
      <h2 class="text-3xl font-light mb-12 text-center">What Our Customers Say</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-background p-8 rounded-lg shadow-sm">
          <div class="flex text-yellow-400 mb-4">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
          <p class="text-gray-600 mb-6">"The quality of these shoes is exceptional. I've never owned a pair that combines comfort and style so perfectly. Worth every penny."</p>
          <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-gray-300 mr-4"></div>
            <div>
              <h4 class="font-medium">Michael O.</h4>
              <p class="text-sm text-gray-500">Lagos, Nigeria</p>
            </div>
          </div>
        </div>
        
        <div class="bg-background p-8 rounded-lg shadow-sm">
          <div class="flex text-yellow-400 mb-4">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
          </div>
          <p class="text-gray-600 mb-6">"I ordered a custom pair for my wedding and they exceeded all expectations. The attention to detail is remarkable. Highly recommend!"</p>
          <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-gray-300 mr-4"></div>
            <div>
              <h4 class="font-medium">Sarah K.</h4>
              <p class="text-sm text-gray-500">Abuja, Nigeria</p>
            </div>
          </div>
        </div>
        
        <div class="bg-background p-8 rounded-lg shadow-sm">
          <div class="flex text-yellow-400 mb-4">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
          </div>
          <p class="text-gray-600 mb-6">"These boots are not only beautiful but incredibly durable. I've worn them almost daily for a year and they still look amazing."</p>
          <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-gray-300 mr-4"></div>
            <div>
              <h4 class="font-medium">David T.</h4>
              <p class="text-sm text-gray-500">Port Harcourt, Nigeria</p>
            </div>
          </div>          
        </div>
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
      
      // Video play functionality
      const playButton = document.getElementById('play-button');
      const videoIframe = document.getElementById('craft-video');
      const videoOverlay = document.getElementById('video-overlay');
      
      playButton.addEventListener('click', function() {
        videoIframe.src = 'https://www.youtube.com/embed/U0--UIuRE3E?autoplay=1&mute=0';
        videoIframe.style.display = 'block';
        playButton.style.display = 'none';
        videoOverlay.style.opacity = '1';
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
