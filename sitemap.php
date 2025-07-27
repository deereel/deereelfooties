<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sitemap | DeeReel Footies</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>
<body class="bg-background" data-page="sitemap">

  <!-- Main Content -->
  <main>
    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <div class="mb-8">
        <h1 class="text-3xl font-light mb-2">Sitemap</h1>
        <div class="flex items-center text-sm text-gray-500">
          <a href="/index.php">Home</a>
          <span class="mx-2">/</span>
          <span>Sitemap</span>
        </div>
      </div>

      <!-- Introduction -->
      <div class="mb-12 p-6 bg-gray-50 rounded-lg">
        <p class="text-lg text-gray-700 mb-4">
          Welcome to the DeeReel Footies sitemap. Find all pages and sections of our website organized by category.
        </p>
        <p class="text-gray-600">
          Use this page to quickly navigate to any section of our website or discover new content.
        </p>
      </div>

      <!-- Sitemap Grid -->
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        
        <!-- Main Pages -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
          <div class="flex items-center mb-4">
            <div class="bg-blue-100 p-2 rounded-lg mr-3">
              <i class="fas fa-home text-blue-600"></i>
            </div>
            <h2 class="text-xl font-medium">Main Pages</h2>
          </div>
          <ul class="space-y-2">
            <li><a href="/index.php" class="text-gray-600 hover:text-blue-600 hover:underline">Home</a></li>
            <li><a href="/products.php" class="text-gray-600 hover:text-blue-600 hover:underline">All Products</a></li>
            <li><a href="/customize.php" class="text-gray-600 hover:text-blue-600 hover:underline">Customize Shoes</a></li>
            <li><a href="/moo.php" class="text-gray-600 hover:text-blue-600 hover:underline">Made on Order</a></li>
            <li><a href="/shoemaking.php" class="text-gray-600 hover:text-blue-600 hover:underline">Shoemaking Process</a></li>
            <li><a href="/cart.php" class="text-gray-600 hover:text-blue-600 hover:underline">Shopping Cart</a></li>
          </ul>
        </div>

        <!-- Men's Collection -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
          <div class="flex items-center mb-4">
            <div class="bg-green-100 p-2 rounded-lg mr-3">
              <i class="fas fa-male text-green-600"></i>
            </div>
            <h2 class="text-xl font-medium">Men's Collection</h2>
          </div>
          <ul class="space-y-2">
            <li><a href="/men.php" class="text-gray-600 hover:text-blue-600 hover:underline">Men's Overview</a></li>
            <li><a href="/products/men/men-shoes.php" class="text-gray-600 hover:text-blue-600 hover:underline">Men's Shoes</a></li>
            <li><a href="/products/men/men-boots.php" class="text-gray-600 hover:text-blue-600 hover:underline">Men's Boots</a></li>
            <li><a href="/products/men/men-sneakers.php" class="text-gray-600 hover:text-blue-600 hover:underline">Men's Sneakers</a></li>
            <li><a href="/products/men/men-mules.php" class="text-gray-600 hover:text-blue-600 hover:underline">Men's Mules</a></li>
            <li><a href="/products/men/men-slippers.php" class="text-gray-600 hover:text-blue-600 hover:underline">Men's Slippers</a></li>
          </ul>
        </div>

        <!-- Women's Collection -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
          <div class="flex items-center mb-4">
            <div class="bg-pink-100 p-2 rounded-lg mr-3">
              <i class="fas fa-female text-pink-600"></i>
            </div>
            <h2 class="text-xl font-medium">Women's Collection</h2>
          </div>
          <ul class="space-y-2">
            <li><a href="/women.php" class="text-gray-600 hover:text-blue-600 hover:underline">Women's Overview</a></li>
            <li><a href="/products/women/women-shoes.php" class="text-gray-600 hover:text-blue-600 hover:underline">Women's Shoes</a></li>
            <li><a href="/products/women/women-boots.php" class="text-gray-600 hover:text-blue-600 hover:underline">Women's Boots</a></li>
            <li><a href="/products/women/women-sneakers.php" class="text-gray-600 hover:text-blue-600 hover:underline">Women's Sneakers</a></li>
            <li><a href="/products/women/women-mules.php" class="text-gray-600 hover:text-blue-600 hover:underline">Women's Mules</a></li>
            <li><a href="/products/women/women-slippers.php" class="text-gray-600 hover:text-blue-600 hover:underline">Women's Slippers</a></li>
          </ul>
        </div>

        <!-- Customer Account -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
          <div class="flex items-center mb-4">
            <div class="bg-purple-100 p-2 rounded-lg mr-3">
              <i class="fas fa-user text-purple-600"></i>
            </div>
            <h2 class="text-xl font-medium">Customer Account</h2>
          </div>
          <ul class="space-y-2">
            <li><a href="/dashboard.php" class="text-gray-600 hover:text-blue-600 hover:underline">Dashboard</a></li>
            <li><a href="/account-settings.php" class="text-gray-600 hover:text-blue-600 hover:underline">Account Settings</a></li>
            <li><a href="/dashboard.php#orders" class="text-gray-600 hover:text-blue-600 hover:underline">My Orders</a></li>
            <li><a href="/dashboard.php#wishlist" class="text-gray-600 hover:text-blue-600 hover:underline">Wishlist</a></li>
            <li><a href="/dashboard.php#designs" class="text-gray-600 hover:text-blue-600 hover:underline">My Designs</a></li>
            <li><a href="/dashboard.php#address" class="text-gray-600 hover:text-blue-600 hover:underline">Address Book</a></li>
          </ul>
        </div>

        <!-- Information & Support -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
          <div class="flex items-center mb-4">
            <div class="bg-orange-100 p-2 rounded-lg mr-3">
              <i class="fas fa-info-circle text-orange-600"></i>
            </div>
            <h2 class="text-xl font-medium">Information & Support</h2>
          </div>
          <ul class="space-y-2">
            <li><a href="/contact.php" class="text-gray-600 hover:text-blue-600 hover:underline">Contact Us</a></li>
            <li><a href="/faq.php" class="text-gray-600 hover:text-blue-600 hover:underline">FAQ</a></li>
            <li><a href="/size-guide.php" class="text-gray-600 hover:text-blue-600 hover:underline">Size Guide</a></li>
            <li><a href="/care-guide.php" class="text-gray-600 hover:text-blue-600 hover:underline">Shoe Care Guide</a></li>
            <li><a href="/shipping.php" class="text-gray-600 hover:text-blue-600 hover:underline">Shipping Information</a></li>
            <li><a href="/returns.php" class="text-gray-600 hover:text-blue-600 hover:underline">Returns & Exchanges</a></li>
          </ul>
        </div>

        <!-- Company Information -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
          <div class="flex items-center mb-4">
            <div class="bg-indigo-100 p-2 rounded-lg mr-3">
              <i class="fas fa-building text-indigo-600"></i>
            </div>
            <h2 class="text-xl font-medium">Company Information</h2>
          </div>
          <ul class="space-y-2">
            <li><a href="/our-history.php" class="text-gray-600 hover:text-blue-600 hover:underline">Our History</a></li>
            <li><a href="/careers.php" class="text-gray-600 hover:text-blue-600 hover:underline">Careers</a></li>
            <li><a href="/terms.php" class="text-gray-600 hover:text-blue-600 hover:underline">Terms & Conditions</a></li>
            <li><a href="/privacy.php" class="text-gray-600 hover:text-blue-600 hover:underline">Privacy Policy</a></li>
            <li><a href="/cookies.php" class="text-gray-600 hover:text-blue-600 hover:underline">Cookie Policy</a></li>
            <li><a href="/sitemap.php" class="text-gray-600 hover:text-blue-600 hover:underline">Sitemap</a></li>
          </ul>
        </div>

      </div>

      <!-- Product Categories Section -->
      <div class="mt-16">
        <h2 class="text-2xl font-light mb-8 text-center">Product Categories</h2>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- Shoes -->
          <div class="bg-white rounded-lg shadow-sm border p-6 text-center">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-shoe-prints text-2xl text-blue-600"></i>
            </div>
            <h3 class="font-medium mb-3">Shoes</h3>
            <ul class="text-sm text-gray-600 space-y-1">
              <li>Oxford Shoes</li>
              <li>Derby Shoes</li>
              <li>Loafers</li>
              <li>Monk Straps</li>
            </ul>
          </div>

          <!-- Boots -->
          <div class="bg-white rounded-lg shadow-sm border p-6 text-center">
            <div class="bg-brown-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-hiking text-2xl text-amber-700"></i>
            </div>
            <h3 class="font-medium mb-3">Boots</h3>
            <ul class="text-sm text-gray-600 space-y-1">
              <li>Chelsea Boots</li>
              <li>Wingtip Boots</li>
              <li>Captoe Boots</li>
              <li>Jodhpur Boots</li>
            </ul>
          </div>

          <!-- Sneakers -->
          <div class="bg-white rounded-lg shadow-sm border p-6 text-center">
            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-running text-2xl text-green-600"></i>
            </div>
            <h3 class="font-medium mb-3">Sneakers</h3>
            <ul class="text-sm text-gray-600 space-y-1">
              <li>Leather Sneakers</li>
              <li>Canvas Sneakers</li>
              <li>Athletic Style</li>
              <li>Casual Wear</li>
            </ul>
          </div>

          <!-- Casual Footwear -->
          <div class="bg-white rounded-lg shadow-sm border p-6 text-center">
            <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-socks text-2xl text-purple-600"></i>
            </div>
            <h3 class="font-medium mb-3">Casual</h3>
            <ul class="text-sm text-gray-600 space-y-1">
              <li>Mules</li>
              <li>Slippers</li>
              <li>Sandals</li>
              <li>House Shoes</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Quick Links Section -->
      <div class="mt-16 bg-gray-50 rounded-lg p-8">
        <h2 class="text-2xl font-light mb-8 text-center">Quick Links</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
          <!-- Popular Actions -->
          <div>
            <h3 class="font-medium mb-4 text-gray-800">Popular Actions</h3>
            <ul class="space-y-2">
              <li><a href="/products.php?featured=1" class="text-blue-600 hover:underline">View Featured Products</a></li>
              <li><a href="/products.php?new=1" class="text-blue-600 hover:underline">New Arrivals</a></li>
              <li><a href="/customize.php" class="text-blue-600 hover:underline">Customize Your Shoes</a></li>
              <li><a href="/size-guide.php" class="text-blue-600 hover:underline">Find Your Size</a></li>
              <li><a href="/care-guide.php" class="text-blue-600 hover:underline">Shoe Care Tips</a></li>
            </ul>
          </div>

          <!-- Customer Service -->
          <div>
            <h3 class="font-medium mb-4 text-gray-800">Customer Service</h3>
            <ul class="space-y-2">
              <li><a href="/contact.php" class="text-blue-600 hover:underline">Get Help</a></li>
              <li><a href="/faq.php" class="text-blue-600 hover:underline">Common Questions</a></li>
              <li><a href="/returns.php" class="text-blue-600 hover:underline">Return Policy</a></li>
              <li><a href="/shipping.php" class="text-blue-600 hover:underline">Shipping Info</a></li>
              <li><a href="https://wa.me/2347031864772" target="_blank" class="text-blue-600 hover:underline">WhatsApp Support</a></li>
            </ul>
          </div>

          <!-- Social & Connect -->
          <div>
            <h3 class="font-medium mb-4 text-gray-800">Connect With Us</h3>
            <ul class="space-y-2">
              <li><a href="https://instagram.com/deereelfooties" target="_blank" class="text-blue-600 hover:underline">Instagram</a></li>
              <li><a href="https://www.tiktok.com/@deereel.footies" target="_blank" class="text-blue-600 hover:underline">TikTok</a></li>
              <li><a href="mailto:deereelfooties@gmail.com" class="text-blue-600 hover:underline">Email Us</a></li>
              <li><a href="tel:+2348134235110" class="text-blue-600 hover:underline">Call Us</a></li>
              <li><a href="/shoemaking.php#workshop-tour" class="text-blue-600 hover:underline">Visit Our Workshop</a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Search Section -->
      <div class="mt-16 text-center">
        <div class="bg-white rounded-lg shadow-sm border p-8 max-w-2xl mx-auto">
          <h2 class="text-2xl font-light mb-4">Can't Find What You're Looking For?</h2>
          <p class="text-gray-600 mb-6">
            Use our search function or contact our customer service team for assistance.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="document.querySelector('[data-bs-target=\\\"#searchModal\\\"]').click()" 
                    class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">
              <i class="fas fa-search mr-2"></i>Search Website
            </button>
            <a href="/contact.php" class="border border-blue-600 text-blue-600 px-6 py-3 rounded hover:bg-blue-600 hover:text-white transition">
              <i class="fas fa-envelope mr-2"></i>Contact Support
            </a>
          </div>
        </div>
      </div>

      <!-- Last Updated -->
      <div class="mt-12 text-center text-sm text-gray-500">
        <p>Sitemap last updated: <?php echo date('F d, Y'); ?></p>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>

  <!-- Scroll to Top Button -->
  <a href="#" class="btn btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/search-modal.php'); ?>  
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>

  <script>
    // Add smooth scrolling and animations
    document.addEventListener('DOMContentLoaded', function() {
      // Animate cards on scroll
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };

      const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }
        });
      }, observerOptions);

      // Observe all cards
      document.querySelectorAll('.bg-white').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
      });

      // Add hover effects to links
      document.querySelectorAll('a[href]').forEach(link => {
        link.addEventListener('mouseenter', function() {
          this.style.transform = 'translateX(5px)';
          this.style.transition = 'transform 0.2s ease';
        });
        
        link.addEventListener('mouseleave', function() {
          this.style.transform = 'translateX(0)';
        });
      });
    });
  </script>
  
</body>
</html>