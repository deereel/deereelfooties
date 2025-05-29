document.addEventListener('DOMContentLoaded', function() {
  // Mobile menu toggle
  const mobileMenuToggle = document.getElementById('mobileMenuToggle');
  const closeMobileMenu = document.getElementById('closeMobileMenu');
  const mobileNavOverlay = document.querySelector('.mobile-nav-overlay');
  const mobileAccountBtn = document.getElementById('mobileAccountBtn');
  
  // Mobile menu open
  if (mobileMenuToggle && mobileNavOverlay) {
    mobileMenuToggle.addEventListener('click', function() {
      mobileNavOverlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    });
  }
  
  // Mobile menu close
  if (closeMobileMenu && mobileNavOverlay) {
    closeMobileMenu.addEventListener('click', function() {
      mobileNavOverlay.classList.add('hidden');
      document.body.style.overflow = 'auto';
    });
  }
  
  // Close mobile menu when clicking outside
  if (mobileNavOverlay) {
    mobileNavOverlay.addEventListener('click', function(e) {
      if (e.target === mobileNavOverlay) {
        mobileNavOverlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }
    });
  }
  
  // Mobile account button
  if (mobileAccountBtn) {
    mobileAccountBtn.addEventListener('click', function() {
      // Check if user is logged in
      if (window.app && window.app.auth && window.app.auth.isLoggedIn()) {
        // Show mobile account dropdown
        const dropdown = document.createElement('div');
        dropdown.className = 'position-absolute bg-white shadow-sm rounded mt-2 p-2 end-0';
        dropdown.style.zIndex = '1000';
        dropdown.style.minWidth = '150px';
        
        dropdown.innerHTML = `
          <a href="/dashboard.php" class="d-block p-2 text-dark text-decoration-none">Profile</a>
          <hr class="my-1">
          <a href="#" id="mobileLogoutBtnDropdown" class="d-block p-2 text-dark text-decoration-none">Logout</a>
        `;
        
        this.parentNode.appendChild(dropdown);
        
        // Add logout handler
        document.getElementById('mobileLogoutBtnDropdown').addEventListener('click', function(e) {
          e.preventDefault();
          if (window.app && window.app.auth) {
            window.app.auth.logout();
          }
          dropdown.remove();
        });
        
        // Close when clicking outside
        document.addEventListener('click', function closeDropdown(e) {
          if (!dropdown.contains(e.target) && e.target !== mobileAccountBtn) {
            dropdown.remove();
            document.removeEventListener('click', closeDropdown);
          }
        });
      } else {
        // Show login modal
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
        loginModal.show();
      }
    });
  }
  
  // Mobile logout button
  const mobileLogoutBtn = document.getElementById('mobileLogoutBtn');
  if (mobileLogoutBtn) {
    mobileLogoutBtn.addEventListener('click', function(e) {
      e.preventDefault();
      if (window.app && window.app.auth) {
        window.app.auth.logout();
      }
    });
  }
  
  // Update mobile menu based on login state
  function updateMobileMenu() {
    const mobileLoggedOutMenu = document.getElementById('mobile-logged-out-menu');
    const mobileLoggedInMenu = document.getElementById('mobile-logged-in-menu');
    
    if (!mobileLoggedOutMenu || !mobileLoggedInMenu) return;
    
    if (window.app && window.app.auth && window.app.auth.isLoggedIn()) {
      mobileLoggedOutMenu.style.display = 'none';
      mobileLoggedInMenu.style.display = 'block';
    } else {
      mobileLoggedOutMenu.style.display = 'block';
      mobileLoggedInMenu.style.display = 'none';
    }
  }
  
  // Check for app initialization
  const checkAppReady = setInterval(() => {
    if (window.app && window.app.auth) {
      clearInterval(checkAppReady);
      updateMobileMenu();
    }
  }, 100);
  
  // Timeout after 5 seconds
  setTimeout(() => clearInterval(checkAppReady), 5000);
});