<?php
require_once 'auth/db.php';
include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php');
include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php');
?>

  <!-- Hero Slider Section -->
  <section class="relative">
    <div class="swiper hero-swiper">
      <div class="swiper-wrapper">
        <!-- Slide 1 -->
        <div class="swiper-slide">
          <div class="slide-bg" style="background-image: url('/images/hero-1.jpg');"></div>
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
          <div class="slide-bg" style="background-image: url('/images/hero-2.jpg');"></div>
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
        <p class="text-gray-600 max-w-2xl mx-auto">Explore our collections of handcrafted footwear, designed for those who appreciate quality and elegance.</p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <a href="/men.php" class="group relative overflow-hidden rounded-lg shadow-lg">
          <div class="aspect-[16/9] overflow-hidden">
            <img src="/images/penny loafer 600.webp" alt="Men's Collection" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
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
            <img src="/images/Oxford Cap Toe 600.webp" alt="Women's Collection" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
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
        <a href="/products.php" class="text-primary hover:underline flex items-center">
          View All <i class="fas fa-arrow-right ml-2"></i>
        </a>
      </div>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php
        try {
          // Get new collection products
          $stmt = $pdo->prepare("SELECT * FROM products WHERE is_new_collection = 1 ORDER BY created_at DESC LIMIT 4");
          $stmt->execute();
          $newProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          if (count($newProducts) > 0) {
            foreach ($newProducts as $product):
        ?>
          <div class="group">
            <a href="product.php?slug=<?= $product['slug'] ?>" class="block">
              <div class="relative overflow-hidden rounded-lg mb-4 shadow-sm group-hover:shadow-md transition">
                <div class="aspect-[3/4] overflow-hidden">
                  <img src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?>" 
                       class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                </div>
                <?php if ($product['is_new_collection']): ?>
                <span class="absolute top-4 left-4 bg-primary text-white text-xs px-2 py-1">NEW</span>
                <?php endif; ?>
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

  <!-- Featured Collection with Large Image -->
  <section class="py-20 bg-white">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="order-2 lg:order-1">
          <h2 class="text-4xl font-light mb-6">Handcrafted Excellence</h2>
          <p class="text-gray-600 mb-8">Each pair of DeeReel Footies is meticulously crafted by skilled artisans using premium materials and traditional techniques passed down through generations.</p>
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
          <a href="/about.php" class="btn-primary px-8 py-3">Our Story</a>
        </div>
        <!-- Replace the craftsmanship image div with this code -->
        <div class="order-1 lg:order-2 relative group">
          <img src="/images/craftsmanship.jpg" alt="Craftsmanship" class="rounded-lg shadow-lg w-full">
          <div class="absolute inset-0 bg-black bg-opacity-70 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/U0--UIuRE3E?autoplay=1" title="Making Shoe Patterns" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
          </div>
          <div class="absolute inset-0 flex items-center justify-center opacity-70 group-hover:opacity-0 transition-opacity duration-300">
            <i class="fas fa-play-circle text-white text-5xl"></i>
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
      
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php
        try {
          // Get featured products
          $stmt = $pdo->prepare("SELECT * FROM products WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 4");
          $stmt->execute();
          $featuredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          if (count($featuredProducts) > 0) {
            foreach ($featuredProducts as $product):
        ?>
          <div class="group">
            <a href="product.php?slug=<?= $product['slug'] ?>" class="block">
              <div class="relative overflow-hidden rounded-lg mb-4 shadow-sm group-hover:shadow-md transition">
                <div class="aspect-[3/4] overflow-hidden">
                  <img src="<?= $product['main_image'] ?>" alt="<?= $product['name'] ?>" 
                       class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                </div>
                <?php if ($product['is_featured']): ?>
                <span class="absolute top-4 right-4 bg-primary text-white text-xs px-2 py-1">FEATURED</span>
                <?php endif; ?>
              </div>
              <h3 class="font-medium text-lg"><?= $product['name'] ?></h3>
              <p class="text-accent font-semibold">₦<?= number_format($product['price']) ?></p>
            </a>
          </div>
        <?php 
            endforeach;
          } else {
            // Show placeholder if no featured products
            for ($i = 0; $i < 4; $i++):
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

  <!-- Newsletter Section -->
  <section class="py-20 bg-primary text-white">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-3xl font-light mb-4">Join Our Newsletter</h2>
      <p class="max-w-2xl mx-auto mb-8">Subscribe to receive updates on new collections, exclusive offers, and styling tips.</p>
      <form class="max-w-md mx-auto flex flex-wrap">
        <input type="email" placeholder="Your email address" class="flex-1 min-w-[200px] px-4 py-3 rounded-l text-black">
        <button type="submit" class="bg-secondary text-primary px-6 py-3 rounded-r hover:bg-secondary-dark transition">Subscribe</button>
      </form>
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
    });
  </script>

</body>
</html>