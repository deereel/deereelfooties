<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/4cfdaa33e8.js" crossorigin="anonymous"></script>

<!-- Main Script - Simple version with no module dependencies -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  console.log('Main script loaded');
  
  // Initialize user authentication state
  initUserAuth();
  
  // Initialize Bootstrap components
  initBootstrapComponents();
  
  // Initialize scroll to top
  initScrollToTop();
  
  // Initialize swiper if present
  if (typeof Swiper !== 'undefined' && document.querySelector('.hero-swiper')) {
    initHeroSwiper();
  }
});

// Initialize user authentication state
function initUserAuth() {
  // Try to load user from localStorage
  const storedUser = localStorage.getItem('DRFUser');
  let user = null;
  
  if (storedUser) {
    try {
      user = JSON.parse(storedUser);
      console.log('User loaded:', user.name);
    } catch (e) {
      console.error('Error parsing stored user data:', e);
      localStorage.removeItem('DRFUser');
    }
  }
  
  // Update UI based on auth state
  updateAuthUI(user);
  
  // Handle logout button clicks
  document.querySelectorAll('.logout-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      logout();
    });
  });
  
  // Handle login form
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const email = document.getElementById('loginEmail').value;
      const password = document.getElementById('loginPassword').value;
      const errorElement = document.getElementById('loginError');
      
      // Reset previous error
      if (errorElement) {
        errorElement.classList.add('d-none');
      }
      
      // Validate form
      if (!loginForm.checkValidity()) {
        loginForm.classList.add('was-validated');
        return;
      }
      
      // Send login request
      const formData = new FormData();
      formData.append('email', email);
      formData.append('password', password);
      
      fetch('/auth/login.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          console.log('Login successful:', data);
          
          // Save user data to localStorage
          localStorage.setItem('DRFUser', JSON.stringify(data.user));
          
          // Close modal
          const loginModal = document.getElementById('loginModal');
          if (loginModal && typeof bootstrap !== 'undefined') {
            const bsModal = bootstrap.Modal.getInstance(loginModal);
            if (bsModal) bsModal.hide();
          }
          
          // Reload page
          window.location.reload();
        } else {
          // Show error
          if (errorElement) {
            errorElement.textContent = data.error || 'Login failed. Please try again.';
            errorElement.classList.remove('d-none');
          }
        }
      })
      .catch(error => {
        console.error('Login error:', error);
        if (errorElement) {
          errorElement.textContent = 'An error occurred. Please try again.';
          errorElement.classList.remove('d-none');
        }
      });
    });
  }
  
  // Handle signup form
  const signupForm = document.getElementById('signupForm');
  if (signupForm) {
    signupForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const name = document.getElementById('signupName').value;
      const email = document.getElementById('signupEmail').value;
      const password = document.getElementById('signupPassword').value;
      const confirmPassword = document.getElementById('signupConfirmPassword').value;
      const errorElement = document.getElementById('signupError');
      
      // Reset previous error
      if (errorElement) {
        errorElement.classList.add('d-none');
      }
      
      // Check if passwords match
      if (password !== confirmPassword) {
        document.getElementById('signupConfirmPassword').setCustomValidity('Passwords do not match');
      } else {
        document.getElementById('signupConfirmPassword').setCustomValidity('');
      }
      
      // Validate form
      if (!signupForm.checkValidity()) {
        signupForm.classList.add('was-validated');
        return;
      }
      
      // Send signup request
      const formData = new FormData();
      formData.append('name', name);
      formData.append('email', email);
      formData.append('password', password);
      
      fetch('/auth/signup.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          console.log('Signup successful:', data);
          
          // Save user data to localStorage
          localStorage.setItem('DRFUser', JSON.stringify(data.user));
          
          // Close modal
          const loginModal = document.getElementById('loginModal');
          if (loginModal && typeof bootstrap !== 'undefined') {
            const bsModal = bootstrap.Modal.getInstance(loginModal);
            if (bsModal) bsModal.hide();
          }
          
          // Reload page
          window.location.reload();
        } else {
          // Show error
          if (errorElement) {
            errorElement.textContent = data.error || 'Signup failed. Please try again.';
            errorElement.classList.remove('d-none');
          }
        }
      })
      .catch(error => {
        console.error('Signup error:', error);
        if (errorElement) {
          errorElement.textContent = 'An error occurred. Please try again.';
          errorElement.classList.remove('d-none');
        }
      });
    });
  }
  
  // Handle form switching
  const showSignupForm = document.getElementById('showSignupForm');
  const showLoginForm = document.getElementById('showLoginForm');
  
  if (showSignupForm && loginForm && signupForm) {
    showSignupForm.addEventListener('click', function(e) {
      e.preventDefault();
      loginForm.classList.add('d-none');
      signupForm.classList.remove('d-none');
      
      const loginText = document.getElementById('loginText');
      const signupText = document.getElementById('signupText');
      if (loginText) loginText.classList.add('d-none');
      if (signupText) signupText.classList.remove('d-none');
      
      const modalTitle = document.querySelector('#loginModal .modal-title');
      if (modalTitle) modalTitle.textContent = 'Create Account';
    });
  }
  
  if (showLoginForm && loginForm && signupForm) {
    showLoginForm.addEventListener('click', function(e) {
      e.preventDefault();
      signupForm.classList.add('d-none');
      loginForm.classList.remove('d-none');
      
      const loginText = document.getElementById('loginText');
      const signupText = document.getElementById('signupText');
      if (signupText) signupText.classList.add('d-none');
      if (loginText) loginText.classList.remove('d-none');
      
      const modalTitle = document.querySelector('#loginModal .modal-title');
      if (modalTitle) modalTitle.textContent = 'Sign In';
    });
  }
}

// Update UI based on authentication state
function updateAuthUI(user) {
  const loggedInElements = document.querySelectorAll('.logged-in');
  const loggedOutElements = document.querySelectorAll('.logged-out');
  const userNameElements = document.querySelectorAll('.user-name');
  
  if (user) {
    // User is logged in
    loggedInElements.forEach(el => el.classList.remove('d-none'));
    loggedOutElements.forEach(el => el.classList.add('d-none'));
    
    // Update user name elements
    userNameElements.forEach(el => {
      el.textContent = user.name;
    });
  } else {
    // User is logged out
    loggedInElements.forEach(el => el.classList.add('d-none'));
    loggedOutElements.forEach(el => el.classList.remove('d-none'));
  }
}

// Logout function
function logout() {
  localStorage.removeItem('DRFUser');
  
  // Call server logout
  fetch('/auth/logout.php')
    .finally(() => {
      window.location.reload();
    });
}

// Initialize Bootstrap components
function initBootstrapComponents() {
  if (typeof bootstrap === 'undefined') {
    console.error('Bootstrap is not loaded');
    return;
  }
  
  // Initialize dropdowns
  document.querySelectorAll('.dropdown-toggle').forEach(dropdownToggle => {
    try {
      new bootstrap.Dropdown(dropdownToggle);
    } catch (e) {
      console.error('Error initializing dropdown:', e);
    }
  });
  
  // Initialize tooltips
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(tooltipTriggerEl => {
    try {
      new bootstrap.Tooltip(tooltipTriggerEl);
    } catch (e) {
      console.error('Error initializing tooltip:', e);
    }
  });
  
  // Initialize popovers
  document.querySelectorAll('[data-bs-toggle="popover"]').forEach(popoverTriggerEl => {
    try {
      new bootstrap.Popover(popoverTriggerEl);
    } catch (e) {
      console.error('Error initializing popover:', e);
    }
  });
  
  // Initialize dropdown submenus
  document.querySelectorAll('.dropdown-submenu').forEach(menu => {
    const link = menu.querySelector('.dropdown-item');
    const submenu = menu.querySelector('.dropdown-menu');
    
    if (link && submenu) {
      // For desktop: show on hover
      menu.addEventListener('mouseenter', function() {
        if (window.innerWidth >= 992) {
          submenu.classList.add('show');
        }
      });
      
      menu.addEventListener('mouseleave', function() {
        if (window.innerWidth >= 992) {
          submenu.classList.remove('show');
        }
      });
      
      // For mobile: show on click
      link.addEventListener('click', function(e) {
        if (window.innerWidth < 992) {
          e.preventDefault();
          e.stopPropagation();
          
          submenu.classList.toggle('show');
        }
      });
    }
  });
}

// Initialize scroll to top functionality
function initScrollToTop() {
  const scrollToTopBtn = document.getElementById('scrollToTop');
  if (scrollToTopBtn) {
    window.addEventListener('scroll', function() {
      if (window.pageYOffset > 300) {
        scrollToTopBtn.style.display = 'flex';
      } else {
        scrollToTopBtn.style.display = 'none';
      }
    });
    
    scrollToTopBtn.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }
}

// Initialize hero swiper
function initHeroSwiper() {
  try {
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
    console.log('Hero swiper initialized');
  } catch (error) {
    console.error('Error initializing hero swiper:', error);
  }
}
</script>

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<!-- Page-specific scripts -->
<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$pageScripts = [
  'customize' => '/js/customize.js',
  'cart' => ['/js/cart-page.js'],
  'checkout' => '/js/checkout.js',
  'profile' => '/js/profile.js',
  'dashboard' => '/js/dashboard.js'
];

if (isset($pageScripts[$currentPage])) {
  $scripts = is_array($pageScripts[$currentPage]) ? $pageScripts[$currentPage] : [$pageScripts[$currentPage]];
  foreach ($scripts as $script) {
    echo "<script src=\"{$script}\"></script>\n";
  }
}
?>