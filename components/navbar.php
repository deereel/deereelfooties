<?php
// Get current user from session
$currentUser = isset($_SESSION['user']) ? $_SESSION['user'] : null;
// Fallback to old session format if needed
if (!$currentUser && isset($_SESSION['user_id'])) {
  $currentUser = [
    'id' => $_SESSION['user_id'],
    'name' => $_SESSION['username'] ?? 'User'
  ];
}
?>

<header class="sticky-top shadow-sm" style="background-color: var(--color-primary) !important;">
  <!-- Desktop: Logo and Search Icon -->
  <div class="d-none d-lg-flex justify-content-between align-items-center px-4 py-1 mb-1">
    <a href="/index.php" class="navbar-brand d-flex align-items-center" style="color: var(--color-text-light) !important;">
      <img src="/images/drf-logo.webp" alt="DeeReeL Footies Logo" style="height: 70px;">
      <span class="nav-brand-title" style="font-size: 1.5rem; font-weight: 600; color: var(--color-text-light) !important;">DeeReel Footies</span>
    </a>

    <button class="btn" style="color: var(--color-text-light) !important;">
      <i class="fas fa-search"></i>
    </button>
  </div>

  <!-- Mobile Navigation -->
  <div class="mobile-nav d-lg-none d-flex justify-content-between align-items-center px-4 py-2">
    <button class="btn btn-outline-light" id="mobileMenuToggle" aria-label="Toggle navigation" style="border-color: var(--color-secondary) !important; color: var(--color-text-light) !important;">
      ☰ Menu
    </button>
    
    <a href="/index.php" class="navbar-brand d-flex align-items-center" style="color: var(--color-text-light) !important;">
      <img src="/images/drf-logo.webp" alt="DeeReeL Footies Logo" style="height: 60px;">
      <span class="nav-brand-title" style="font-size: 1.3rem; font-weight: 600; color: var(--color-text-light) !important;">DeeReel Footies</span>
    </a>
    
    <div style="width: 80px;"></div>

    <div class="mobile-nav-overlay hidden fixed inset-0 bg-black/80 z-50">
      <div class="mobile-nav-content bg-white h-full w-3/4 max-w-sm shadow-lg p-6 overflow-y-auto">
        <button class="close-btn text-black text-2xl mb-4" id="closeMobileMenu" aria-label="Close navigation">
          &times;
        </button>
        <ul class="space-y-4">
          <li><a href="/men.php" class="text-lg font-medium" style="color: var(--color-text-dark) !important;">MEN</a></li>
          <li><a href="/women.php" class="text-lg font-medium" style="color: var(--color-text-dark) !important;">WOMEN</a></li>
          <li><a href="/products.php" class="text-lg font-medium" style="color: var(--color-text-dark) !important;">PRODUCTS</a></li>
          <li><a href="/size-guide.php" class="text-lg font-medium" style="color: var(--color-text-dark) !important;">SIZE GUIDE</a></li>
          <li><a href="/contact.php" class="text-lg font-medium" style="color: var(--color-text-dark) !important;">CONTACT</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Second Row: Main Navigation -->
  <nav class="d-none d-md-flex w-100 align-items-center" style="background-color: var(--color-primary) !important;">
    <!-- Left-side navigation -->
    <div class="d-flex align-items-center gap-4">
      <!-- MEN Dropdown -->    
      <div class="dropdown position-relative">
        <a href="/men.php" class="text-decoration-none" role="button" style="color: var(--color-text-light) !important;">MEN</a>
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
          <li><a class="dropdown-item" href="/products/men/men-sneakers.php">Sneakers</a></li>
        </ul>
      </div>
      
      <!-- WOMEN Dropdown -->    
      <div class="dropdown position-relative">
        <a href="/women.php" class="text-decoration-none" role="button" style="color: var(--color-text-light) !important;">WOMEN</a>
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
          <li><a class="dropdown-item" href="/products/women/women-sneakers.php">Sneakers</a></li>
        </ul>
      </div>

      <a href="/customize.php" class="nav-link" style="color: var(--color-text-light) !important;">CUSTOMIZE</a>
      <a href="/moo.php" class="nav-link" style="color: var(--color-text-light) !important;">Made on Order</a>
      <a href="/products.php" class="nav-link" style="color: var(--color-text-light) !important;">PRODUCTS</a>
      <a href="/size-guide.php" class="nav-link" style="color: var(--color-text-light) !important;">SIZE GUIDE</a>
    </div>

    <!-- Right-side Shoemaking -->
    <ul class="navbar-nav flex-row gap-3 ms-auto">
      <li class="nav-item">
        <a class="nav-link" href="/shoemaking.php" style="color: var(--color-text-light) !important;">Shoemaking</a>
      </li>
      
      <!-- Cart Icon -->
      <li class="nav-item">
        <a class="nav-link position-relative" href="/cart.php" style="color: var(--color-text-light) !important;">
          <i class="fas fa-shopping-cart"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count d-none">0</span>
        </a>
      </li>
      
      <!-- User Account Dropdown -->
      <li class="nav-item dropdown">
        <!-- Logged out state -->
        <a class="nav-link dropdown-toggle logged-out <?= $currentUser ? 'd-none' : '' ?>" href="#" id="userAccountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--color-text-light) !important;">
          <i class="fas fa-user"></i> Account
        </a>
        
        <!-- Logged in state -->
        <a class="nav-link dropdown-toggle logged-in <?= $currentUser ? '' : 'd-none' ?>" href="#" id="userAccountDropdownLoggedIn" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--color-text-light) !important;">
          <i class="fas fa-user"></i> <span class="user-name"><?= $currentUser ? htmlspecialchars($currentUser['name']) : 'User' ?></span>
        </a>
        
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userAccountDropdown">
          <!-- Logged out state -->
          <li class="logged-out <?= $currentUser ? 'd-none' : '' ?>"><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Sign In / Sign Up</a></li>
          
          <!-- Logged in state -->
          <li class="logged-in <?= $currentUser ? '' : 'd-none' ?>"><a class="dropdown-item" href="/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
          <li class="logged-in <?= $currentUser ? '' : 'd-none' ?>"><a class="dropdown-item" href="/dashboard.php?tab=orders"><i class="fas fa-box me-2"></i>My Orders</a></li>
          <li class="logged-in <?= $currentUser ? '' : 'd-none' ?>"><a class="dropdown-item" href="/dashboard.php?tab=wishlist"><i class="fas fa-heart me-2"></i>Wishlist</a></li>
          <li class="logged-in <?= $currentUser ? '' : 'd-none' ?>"><a class="dropdown-item" href="/dashboard.php?tab=designs"><i class="fas fa-palette me-2"></i>My Designs</a></li>
          <li class="logged-in <?= $currentUser ? '' : 'd-none' ?>"><a class="dropdown-item" href="/account-settings.php"><i class="fas fa-user-edit me-2"></i>Account Settings</a></li>
          <li class="logged-in <?= $currentUser ? '' : 'd-none' ?>"><hr class="dropdown-divider"></li>
          <li class="logged-in <?= $currentUser ? '' : 'd-none' ?>"><a href="#" class="dropdown-item logout-btn text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
      </li>
    </ul>
  </nav>
</header>

<?php if ($currentUser): ?>
  <!-- Add user ID as meta tag for JavaScript -->
  <meta name="user-id" content="<?= htmlspecialchars($currentUser['id'] ?? $currentUser['user_id'] ?? '') ?>">
<?php endif; ?>

<!--
  Synchronize client-side (localStorage) and server-side (PHP Session) login states.
  This script ensures that JavaScript components, like the CartHandler, have the correct
  user status on page load, preventing issues where a user is logged in but actions
  are treated as if they were a guest (or vice-versa).

-->

<script>
  (function() {
    try {
      const serverUser = <?= $currentUser ? json_encode($currentUser) : 'null' ?>;
      const localUserRaw = localStorage.getItem('DRFUser');

      if (serverUser && !localUserRaw) {
        console.log('Client-side user not found. Syncing from server session.');
        localStorage.setItem('DRFUser', JSON.stringify(serverUser));
      } else if (!serverUser && localUserRaw) {
        console.log('Server session not found. Clearing client-side user.');
        localStorage.removeItem('DRFUser');
      }
    } catch (e) {
      console.error('Failed to sync user state with localStorage:', e);
    }
  })();
</script>

<script>
// Initialize user authentication state immediately
document.addEventListener('DOMContentLoaded', function() {
  const storedUser = localStorage.getItem('DRFUser');
  if (storedUser) {
    try {
      const user = JSON.parse(storedUser);
      
      // Show username in navbar
      document.querySelectorAll('.user-name').forEach(function(el) {
        el.textContent = user.name || 'User';
      });
      
      // Show logged-in elements, hide logged-out elements
      document.querySelectorAll('.logged-in').forEach(function(el) {
        el.classList.remove('d-none');
      });
      document.querySelectorAll('.logged-out').forEach(function(el) {
        el.classList.add('d-none');
      });
      
    } catch (e) {
      console.error('Error parsing user data:', e);
    }
  } else {
    // Guest user, no special handling needed
  }
  
  // Handle logout button
  document.querySelectorAll('.logout-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      if (confirm('Are you sure you want to logout?')) {
        // Save cart before logout if cart handler exists
        if (window.cartHandler) {
          window.cartHandler.handleLogout();
        }
        
        localStorage.removeItem('DRFUser');
        fetch('/auth/logout.php').finally(() => {
          window.location.href = '/index.php';
        });
      }
    });
  });
  
  // Mobile menu functionality
  const mobileMenuToggle = document.getElementById('mobileMenuToggle');
  const closeMobileMenu = document.getElementById('closeMobileMenu');
  const mobileNavOverlay = document.querySelector('.mobile-nav-overlay');
  
  if (mobileMenuToggle && mobileNavOverlay) {
    mobileMenuToggle.addEventListener('click', function() {
      mobileNavOverlay.classList.remove('hidden');
    });
  }
  
  if (closeMobileMenu && mobileNavOverlay) {
    closeMobileMenu.addEventListener('click', function() {
      mobileNavOverlay.classList.add('hidden');
    });
  }
  
  // Close mobile menu when clicking overlay
  if (mobileNavOverlay) {
    mobileNavOverlay.addEventListener('click', function(e) {
      if (e.target === mobileNavOverlay) {
        mobileNavOverlay.classList.add('hidden');
      }
    });
  }
});
</script>
