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
  
  // Handle cart separation between guest and logged-in users
  const isLoggedIn = document.body.hasAttribute('data-user-id');
  if (isLoggedIn) {
    // If user is logged in, clear any guest cart
    localStorage.removeItem('DRFCart');
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
      
      // Clear guest cart to prevent mixing with user cart
      localStorage.removeItem('DRFCart');
      
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

// Handle logout
async function handleLogout() {
  try {
    // Clear user data from storage
    localStorage.removeItem('DRFUser');
    localStorage.removeItem('isLoggedIn');
    sessionStorage.removeItem('user_id');
    sessionStorage.removeItem('DRFServerCart');
    
    // Clear any cart data to ensure clean separation
    localStorage.removeItem('DRFCart');
    
    // Remove user data attribute
    document.body.removeAttribute('data-user-id');
    
    // Call server logout
    await fetch('/auth/logout.php');
    
    // Reload page
    window.location.reload();
  } catch (error) {
    console.error('Logout error:', error);
    window.location.reload(); // Reload anyway
  }
}