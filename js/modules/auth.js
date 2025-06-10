class AuthManager {
  constructor() {
    this.userKey = 'DRFUser';
    this.initialized = false;
  }

  init() {
    if (this.initialized) return;
    
    console.log('AuthManager initializing...');
    this.initialized = true;
    
    // Check login status on page load
    setTimeout(() => {
      this.updateUI();
    }, 100);
    
    // Listen for storage changes (for multi-tab sync)
    window.addEventListener('storage', (e) => {
      if (e.key === this.userKey) {
        console.log('Storage changed, updating UI');
        this.updateUI();
      }
    });

    // Listen for custom login events
    window.addEventListener('userLogin', () => {
      console.log('User login event received');
      this.updateUI();
    });
  }

  isLoggedIn() {
    const user = this.getCurrentUser();
    const loggedIn = user !== null && (user.user_id || user.id);
    console.log('Checking login status:', loggedIn, user);
    return loggedIn;
  }

  getCurrentUser() {
    try {
      const userData = localStorage.getItem(this.userKey);
      const user = userData ? JSON.parse(userData) : null;
      console.log('Getting current user from localStorage:', user);
      return user;
    } catch (error) {
      console.error('Error parsing user data:', error);
      return null;
    }
  }

  setUser(userData) {
    console.log('Setting user data:', userData);
    localStorage.setItem(this.userKey, JSON.stringify(userData));
    
    // Force immediate UI update
    setTimeout(() => {
      this.updateUI();
    }, 50);
    
    // Trigger custom event for other components
    window.dispatchEvent(new CustomEvent('userLogin', { detail: userData }));
  }

  logout() {
    console.log('Logging out user');
    const user = this.getCurrentUser();
    
    // Clear user data
    localStorage.removeItem(this.userKey);
    
    // Clear cart if it's user-specific
    if (user) {
      const userCartKey = `DRFCart_${user.user_id || user.id || user.email}`;
      localStorage.removeItem(userCartKey);
    }
    
    this.updateUI();
    
    // Trigger custom event
    window.dispatchEvent(new CustomEvent('userLogout'));
    
    // Redirect to home page
    window.location.href = '/index.php';
  }

  updateUI() {
    const user = this.getCurrentUser();
    const isLoggedIn = this.isLoggedIn();
    
    console.log('=== UPDATING UI ===');
    console.log('User:', user);
    console.log('Is Logged In:', isLoggedIn);
    
    // Update account dropdown
    this.updateAccountDropdown(user, isLoggedIn);
    
    // Update any user-specific elements
    this.updateUserElements(user, isLoggedIn);
    
    // Update dashboard elements if on dashboard page
    this.updateDashboardElements(user, isLoggedIn);
    
    console.log('=== UI UPDATE COMPLETE ===');
  }

  updateAccountDropdown(user, isLoggedIn) {
    console.log('Updating account dropdown...');
    
    // Try multiple selectors for the account dropdown
    const accountDropdown = document.getElementById('userAccountDropdown') || 
                           document.querySelector('[id*="userAccount"]') ||
                           document.querySelector('.nav-link[href*="account"]') ||
                           document.querySelector('a[data-bs-toggle="dropdown"]:has(i.fa-user)');
    
    const dropdownMenu = accountDropdown ? 
                        accountDropdown.nextElementSibling ||
                        document.querySelector('#userAccountDropdown + .dropdown-menu') ||
                        document.querySelector('.dropdown-menu[aria-labelledby*="userAccount"]') : null;
    
    console.log('Account dropdown element:', accountDropdown);
    console.log('Dropdown menu element:', dropdownMenu);
    
    if (!accountDropdown) {
      console.warn('Account dropdown not found! Trying alternative approach...');
      
      // Try to find any dropdown with user icon
      const userLinks = document.querySelectorAll('a:has(.fa-user), a:has(.fas.fa-user)');
      console.log('Found user links:', userLinks);
      
      userLinks.forEach((link, index) => {
        console.log(`User link ${index}:`, link);
        if (isLoggedIn && user) {
          link.innerHTML = `<i class="fas fa-user"></i> ${user.name}`;
        } else {
          link.innerHTML = `<i class="fas fa-user"></i> Account`;
        }
      });
      
      return;
    }

    if (isLoggedIn && user) {
      console.log('Updating for logged in user:', user.name);
      
      // Update dropdown text to show user name
      accountDropdown.innerHTML = `<i class="fas fa-user"></i> ${user.name || 'Account'}`;
      
      if (dropdownMenu) {
        // Update dropdown menu for logged-in user
        dropdownMenu.innerHTML = `
          <li><h6 class="dropdown-header">Welcome, ${user.name}!</h6></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
          <li><a class="dropdown-item" href="/dashboard.php#orders"><i class="fas fa-box me-2"></i>My Orders</a></li>
          <li><a class="dropdown-item" href="/dashboard.php#wishlist"><i class="fas fa-heart me-2"></i>Wishlist</a></li>
          <li><a class="dropdown-item" href="/dashboard.php#designs"><i class="fas fa-palette me-2"></i>My Designs</a></li>
          <li><a class="dropdown-item" href="/dashboard.php#personal"><i class="fas fa-user-edit me-2"></i>Account Settings</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a href="#" id="navLogoutBtn" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        `;
        
        // Add logout event listener
        const logoutBtn = document.getElementById('navLogoutBtn');
        if (logoutBtn) {
          logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
              this.logout();
            }
          });
        }
      }
    } else {
      console.log('Updating for guest user');
      
      // Update dropdown for guest user
      accountDropdown.innerHTML = `<i class="fas fa-user"></i> Account`;
      
      if (dropdownMenu) {
        dropdownMenu.innerHTML = `
          <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fas fa-sign-in-alt me-2"></i>Sign In / Sign Up</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="/dashboard.php"><i class="fas fa-user me-2"></i>Guest Account</a></li>
        `;
      }
    }
  }

  updateUserElements(user, isLoggedIn) {
    console.log('Updating user elements...');
    
    // Update any elements with user-specific data
    const userNameElements = document.querySelectorAll('[data-user-name]');
    const userEmailElements = document.querySelectorAll('[data-user-email]');
    const loggedInElements = document.querySelectorAll('[data-logged-in-only]');
    const guestOnlyElements = document.querySelectorAll('[data-guest-only]');

    console.log('Found user name elements:', userNameElements.length);
    console.log('Found user email elements:', userEmailElements.length);

    userNameElements.forEach(el => {
      const name = isLoggedIn && user ? user.name : '';
      el.textContent = name;
      console.log('Updated user name element to:', name);
    });

    userEmailElements.forEach(el => {
      const email = isLoggedIn && user ? user.email : '';
      el.textContent = email;
      console.log('Updated user email element to:', email);
    });

    loggedInElements.forEach(el => {
      el.style.display = isLoggedIn ? 'block' : 'none';
    });

    guestOnlyElements.forEach(el => {
      el.style.display = isLoggedIn ? 'none' : 'block';
    });
  }

  updateDashboardElements(user, isLoggedIn) {
    // Update dashboard-specific elements
    const dashboardUsername = document.getElementById('dashboard-username');
    if (dashboardUsername && isLoggedIn && user) {
      dashboardUsername.textContent = user.name;
      console.log('Updated dashboard username to:', user.name);
    }
  }

  // Handle login form submission
  handleLogin(formData) {
    console.log('Handling login with data:', formData);
    
    return fetch('/auth/login.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(formData)
    })
    .then(response => {
      console.log('Login response status:', response.status);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      console.log('Login response data:', data);
      if (data.success) {
        this.setUser(data.user);
        return { success: true, user: data.user };
      } else {
        throw new Error(data.message || 'Login failed');
      }
    })
    .catch(error => {
      console.error('Login fetch error:', error);
      throw error;
    });
  }

  // Handle registration form submission
  handleRegister(formData) {
    console.log('Handling registration with data:', formData);
    
    return fetch('/auth/register.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(formData)
    })
    .then(response => {
      console.log('Register response status:', response.status);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      console.log('Register response data:', data);
      if (data.success) {
        this.setUser(data.user);
        return { success: true, user: data.user };
      } else {
        throw new Error(data.message || 'Registration failed');
      }
    })
    .catch(error => {
      console.error('Registration fetch error:', error);
      throw error;
    });
  }

  // Legacy method names for compatibility
  login(formData) {
    return this.handleLogin(formData);
  }

  register(formData) {
    return this.handleRegister(formData);
  }
}

// Only create instance if it doesn't exist
if (!window.authManager) {
  console.log('Creating AuthManager instance...');
  window.authManager = new AuthManager();
  
  // Make it available to other scripts
  if (typeof window.app === 'undefined') {
    window.app = {};
  }
  window.app.auth = window.authManager;
  
  console.log('AuthManager setup complete');
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
  console.log('DOM ready - Auth manager initializing');
  if (window.authManager) {
    window.authManager.init();
  }
});

// Also try to update UI after a short delay
setTimeout(() => {
  if (window.authManager) {
    console.log('Delayed UI update');
    window.authManager.updateUI();
  }
}, 1000);