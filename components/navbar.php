<!-- Sticky Header -->
  <header class="sticky-top bg-primary text-white border-bottom shadow-sm">
    <!-- First Row: Logo -->
    <div class="d-flex justify-content-between align-items-center px-4 py-1">
      <a href="/index.php" class="navbar-brand d-flex align-items-center">
        <img src="/images/drf-logo.png" alt="DeeReeL Footies Logo" style="height: 50px;">
        <span class="nav-brand-title">DeeReel Footies</span>
      </a>
      <button class="btn d-none d-lg-inline" data-bs-toggle="modal" data-bs-target="#searchModal">
        <i class="fas fa-search"></i>
      </button>
    </div>
    
    <!-- Second Row: Mobile Menu Button -->
    <div class="d-lg-none px-4 py-2 border-top border-secondary">
      <div class="d-flex justify-content-between align-items-center">
        <button class="btn btn-outline-secondary" id="mobileMenuToggle" type="button" aria-label="Toggle navigation">
          <i class="fas fa-bars"></i> Menu
        </button>
        <div>
          <a href="/cart.php" class="btn btn-outline-secondary me-2">
            <i class="fas fa-shopping-cart"></i>
          </a>
          <button class="btn btn-outline-secondary" id="mobileAccountBtn">
            <i class="fas fa-user"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Navigation -->
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
          <li class="border-t pt-4 mt-4">
            <div id="mobile-logged-out-menu">
              <a href="#" class="text-lg font-medium" data-bs-toggle="modal" data-bs-target="#loginModal">Sign In / Sign Up</a>
            </div>
            <div id="mobile-logged-in-menu" style="display: none;">
              <a href="/dashboard.php" class="text-lg font-medium">My Profile</a>
              <a href="#" id="mobileLogoutBtn" class="text-lg font-medium d-block mt-2">Logout</a>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- Desktop Navigation -->
    <nav class="d-none d-md-flex w-100 align-items-center">
      <!-- Left-side navigation -->
      <div class="d-flex align-items-center gap-4">
            <!-- MEN Dropdown -->    
            <div class="dropdown position-relative">
                <a href="/men.php" class="text-white text-decoration-none" role="button">MEN</a>
                <ul class="dropdown-menu bg-white">
                  <!-- Shoes -->
                  <li class="dropdown-submenu">
                      <a href="/products/men/men-shoes.php" class="dropdown-item text-dark">Shoes</a>
                      <ul class="dropdown-menu bg-white">
                            <li><a class="dropdown-item text-dark" href="/products/men/men-shoes.php?type=loafer">Loafers</a></li>
                            <li><a class="dropdown-item text-dark" href="/products/men/men-shoes.php?type=oxford">Oxford</a></li>
                            <li><a class="dropdown-item text-dark" href="/products/men/men-shoes.php?type=derby">Derby</a></li>
                            <li><a class="dropdown-item text-dark" href="/products/men/men-shoes.php?type=monk">Monk Straps</a></li>
                    </ul>
                  </li>
                  
                  <!-- Boots -->
                  <li class="dropdown-submenu">
                      <a href="/products/men/men-boots.php" class="dropdown-item text-dark">Boots</a>
                      <ul class="dropdown-menu bg-white">
                          <li><a class="dropdown-item text-dark" href="/products/men/men-boots.php?type=zipper">Zipper boots</a></li>
                          <li><a class="dropdown-item text-dark" href="/products/men/men-boots.php?type=wingtip">Wingtip boots</a></li>
                          <li><a class="dropdown-item text-dark" href="/products/men/men-boots.php?type=chelsea">Chelsea boots</a></li>
                          <li><a class="dropdown-item text-dark" href="/products/men/men-boots.php?type=captoe">Captoe boots</a></li>
                          <li><a class="dropdown-item text-dark" href="/products/men/men-boots.php?type=jodhpur">Jodhpur boots</a></li>
                          <li><a class="dropdown-item text-dark" href="/products/men/men-boots.php?type=balmoral">Balmoral boots</a></li>
                      </ul>  
                  </li>
              
                  <li><a class="dropdown-item text-dark" href="/products/men/men-slippers.php">Slippers/Sandals</a></li>
                  <li><a class="dropdown-item text-dark" href="/products/men/men-mules.php">Mules</a></li>
                </ul>
            </div>
            
            

            <!-- WOMEN Dropdown -->    
            <div class="dropdown position-relative">

                <a href="/women.php" class="text-white text-decoration-none" role="button">WOMEN</a>
                <ul class="dropdown-menu bg-white">
                  <!-- Shoes -->
                  <li class="dropdown-submenu">
                      <a href="/products/women/women-shoes.php" class="dropdown-item text-dark">Shoes</a>
                      <ul class="dropdown-menu bg-white">
                          <li><a class="dropdown-item text-dark" href="/products/women/women-shoes.php?type=loafer">Loafers</a></li>
                          <li><a class="dropdown-item text-dark" href="/products/women/women-shoes.php?type=oxford">Oxford</a></li>
                          <li><a class="dropdown-item text-dark" href="/products/women/women-shoes.php?type=derby">Derby</a></li>
                          <li><a class="dropdown-item text-dark" href="/products/women/women-shoes.php?type=monk">Monk Straps</a></li>
                      </ul>
                  </li>
                  
                  <!-- Boots -->
                  <li class="dropdown-submenu">
                      <a href="/products/women/women-boots.php" class="dropdown-item text-dark">Boots</a>
                      <ul class="dropdown-menu bg-white">
                          <li><a class="dropdown-item text-dark" href="/products/women/women-boots.php?type=zipper">Zipper boots</a></li>
                          <li><a class="dropdown-item text-dark" href="/products/women/women-boots.php?type=wingtip">Wingtip boots</a></li>
                          <li><a class="dropdown-item text-dark" href="/products/women/women-boots.php?type=chelsea">Chelsea boots</a></li>
                          <li><a class="dropdown-item text-dark" href="/products/women/women-boots.php?type=captoe">Captoe boots</a></li>
                          <li><a class="dropdown-item text-dark" href="/products/women/women-boots.php?type=jodhpur">Jodhpur boots</a></li>
                          <li><a class="dropdown-item text-dark" href="/products/women/women-boots.php?type=balmoral">Balmoral boots</a></li>
                      </ul>
                      </li>
              
                  <li><a class="dropdown-item text-dark" href="/products/women/women-slippers.php">Slippers/Sandals</a></li>
                  <li><a class="dropdown-item text-dark" href="/products/women/women-mules.php">Mules</a></li>
                </ul>
            </div>
            
            

            <a href="/customize.php" class="nav-link text-white">CUSTOMIZE</a>
            <a href="/moo.php" class="nav-link text-white">Made on Order</a>
            <a href="/products.php" class="nav-link text-white">PRODUCTS</a>
            <a href="/size-guide.php" class="nav-link text-white">SIZE GUIDE</a>
      </div>

      <!-- Right-side Shoemaking and Cart -->
      <ul class="navbar-nav flex-row gap-3 ms-auto">
        <li class="nav-item">
          <a class="nav-link text-white" href="/shoemaking.php">Shoemaking</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="/cart.php">
            <i class="fas fa-shopping-cart"></i> Cart
          </a>
        </li>
        <!-- User Account Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="userAccountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user"></i> <span id="username-display">Account</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end bg-white" aria-labelledby="userAccountDropdown">
            <!-- Login/Signup option (shown when logged out) -->
            <div id="logged-out-menu">
              <li><a class="dropdown-item text-dark" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Sign In / Sign Up</a></li>
            </div>
            
            <!-- User options (shown when logged in) -->
            <div id="logged-in-menu" style="display: none;">
              <li><a class="dropdown-item text-dark" href="/dashboard.php">Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a href="#" id="logoutBtn" class="dropdown-item text-dark">Logout</a></li>
            </div>
          </ul>
        </li>
      </ul>
    </nav>
    
  </header>