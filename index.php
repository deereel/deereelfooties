<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- SEO Meta Tags -->
  <meta name="description" content="DeeReel Footies – Premium handcrafted shoes for men and women. Shop loafers, boots, slippers, and custom footwear.">
  <meta name="keywords" content="handcrafted shoes, Elegant shoes, DRF, DeeReel Footies, men's shoes, women's boots, women's shoes, men's boots, men's slippers, women's slippers, custom footwear, loafers, sandals, mules, derby, monk strap">
  <meta name="author" content="DeeReel Footies">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>DeeReel Footies | Handcrafted Luxury Shoes for Men and Women</title>
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="/js/product-grid.js" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="/js/product-grid.js" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    /* Top-level dropdown appears on hover */
    .dropdown:hover > .dropdown-menu {
      display: block;
      margin-top: 0;
    }
  
    /* Basic positioning */
    .dropdown {
      position: relative;
    }
  
    .dropdown-menu {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      z-index: 1000;
      margin-top: 0.5rem;
    }
  
    /* Remove Bootstrap's default down arrow */
    .dropdown-toggle::after {
      display: none !important;
    }
  
    /* Nested dropdown positioning */
    .dropdown-submenu {
      position: relative;
    }
  
    .dropdown-submenu > .dropdown-menu {
      display: none;
      position: absolute;
      top: 0;
      left: 100%; /* Align to the right of parent */
      margin-left: 0;
      z-index: 1001;
    }
  
    /* Show submenu on hover */
    .dropdown-submenu:hover > .dropdown-menu {
      display: block;
    }
  
    /* Prevent overlap of submenus */
    .dropdown-menu > .dropdown-submenu {
      position: relative;
    }

    .dropdown-submenu {
      position: relative;
    }
    .dropdown-submenu .dropdown-menu {
      display: none;
    }
    .dropdown-submenu .dropdown-menu.show {
      display: block;
    }

    /* Scroll to Top Custom Styling */
    #scrollToTop {
      background-color: #381819; /* Dark chocolate background */
      color: #fff; /* White icon color */
      align-items: center;
      justify-content: center;
      transition: background-color 0.3s ease, transform 0.3s ease;
    }

    #scrollToTop:hover {
      background-color: #5a2a2b; /* Lighter chocolate on hover */
      transform: scale(1.1);
    }

    html {
      scroll-behavior: smooth;
    }

  </style>
  
  
    
</head>
<body>
  <!-- Sticky Header -->
  <header class="sticky-top bg-white border-bottom shadow-sm">
    <!-- First Row: Logo and Search Icon -->
    <div class="d-flex justify-content-between align-items-center px-4 py-1 mb-1">
      <a href="/index.php" class="navbar-brand d-flex align-items-center">
        <img src="/images/drf-logo.png" alt="DeeReeL Footies Logo" style="height: 50px;">
        <span class="nav-brand-title">DeeReel Footies</span>
      </a>
      <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <button class="btn d-none d-lg-inline">
        <i class="fas fa-search"></i>
      </button>
    </div>

    
      <!-- Mobile Navigation -->
      <div class="mobile-nav d-lg-none">
        <button class="btn btn-outline-secondary" id="mobileMenuToggle" aria-label="Toggle navigation">
          ☰ Menu
        </button>

        <div class="mobile-nav-overlay hidden fixed inset-0 bg-black/80 z-50">
          <div class="mobile-nav-content bg-white h-full w-3/4 max-w-sm shadow-lg p-6 overflow-y-auto">
            <button class="close-btn text-black text-2xl mb-4" id="closeMobileMenu" aria-label="Close navigation">
              &times;
            </button>
            <ul class="space-y-4">
              <li><a href="/men.php" class="text-lg font-medium">MEN</a></li>
              <li><a href="/women.php" class="text-lg font-medium">WOMEN</a></li>
              <li><a href="/products.php" class="text-lg font-medium">PRODUCTS</a></li>
              <li><a href="/size-guide.php" class="text-lg font-medium">SIZE GUIDE</a></li>
              <li><a href="/contact.php" class="text-lg font-medium">CONTACT</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Second Row: Main Navigation -->
    <nav class="d-none d-md-flex w-100 align-items-center">
    <!--<nav class="hidden md:flex items-center space-x-8 mb-2"> -->
      <!-- Left-side navigation -->
      <div class="d-flex align-items-center gap-4">
            <!-- MEN Dropdown -->    
            <div class="dropdown position-relative">
                <a href="/men.php" class="text-dark text-decoration-none" role="button">MEN</a>
                <ul class="dropdown-menu">
                  <!-- Shoes -->
                  <li class="dropdown-submenu">
                      <a href="/products/men/men-shoes.php?gender=men&type=all" class="dropdown-item">Shoes</a>
                      <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="/products/men/men-shoes.php?gender=men&type=loafers">Loafers</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-shoes.php?gender=men&type=oxford">Oxford</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-shoes.php?gender=men&type=derby">Derby</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-shoes.php?gender=men&type=monk">Monk Straps</a></li>
                      </ul>
                  </li>
                  
                  <!-- Boots -->
                  <li class="dropdown-submenu">
                      <a href="/products/men/men-boots.php?gender=men&type=all" class="dropdown-item">Boots</a>
                      <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="/products/men/men-boots.php?gender=men&type=zipper">Zipper boots</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-boots.php?gender=men&type=wingtip">Wingtip boots</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-boots.php?gender=men&type=chelsea">Chelsea boots</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-boots.php?gender=men&type=captoe">Captoe boots</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-boots.php?gender=men&type=jodhpur">Jodhpur boots</a></li>
                          <li><a class="dropdown-item" href="/products/men/men-boots.php?gender=men&type=balmoral">Balmoral boots</a></li>
                      </ul>  
                  </li>
              
                  <li><a class="dropdown-item" href="/products/men/men-slippers.php">Slippers/Sandals</a></li>
                  <li><a class="dropdown-item" href="/products/men/men-mules.php">Mules</a></li>
                </ul>
            </div>
            
            

            <!-- WOMEN Dropdown -->    
            <div class="dropdown position-relative">

                <a href="/women.php" class="text-dark text-decoration-none" role="button">WOMEN</a>
                <ul class="dropdown-menu">
                  <!-- Shoes -->
                  <li class="dropdown-submenu">
                      <a href="/products/women/women-shoes.php?gender=women&type=all" class="dropdown-item">Shoes</a>
                      <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="/products/women/women-shoes.php?gender=women&type=loafers">Loafers</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-shoes.php?gender=women&type=oxford">Oxford</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-shoes.php?gender=women&type=derby">Derby</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-shoes.php?gender=women&type=monk">Monk Straps</a></li>
                      </ul>
                  </li>
                  
                  <!-- Boots -->
                  <li class="dropdown-submenu">
                      <a href="/products/women/women-boots.php?gender=women&type=all" class="dropdown-item">Boots</a>
                      <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="/products/women/women-boots.php?gender=women&type=zipper">Zipper boots</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-boots.php?gender=women&type=wingtip">Wingtip boots</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-boots.php?gender=women&type=chelsea">Chelsea boots</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-boots.php?gender=women&type=captoe">Captoe boots</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-boots.php?gender=women&type=jodhpur">Jodhpur boots</a></li>
                          <li><a class="dropdown-item" href="/products/women/women-boots.php?gender=women&type=balmoral">Balmoral boots</a></li>
                      </ul>
                      </li>
              
                  <li><a class="dropdown-item" href="/products/women/women-slippers.php">Slippers/Sandals</a></li>
                  <li><a class="dropdown-item" href="/products/women/women-mules.php">Mules</a></li>
                </ul>
            </div>
            
            

            <a href="/customize.php" class="nav-link">CUSTOMIZE</a>
            <a href="/moo.php" class="nav-link">Made on Order</a>
            <a href="/products.php" class="nav-link">PRODUCTS</a>
            <a href="/size-guide.php" class="nav-link">SIZE GUIDE</a>
      </div>

      <!-- Right-side Shoemaking and Cart -->
      <ul class="navbar-nav flex-row gap-3 ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="/shoemaking.php">Shoemaking</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/cart.php">
            <i class="fas fa-shopping-cart"></i> Cart
          </a>
        </li>
        <!-- User Account Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userAccountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user"></i> Account
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userAccountDropdown">
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Sign In / Sign Up</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#profileSection">Profile</a></li>
          </ul>
        </li>
      </ul>
    </nav>
    
  </header>

  <!-- Main Content -->
  <main>
    <!-- Hero Section -->
    <section class="relative w-full h-[600px]">
      <img src="/images/hero.jpg" alt="DeeReeL Footies Handcrafted Shoes" class="object-cover w-full h-full">
      <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
        <div class="text-center text-white max-w-3xl px-4">
          <h1 class="text-4xl md:text-5xl font-light mb-4">HANDCRAFTED SHOES FROM MALLORCA</h1>
          <p class="text-lg md:text-xl mb-8">
            Since 1866, creating exceptional footwear with traditional methods and the finest materials
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/men.php" class="bg-white text-black px-8 py-3 font-medium hover:bg-gray-100 transition">
              SHOP MEN
            </a>
            <a href="/women.php" class="bg-white text-black px-8 py-3 font-medium hover:bg-gray-100 transition">
              SHOP WOMEN
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 px-4 max-w-7xl mx-auto">
      <h2 class="text-3xl font-light text-center mb-12">FEATURED COLLECTION</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Add products here -->
        <div class="group product-card"
                 data-price="55000"
                 data-size="40,41,42,43,44,45,46"
                 data-color="tan"
                 data-type="oxford">
              <a href="/products/men/shoes/oxford-cap-toe-600.php">
                <div class="relative aspect-[3/4] overflow-hidden mb-4">
                  <img src="/images/Oxford Cap Toe 600.webp" alt="Oxford Cap Toe 600"
                       class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                </div>
                <h3 class="text-lg">Oxford Cap Toe 600</h3>
                <p class="text-gray-500">₦55,000</p>
              </a>
            </div>

            <div class="group product-card"
                 data-price="55000"
                 data-size="39,40,41,42,43,44,45,46,47"
                 data-color="tan,brown,black"
                 data-type="oxford"
                 data-gender="men">
              <a href="/products/men/shoes/cram-solid-oxford.php">
                <div class="relative aspect-[3/4] overflow-hidden mb-4">
                  <img src="/images/cram solid oxford.webp" alt="Cram Solid oxford"
                       class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                </div>
                <h3 class="text-lg">Cram Solid Oxford</h3>
                <p class="text-gray-500">₦55,000</p>
              </a>
            </div>

        <div class="group product-card"
                 data-price="42000"
                 data-size="40,41,42,43,44,45,46"
                 data-color="brown"
                 data-type="loafer">
              <a href="/products/men/shoes/penny-loafer-600.php">
                <div class="relative aspect-[3/4] overflow-hidden mb-4">
                  <img src="/images/penny loafer 600.webp" alt="Penny Loafer 600"
                       class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                </div>
                <h3 class="text-lg">Penny Loafer 600</h3>
                <p class="text-gray-500">₦42,000</p>
              </a>
            </div>

            <div class="group product-card"
                 data-price="35000"
                 data-size="39,40,41,42,43,44,45,46,47"
                 data-color="tan,green,black,white"
                 data-type="mule"
                 data-gender="men">
              <a href="/products/men/mules/vintage-croc-600.php">
                <div class="relative aspect-[3/4] overflow-hidden mb-4">
                  <img src="/images/Vintage Croc 600.webp" alt="Vintage Croc 600"
                       class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                </div>
                <h3 class="text-lg">Vintage Croc 600</h3>
                <p class="text-gray-500">₦35,000</p>
              </a>
            </div>

        <!-- Add more product here -->

        
      </div>
      <div class="text-center mt-12">
        <a href="/products.php" class="border border-black px-8 py-3 inline-block hover:bg-black hover:text-white transition">
          VIEW ALL
        </a>
      </div>
    </section>

    <!-- Story Section -->
    <section class="py-16 bg-neutral-100">
      <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-12 items-center">
        <div>
          <h2 class="text-3xl font-light mb-6">OUR STORY</h2>
          <p class="mb-4">
            For over 150 years, DeeReeL Footies has been dedicated to the art of shoemaking in Lagos, Nigeria. Our team
            combines traditional craftsmanship with innovative techniques to create shoes of exceptional
            quality and durability.
          </p>
          <p class="mb-6">
            Every pair of DRF shoes is handcrafted by skilled artisans using the finest materials sourced from
            around the world.
          </p>
          <a href="/our-history.php" class="border border-black px-6 py-2 inline-block hover:bg-black hover:text-white transition">
            LEARN MORE
          </a>
        </div>
        <div class="relative h-[500px]">
          <img src="/images/shoemaker-workshop-making-shoes_171337-12290.avif" alt="DRF Workshop" class="object-cover w-full h-full">
        </div>
      </div>
    </section>

    <!-- Inspiration Section -->
    <section class="py-16 px-4">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-light text-center mb-4">MORE INSPIRATION #DEEREEL FOOTIES</h2>
        <p class="text-center mb-12 max-w-2xl mx-auto">
          These customers have already purchased them, see how they look on them. When they are yours, tag
          <a href="www.instagram.com/deereelfooties">@deereelfooties</a> on your instagram posts and <a href="www.tiktok.com/deereel.footies">@deereel.footies</a> on 
          your tiktok posts, and we will share your look!
        </p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-1.jpg" alt="Instagram Post 1" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-2.jpg" alt="Instagram Post 2" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-3.jpg" alt="Instagram Post 3" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-4.jpg" alt="Instagram Post 4" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-5.jpg" alt="Instagram Post 5" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-6.jpg" alt="Instagram Post 6" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-7.jpg" alt="Instagram Post 7" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
          <div class="relative aspect-square overflow-hidden group">
            <img src="/images/instagram-8.jpg" alt="Instagram Post 8" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
              <i class="fab fa-instagram text-white text-2xl"></i>
            </div>
          </div>
        </div>
      </div>
    </section>
    
  </main>

  

  <!-- Footer -->
  <footer class="bg-white border-t">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto py-12 px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      <div>
        <h3 class="font-medium mb-4">ABOUT DEEREEL FOOTIES</h3>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="/our-history.php" class="hover:underline">Our History</a>
          </li>
          <li>
            <a href="/shoemaking.php" class="hover:underline">Craftsmanship</a>
          </li>
          <li>
            <a href="/contact.php" class="hover:underline">Contact Us</a>
          </li>
          <li>
            <a href="/careers.php" class="hover:underline">Careers</a>
          </li>
        </ul>
      </div>

      <div>
        <h3 class="font-medium mb-4">CUSTOMER SERVICE</h3>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="/shipping.php" class="hover:underline">Shipping & Delivery</a>
          </li>
          <li>
            <a href="/returns.php" class="hover:underline">Returns & Exchanges</a>
          </li>
          <li>
            <a href="/size-guide.php" class="hover:underline">Size Guide</a>
          </li>
          <li>
            <a href="/faq.php" class="hover:underline">FAQ</a>
          </li>
          <li>
            <a href="/care-guide.php" class="hover:underline">Shoe Care Guide</a>
          </li>
        </ul>
      </div>

      <div>
        <h3 class="font-medium mb-4">SHOP</h3>
        <ul class="space-y-2 text-sm">
          <li>
            <a href="/men.php" class="hover:underline">Men's Collection</a>
          </li>
          <li>
            <a href="/women.php" class="hover:underline">Women's Collection</a>
          </li>
          <li>
            <a href="/customize.php" class="hover:underline">Customize</a>
          </li>
          <li>
            <a href="/moo.php" class="hover:underline">MADE ON ORDER</a>
          </li>
          <li>
            <a href="/products.php" class="hover:underline">Outlet</a>
          </li>          
        </ul>
      </div>

      <div>
        <h3 class="font-medium mb-4">NEWSLETTER</h3>
        <p class="text-sm mb-4">Subscribe to receive updates, access to exclusive deals, and more.</p>
        <form class="mb-6">
          <div class="flex flex-col space-y-2">
            <input
              type="email"
              class="px-4 py-2 border border-gray-300 focus:outline-none"
              placeholder="Your email address"
              required
            />
            <button type="submit" class="bg-black text-white px-6 py-2 hover:bg-gray-800 transition">
              SUBSCRIBE
            </button>
          </div>
        </form>
        

        <h3 class="font-medium mb-4">FOLLOW US</h3>
        <div class="flex space-x-4">
          <a href="https://www.instagram.com/deereelfooties" class="hover:text-gray-600">
            <i class="fab fa-instagram text-lg"></i>
            <span class="sr-only">Instagram</span>
          </a>
          <a href="https://www.tiktok.com/@deereel.footies" class="hover:text-gray-600">
            <i class="fab fa-tiktok text-lg"></i>
            <span class="sr-only">Tiktok</span>
          </a>
          <a href="https://wa.me/2347031864772?text=Hello%20DeeReeL%20Footies%2C%20I%20would%20like%20to%20place%20order%20for..." class="hover:text-gray-600">
            <i class="fab fa-whatsapp text-lg"></i>
            <span class="sr-only">Twitter</span>
          </a>
          
        </div>
      </div>
    </div>

    <!-- Bottom Footer -->
    <div class="border-t py-6 px-4">
      <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center text-sm">
        <div class="mb-2 mb-md-0">
          <p>&copy; <span id="current-year"></span> DeeReeL Footies. All rights reserved.</p>
        </div>
        <div class="flex flex-wrap justify-center gap-4">
          <a href="/terms.php" class="hover:underline">Terms & Conditions</a>
          <a href="/privacy.php" class="hover:underline">Privacy Policy</a>
          <a href="/cookies.php" class="hover:underline">Cookie Policy</a>
          <a href="/sitemap.php" class="hover:underline">Sitemap</a>
        </div>
      </div>
    </div>
    
  </footer>

  <!-- Scroll to Top Button -->
  <a href="#" class="btn btn-dark position-fixed bottom-0 end-0 m-4 shadow rounded-circle" style="z-index: 999; width: 45px; height: 45px; display: none;" id="scrollToTop">
    <i class="fas fa-chevron-up"></i>
  </a>


  

  <script>
    document.querySelectorAll('.dropdown-submenu > a').forEach(function (element) {
      element.addEventListener('click', function (e) {
        const submenu = this.nextElementSibling;
        if (submenu && submenu.classList.contains('dropdown-menu')) {
          e.preventDefault();
          submenu.classList.toggle('show');
          e.stopPropagation();
        }
      });
    });
  </script>

  <script>
    // Login form validation
    document.querySelector('#loginSection form').addEventListener('submit', function(event) {
      const email = document.getElementById('loginEmail').value.trim();
      const password = document.getElementById('loginPassword').value.trim();
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        event.preventDefault();
        return;
      }

      if (password.length === 0) {
        alert('Please enter your password.');
        event.preventDefault();
        return;
      }
    });

    // Register form validation
    document.querySelector('#registerSection form').addEventListener('submit', function(event) {
      const email = document.getElementById('registerEmail').value.trim();
      const password = document.getElementById('registerPassword').value;
      const confirmPassword = document.getElementById('registerConfirmPassword').value;
      const name = document.getElementById('registerName').value.trim();
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (name.length === 0) {
        alert('Please enter your full name.');
        event.preventDefault();
        return;
      }

      if (!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        event.preventDefault();
        return;
      }

      if (password.length < 6) {
        alert('Password must be at least 6 characters long.');
        event.preventDefault();
        return;
      }

      if (password !== confirmPassword) {
        alert('Passwords do not match.');
        event.preventDefault();
        return;
      }
    });
  </script>

  
  
  <script>
    // Enable dropdowns on hover
    document.querySelectorAll('.dropdown').forEach(function (dropdown) {
      dropdown.addEventListener('mouseenter', function () {
        let toggle = this.querySelector('[data-bs-toggle="dropdown"]');
        if (toggle) {
          let dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(toggle);
          dropdownInstance.show();
        }
      });
      dropdown.addEventListener('mouseleave', function () {
        let toggle = this.querySelector('[data-bs-toggle="dropdown"]');
        if (toggle) {
          let dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(toggle);
          dropdownInstance.hide();
        }
      });
    });
  
    // Also add hover support for nested submenus (if you're using them)
    document.querySelectorAll('.dropdown-submenu').forEach(function (submenu) {
      submenu.addEventListener('mouseenter', function () {
        let submenuList = this.querySelector('.dropdown-menu');
        if (submenuList) submenuList.classList.add('show');
      });
      submenu.addEventListener('mouseleave', function () {
        let submenuList = this.querySelector('.dropdown-menu');
        if (submenuList) submenuList.classList.remove('show');
      });
    });
  </script>
  <script>
    const scrollBtn = document.getElementById("scrollToTop");
  
    window.addEventListener("scroll", () => {
      if (window.scrollY > 300) {
        scrollBtn.style.display = "flex";
      } else {
        scrollBtn.style.display = "none";
      }
    });
  
    scrollBtn.addEventListener("click", (e) => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  </script>

  <script>
    document.querySelectorAll('.dropdown-submenu > a').forEach(link => {
      link.addEventListener('click', function (e) {
        const submenu = this.nextElementSibling;

        // If the user clicked the main link directly (not just hovering)
        if (!submenu || !submenu.classList.contains('dropdown-menu')) {
          return; // not a submenu
        }

        // Prevent the submenu from hijacking the click
        const isSubmenuOpen = submenu.classList.contains('show');
        if (!isSubmenuOpen) {
          // Allow navigation
          window.location.href = this.getAttribute('href');
        }

        e.preventDefault();
      });
    });
  </script>

  <script>
    document.querySelectorAll('.dropdown-submenu > a').forEach(link => {
      link.addEventListener('click', function (e) {
        const submenu = this.nextElementSibling;

        // If the user clicked the main link directly (not just hovering)
        if (!submenu || !submenu.classList.contains('dropdown-menu')) {
          return; // not a submenu
        }

        // Prevent the submenu from hijacking the click
        const isSubmenuOpen = submenu.classList.contains('show');
        if (!isSubmenuOpen) {
          // Allow navigation
          window.location.href = this.getAttribute('href');
        }

        e.preventDefault();
      });
    });
  </script>

  
  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <script>
    // Set the current year in the footer
    document.getElementById('current-year').textContent = new Date().getFullYear();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Bootstrap JS (with Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <script src="/js/main.js"></script>

  <!-- Include this inside body on all pages -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>




</body>
</html>