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

  <title>Oxford Cap Toe 600 | Handcrafted Luxury Shoes for Men and Women</title>
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

    .selected {
      border: 2px solid black !important;
    }
    .thumb:hover {
      cursor: pointer;
      border: 2px solid black;
    }

    .color-option:hover {
      cursor: pointer;
      border: 2px solid black;
    }

    .size-option:hover {
      cursor: pointer;
      border: 2px solid black;
    }

    .width-option:hover {
      cursor: pointer;
      border: 2px solid black;
    }

    .quantity-btn {
      background-color: #381819; /* Dark chocolate background */
      color: #fff; /* White text color */
      border: none; /* Remove default border */
      padding: 10px 15px; /* Add some padding */
      cursor: pointer; /* Change cursor to pointer on hover */
    }

    .quantity-btn:hover {
      background-color: #5a2a2b; /* Lighter chocolate on hover */
    }

    /* Hide number input spinners */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    input[type=number] {
      appearance: textfield;
      -moz-appearance: textfield;
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
    <div class="max-w-7xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <div class="mb-8">
        <div class="flex items-center text-sm text-gray-500">
          <a href="../..//index.php">Home</a>
          <span class="mx-2">/</span>
          <a href="../../men.php">Men</a>
          <span class="mx-2">/</span>
          <span>Oxford Cap Toe 600</span>
        </div>
      </div>

      <!-- Product Details -->
      <div class="row">
        <div class="col-md-6">
          <!-- Main Product Image -->
          <div class="border mb-3">
            <img id="mainImage" src="/images/Oxford Cap Toe 600.webp" class="img-fluid w-100" alt="Main Shoe Image">
          </div>
      
          <!-- Thumbnail Gallery -->
          <div class="d-flex flex-row flex-wrap gap-2">
            <img src="/images/Oxford Cap Toe 600.webp" class="img-thumbnail thumb" style="width: 80px; cursor: pointer;" onclick="changeImage(this)">
            <img src="/images/Oxford Cap Toe 600-1.webp" class="img-thumbnail thumb" style="width: 80px; cursor: pointer;" onclick="changeImage(this)">
            <img src="/images/Oxford Cap Toe 600-2.webp" class="img-thumbnail thumb" style="width: 80px; cursor: pointer;" onclick="changeImage(this)">
            <img src="/images/Oxford Cap Toe 600-3.webp" class="img-thumbnail thumb" style="width: 80px; cursor: pointer;" onclick="changeImage(this)">            
          </div>
        </div>
      
        <div class="col-md-6">
          <!-- Product Info -->
          <h3 class="fw-bold">Oxford Cap Toe 600</h3>
          <p class="text-muted">Premium handcrafted shoes for elegant occasions.</p>
          <ul class="list-disc pl-5 mb-4 text-gray-600">
            <li>Premium calfskin upper</li>
            <li>Leather lining and insole</li>
            <li>Goodyear welted construction</li>
            <li>Oak-tanned leather sole</li>
            <li>Made in Lagos, Nigeria</li>
          </ul>
          <p class="text-2xl mb-4">₦55,000</p>

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

          <!-- Color Selection -->
          <div class="flex space-x-2" id="color-options">
            <button class="w-8 h-8 rounded-full bg-black ring-2 ring-black color-option" data-color="Black" aria-label="Black"></button>
            <button class="color-option w-8 h-8 rounded-full" style="background-color: #5c3a21;" data-color="Dark Brown" aria-label="Dark Brown"></button>
            <button class="color-option w-8 h-8 rounded-full" style="background-color: #000000;" data-color="Black" aria-label="Black"></button>
            <button class="color-option w-8 h-8 rounded-full" style="background-color: #d2b48c;" data-color="Tan" aria-label="Tan"></button>
            <button class="color-option w-8 h-8 rounded-full" style="background-color: #8b4513;" data-color="Brown" aria-label="Brown"></button>
            <button class="color-option w-8 h-8 rounded-full" style="background-color: #1a2456;" data-color="Navy" aria-label="Navy"></button>
          </div>
          

          <!-- Size Selection -->
          <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
              <h3 class="font-medium">Size</h3>
              <button class="text-sm underline" id="size-guide-btn">Size Guide</button>
            </div>
            <div class="grid grid-cols-4 gap-2" id="size-options">
            
              <button class="border border-gray-300 py-2 hover:border-black size-option" data-size="UK 6">UK 6</button>
              <button class="border border-gray-300 py-2 hover:border-black size-option" data-size="UK 7">UK 7</button>
              <button class="border border-gray-300 py-2 hover:border-black size-option" data-size="UK 8">UK 8</button>
              <button class="border border-gray-300 py-2 hover:border-black size-option" data-size="UK 9">UK 9</button>
              <button class="border border-gray-300 py-2 hover:border-black size-option" data-size="UK 10">UK 10</button>
              <button class="border border-gray-300 py-2 hover:border-black size-option" data-size="UK 11">UK 11</button>
              <button class="border border-gray-300 py-2 hover:border-black size-option" data-size="UK 12">UK 12</button>
              <button class="border border-gray-300 py-2 hover:border-black size-option" data-size="UK 13">UK 13</button>           
                            
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
            <button class="btn btn-dark w-full sm:w-auto" id="add-to-cart-btn">Add to Cart</button>
          </div>

          <!-- Additional Options -->
          <div class="flex flex-col sm:flex-row gap-4 mb-8">
            <button class="border border-black px-4 py-2 flex-1 hover:bg-black hover:text-white transition">
              ADD TO WISHLIST
            </button>
            <button class="border border-black px-4 py-2 flex-1 hover:bg-black hover:text-white transition">
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
                <p>
                  The Oxford Cap Toe 600 is a quintessential dress shoe that embodies timeless elegance and superior craftsmanship. Featuring a sleek silhouette with a cap toe design, this Oxford is handcrafted in our workshop in Mallorca using traditional techniques that have been perfected over generations.
                </p>
                <p class="mt-2">
                  The premium calfskin leather develops a beautiful patina over time, making each pair uniquely yours. The Goodyear welt construction ensures durability and allows for resoling, making this an investment piece that will last for years with proper care.
                </p>
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
                <p>Free shipping within Nigeria on all orders over ₦250k.</p>
                <p class="mt-2">Standard shipping: 5-7 business days (Nigeria), 7-10 business days (International)</p>
                <p class="mt-2">Express shipping: 2-4 business days (Nigeria), 5-7 business days (International)</p>
                <p class="mt-2">Returns accepted within 30 days of delivery for unworn shoes in original packaging.</p>
              </div>
            </details>
        </div>
      </div>
      

        

      <!-- Related Products -->
      <section class="mt-16">
        <h2 class="text-2xl font-light mb-8">You May Also Like</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <div class="group">
            <a href="2.php">
              <div class="relative aspect-[3/4] overflow-hidden mb-4">
                <img src="../..//images/product-2.webp" alt="Penny Loafer 80647" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
              </div>
              <h3 class="text-lg">Penny Loafer 80647</h3>
              <p class="text-gray-500">€425</p>
            </a>
          </div>
          <div class="group">
            <a href="3.php">
              <div class="relative aspect-[3/4] overflow-hidden mb-4">
                <img src="../..//images/product-3.webp" alt="Chelsea Boot 80216" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
              </div>
              <h3 class="text-lg">Chelsea Boot 80216</h3>
              <p class="text-gray-500">€495</p>
            </a>
          </div>
          <div class="group">
            <a href="4.php">
              <div class="relative aspect-[3/4] overflow-hidden mb-4">
                <img src="../..//images/product-4.webp" alt="Wing Tip 80290" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
              </div>
              <h3 class="text-lg">Wing Tip 80290</h3>
              <p class="text-gray-500">€460</p>
            </a>
          </div>
          <div class="group">
            <a href="5.php">
              <div class="relative aspect-[3/4] overflow-hidden mb-4">
                <img src="../..//images/product-5.webp" alt="Double Monk 80544" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
              </div>
              <h3 class="text-lg">Double Monk 80544</h3>
              <p class="text-gray-500">€475</p>
            </a>
          </div>
        </div>
      </section>
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
          <p class="mt-4 text-gray-600">
            Note: Different lasts may fit differently. If you have any questions about sizing, please contact our customer service team.
          </p>
        </div>
      </div>
    </div>

    <!-- Added to Cart Modal -->
    <div id="added-to-cart-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
      <div class="bg-white p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-medium">Added to Cart</h2>
          <button id="close-cart-modal" class="text-2xl">&times;</button>
        </div>
        <div class="flex items-center gap-4 mb-4">
          <div class="w-20 h-20 overflow-hidden border">
            <img id="modal-product-image" src="" alt="" class="object-cover w-full h-full">
          </div>
          <div>
            <h3 id="modal-product-name" class="font-medium text-base"></h3>
            <p id="modal-product-variant" class="text-gray-500 text-sm"></p>
            <p id="modal-product-price" class="text-sm font-medium"></p>
          </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
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


  <!-- JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Product image gallery
      const mainImage = document.getElementById('main-product-image');
      const thumbnails = document.querySelectorAll('.product-thumbnail img');
      
      thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
          mainImage.src = this.src;
          mainImage.alt = this.alt;
          
          // Update active thumbnail
          thumbnails.forEach(thumb => {
            thumb.parentElement.classList.remove('ring-2', 'ring-black');
          });
          this.parentElement.classList.add('ring-2', 'ring-black');
        });
      });
      
      // Size guide modal
      const sizeGuideBtn = document.getElementById('size-guide-btn');
      const sizeGuideModal = document.getElementById('size-guide-modal');
      const closeSizeGuide = document.getElementById('close-size-guide');
      
      sizeGuideBtn.addEventListener('click', function() {
        sizeGuideModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
      });
      
      closeSizeGuide.addEventListener('click', function() {
        sizeGuideModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      });
      
         

    function changeImage(el) {
      document.getElementById("mainImage").src = el.src;
    }
  });
  </script>

  <script>
    // Image switching logic
    function changeImage(imgElement) {
      document.getElementById('mainImage').src = imgElement.src;
    }

    // Selection tracking
    document.querySelectorAll('.color-option').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('selected-color').value = btn.getAttribute('data-color');

        document.querySelectorAll('.color-option').forEach(b => b.classList.remove('ring-4', 'ring-black'));
        btn.classList.add('ring-4', 'ring-black');
      });
    });


    document.querySelectorAll('.size-option').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('selected-size').value = btn.getAttribute('data-size');

        // Optional: Highlight selection
        document.querySelectorAll('.size-option').forEach(b => b.classList.remove('bg-dark', 'text-white'));
        btn.classList.add('bg-dark', 'text-white');
      });
    });

    document.querySelectorAll('.width-option').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('selected-width').value = btn.getAttribute('data-width');

        // Optional: Highlight selection
        document.querySelectorAll('.width-option').forEach(b => b.classList.remove('bg-dark', 'text-white'));
        btn.classList.add('bg-dark', 'text-white');
      });
    });

    // Quantity buttons
    document.querySelectorAll('.quantity-btn').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault(); // ✅ Prevent default button behavior

        const input = document.getElementById('quantity');
        let value = parseInt(input.value) || 1;

        if (btn.dataset.action === 'increase') {
          input.value = value + 1;
        } else if (btn.dataset.action === 'decrease' && value > 1) {
          input.value = value - 1;
        }

        // Optional: update hidden input too
        document.getElementById('selected-quantity').value = input.value;
      });
    });

  </script>

    

<!-- Dropdown Hover Support -->
<script>
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



<!-- Footer Year -->
<script>
  document.getElementById('current-year').textContent = new Date().getFullYear();
</script>

<!-- External Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script src="/js/main.js"></script>

  <!-- Include this inside body on all pages -->
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/account-modal.php'); ?>

<script src="/js/product-grid.js" defer></script>



</body>
</html>