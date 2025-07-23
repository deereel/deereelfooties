<?php
// Common scripts included across the site
?>
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery (required for some Bootstrap components) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Custom Scripts -->
<script src="/js/cart-handler.js"></script>
<script src="/js/wishlist.js"></script>
<script src="/js/product-grid.js"></script>
<script src="/js/product-filters.js"></script>
<script src="/js/subcategory-links.js"></script>

<script>
  // Initialize cart handler and update cart count on page load
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof CartHandler !== 'undefined') {
      if (!window.cartHandler) {
        window.cartHandler = new CartHandler();
      }
      window.cartHandler.updateCartCount();
    }
  });
</script>

<!-- Bootstrap JS (already included above) -->
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
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              console.log('Login successful:', data);

              // Save user data to localStorage
              localStorage.setItem('DRFUser', JSON.stringify(data.user));

              // Update cart handler with user ID for cart migration
              if (window.cartHandler) {
                // Ensure cart handler recognizes login status
                window.cartHandler.checkLoginStatus();
                
                // Handle cart merge and then redirect
                window.cartHandler.handleLogin(data.user.id || data.user.user_id).then(() => {
                  console.log('Cart merge completed, now redirecting...');
                  
                  // Close modal and redirect after cart merge
                  const loginModal = document.getElementById('loginModal');
                  if (loginModal && typeof bootstrap !== 'undefined') {
                    bootstrap.Modal.getInstance(loginModal).hide();
                  }

                  // Redirect to dashboard or reload page
                  if (window.location.pathname.includes('cart')) {
                    window.location.reload(); // Reload cart page to show merged items
                  } else {
                    window.location.href = '/dashboard/';
                  }
                });
              } else {
                // No cart handler, proceed with normal redirect
                const loginModal = document.getElementById('loginModal');
                if (loginModal && typeof bootstrap !== 'undefined') {
                  bootstrap.Modal.getInstance(loginModal).hide();
                }

                if (window.location.pathname.includes('cart')) {
                  window.location.reload();
                } else {
                  window.location.href = '/dashboard/';
                }
              }
            } else {
              alert('Login failed: ' + data.message);
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
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              console.log('Signup successful:', data);

              // Save user data to localStorage
              localStorage.setItem('DRFUser', JSON.stringify(data.user));

              // Update cart handler with user ID for cart migration
              if (window.cartHandler) {
                window.cartHandler.handleLogin(data.user.id);
              }

              // Close modal
              const loginModal = document.getElementById('loginModal');
              if (loginModal && typeof bootstrap !== 'undefined') {
                const bsModal = bootstrap.Modal.getInstance(loginModal);
                if (bsModal) bsModal.hide();
              }

              // Reload page after a short delay to allow cart migration
              setTimeout(() => {
                window.location.reload();
              }, 1000);
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
        if (loginText) loginText.classList.remove('d-none');
        if (signupText) signupText.classList.add('d-none');

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
  function handleLogout() {
    // Handle cart before logout
    if (window.cartHandler) {
        window.cartHandler.handleLogout().then(() => {
            // Clear user data
            localStorage.removeItem('DRFUser');
            
            // Redirect to home page
            window.location.href = '/';
        });
    } else {
        // Fallback if cart handler not available
        localStorage.removeItem('DRFUser');
        window.location.href = '/';
    }
}

// Update existing logout button event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.logout-btn, [data-logout]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            handleLogout();
        });
    });
});

  // Initialize Bootstrap components
  function initBootstrapComponents() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
      return new bootstrap.Popover(popoverTriggerEl);
    });
  }

  // Initialize scroll to top button
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
    new Swiper('.hero-swiper', {
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  }
</script>


  <script>
document.addEventListener('DOMContentLoaded', function () {
  const tryHandleLogin = () => {
    const userMeta = document.querySelector('meta[name="user-id"]');
    if (userMeta && window.cartHandler && !window.cartHandler.isLoggedIn) {
      window.cartHandler.handleLogin(parseInt(userMeta.content));
    }
  };

  // Try once now, or again after 200ms if handler isn't ready yet
  if (window.cartHandler) {
    tryHandleLogin();
  } else {
    setTimeout(tryHandleLogin, 200);
  }
});
</script>

<script>
(function waitForCartHandlerAndMeta() {
  const userMeta = document.querySelector('meta[name="user-id"]');

  if (userMeta && window.cartHandler && !window.cartHandler.isLoggedIn) {
    console.log('Running handleLogin() from global script...');
    window.cartHandler.handleLogin(parseInt(userMeta.content));
  } else {
    setTimeout(waitForCartHandlerAndMeta, 100);
  }
})();
</script>
