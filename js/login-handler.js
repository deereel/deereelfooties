// login-handler.js - Handle login events and cart synchronization

document.addEventListener('DOMContentLoaded', function() {
  // Listen for login form submission
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      handleLogin();
    });
  }
  
  // Listen for logout clicks
  document.querySelectorAll('.logout-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      handleLogout();
    });
  });
  
  // On page load, if user is logged in, clear guest cart from localStorage to avoid mixing
  const isLoggedIn = document.body.hasAttribute('data-user-id');
  if (isLoggedIn) {
    // Optionally keep guest cart in localStorage for restoration on logout
    // localStorage.removeItem('DRFCart');
  }
});

// Handle login
async function handleLogin() {
  const email = document.getElementById('loginEmail').value;
  const password = document.getElementById('loginPassword').value;
  const errorElement = document.getElementById('loginError');
  
  // Reset previous error
  if (errorElement) {
    errorElement.classList.add('d-none');
  }
  
  // Validate form
  const loginForm = document.getElementById('loginForm');
  if (!loginForm.checkValidity()) {
    loginForm.classList.add('was-validated');
    return;
  }
  
  try {
    // Cart functionality has been removed
    
    // Send login request
    const formData = new FormData();
    formData.append('email', email);
    formData.append('password', password);
    
    const response = await fetch('/auth/login_process.php', {
      method: 'POST',
      body: formData
    });
    
    const data = await response.json();
    
    if (data.success) {
      console.log('Login successful:', data);
      
      // Save user data to localStorage
      localStorage.setItem('DRFUser', JSON.stringify(data.user));
      localStorage.setItem('isLoggedIn', 'true');
      sessionStorage.setItem('user_id', data.user.user_id || data.user.id);
      
      // Set user data attribute
      document.body.setAttribute('data-user-id', data.user.user_id || data.user.id);
      
      // Close modal
      const loginModal = document.getElementById('loginModal');
      if (loginModal && typeof bootstrap !== 'undefined') {
        const bsModal = bootstrap.Modal.getInstance(loginModal);
        if (bsModal) bsModal.hide();
      }
      
      // Cart functionality has been removed
      
      // Dispatch login event
      document.dispatchEvent(new CustomEvent('userLoggedIn'));
      
      // Reload page after a short delay to allow cart sync
      setTimeout(() => {
        window.location.reload();
      }, 500);
    } else {
      // Show error
      if (errorElement) {
        errorElement.textContent = data.message || 'Login failed. Please try again.';
        errorElement.classList.remove('d-none');
      }
    }
  } catch (error) {
    console.error('Login error:', error);
    if (errorElement) {
      errorElement.textContent = 'An error occurred. Please try again.';
      errorElement.classList.remove('d-none');
    }
  }
}

async function handleLogout() {
  try {
    // Save cart before logout
    if (window.cartHandler && window.cartHandler.cartItems.length > 0) {
      await fetch('/auth/logout.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ cart_items: window.cartHandler.cartItems })
      });
    } else {
      await fetch('/auth/logout.php');
    }
    
    // Clear user data from storage
    localStorage.removeItem('DRFUser');
    localStorage.removeItem('isLoggedIn');
    sessionStorage.removeItem('user_id');
    sessionStorage.removeItem('DRFServerCart');
    
    // Remove user data attribute
    document.body.removeAttribute('data-user-id');
    
    // Reload page
    window.location.reload();
  } catch (error) {
    console.error('Logout error:', error);
    window.location.reload(); // Reload anyway
  }
}
