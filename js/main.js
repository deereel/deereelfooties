// Main application script
document.addEventListener('DOMContentLoaded', function() {
  console.log('Main.js loaded');
  
  // Initialize user authentication state
  initUserAuth();
  
  // Initialize navbar functionality
  initNavbar();
  
  // Initialize scroll to top button
  initScrollToTop();
});

// Initialize user authentication state
function initUserAuth() {
  const storedUser = localStorage.getItem('DRFUser');
  let user = null;

  if (storedUser) {
    try {
      user = JSON.parse(storedUser);
      console.log('User loaded from localStorage:', user.name);
      updateAuthUI(user);
    } catch (e) {
      console.error('Error parsing stored user data:', e);
      localStorage.removeItem('DRFUser');
    }
  } else {
    // Fallback: Try loading from server session
    fetch('/auth/get_user.php')
      .then(res => res.json())
      .then(data => {
        if (data.success && data.user) {
          console.log('User loaded from session:', data.user.name);
          localStorage.setItem('DRFUser', JSON.stringify(data.user));
          updateAuthUI(data.user);
        } else {
          console.log('No active user session found.');
          updateAuthUI(null);
        }
      })
      .catch(err => {
        console.error('Error loading user from session:', err);
        updateAuthUI(null);
      });
  }

  // Setup logout buttons
  document.querySelectorAll('.logout-btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      logout();
    });
  });
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

// Initialize navbar functionality
function initNavbar() {
  console.log('Initializing navbar');
  
  // Check if navbar exists
  const navbar = document.querySelector('.navbar');
  if (!navbar) {
    console.error('Navbar not found in the document');
    return;
  }
  
  // Make sure Bootstrap is available
  if (typeof bootstrap === 'undefined') {
    console.error('Bootstrap is not loaded');
    
    // Fallback for navbar toggler if Bootstrap is not available
    const navbarToggler = navbar.querySelector('.navbar-toggler');
    const navbarCollapse = navbar.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
      navbarToggler.addEventListener('click', function() {
        navbarCollapse.classList.toggle('show');
      });
    }
    return;
  }
  
  // Initialize Bootstrap dropdowns
  document.querySelectorAll('.dropdown-toggle').forEach(dropdownToggle => {
    try {
      new bootstrap.Dropdown(dropdownToggle);
    } catch (e) {
      console.error('Error initializing dropdown:', e);
    }
  });
  
  // Initialize dropdown menus for navbar
  const dropdownMenus = document.querySelectorAll('.dropdown-submenu');
  
  dropdownMenus.forEach(menu => {
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
  
  // Handle navbar toggler for mobile
  const navbarToggler = navbar.querySelector('.navbar-toggler');
  const navbarCollapse = navbar.querySelector('.navbar-collapse');
  
  if (navbarToggler && navbarCollapse) {
    navbarToggler.addEventListener('click', function() {
      navbarCollapse.classList.toggle('show');
    });
  }
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

function onUserLogin(user) {
  window.cartHandler.onLogin(user.id);
}
function onUserLogout(user) {
  window.cartHandler.onLogout(user.id);
}