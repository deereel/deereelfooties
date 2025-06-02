<!-- Sticky Header -->
  <header class="sticky-top bg-white border-bottom shadow-sm">
    <!-- First Row: Logo and Search Icon -->
    <div class="d-flex justify-content-between align-items-center px-4 py-1 mb-1">
      <a href="/index.php" class="navbar-brand d-flex align-items-center">
        <img src="/images/drf-logo.png" alt="DeeReeL Footies Logo" style="height: 70px;">
        <span class="nav-brand-title" style="font-size: 1.5rem; font-weight: 600;">DeeReel Footies</span>
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
            <li><hr class="dropdown-divider"></li>
            <li><a href="#" id="logoutBtn" class="dropdown-item">Logout</a></li>            
          </ul>
        </li>
      </ul>
    </nav>
    
  </header>