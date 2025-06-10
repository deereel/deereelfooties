// Login Modal Handler
document.addEventListener('DOMContentLoaded', function() {
  console.log('Login modal script loaded');
  
  // Get modal elements
  const loginModal = document.getElementById('loginModal');
  if (!loginModal) {
    console.log('Login modal not found');
    return;
  }
  
  const loginForm = document.getElementById('loginForm');
  const signupForm = document.getElementById('signupForm');
  const loginText = document.getElementById('loginText');
  const signupText = document.getElementById('signupText');
  const modalTitle = loginModal.querySelector('.modal-title');
  
  // Switch to signup form
  const showSignupForm = document.getElementById('showSignupForm');
  if (showSignupForm) {
    showSignupForm.addEventListener('click', function(e) {
      e.preventDefault();
      loginForm.classList.add('d-none');
      signupForm.classList.remove('d-none');
      loginText.classList.add('d-none');
      signupText.classList.remove('d-none');
      modalTitle.textContent = 'Create Account';
    });
  }
  
  // Switch to login form
  const showLoginForm = document.getElementById('showLoginForm');
  if (showLoginForm) {
    showLoginForm.addEventListener('click', function(e) {
      e.preventDefault();
      signupForm.classList.add('d-none');
      loginForm.classList.remove('d-none');
      signupText.classList.add('d-none');
      loginText.classList.remove('d-none');
      modalTitle.textContent = 'Sign In';
    });
  }
  
  // Handle login form submission
  
    if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log('Login form submitted');
        
        const email = document.getElementById('loginEmail').value.trim();
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
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Signing in...';
        submitBtn.disabled = true;
        
        // Send login request directly
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
            const bsModal = bootstrap.Modal.getInstance(loginModal);
            if (bsModal) bsModal.hide();
            
            // Redirect to dashboard immediately
            window.location.href = '/dashboard.php';
            
            // Update UI immediately
            document.querySelectorAll('.logged-in').forEach(el => {
            el.classList.remove('d-none');
            });
            document.querySelectorAll('.logged-out').forEach(el => {
            el.classList.add('d-none');
            });
            document.querySelectorAll('.user-name').forEach(el => {
            el.textContent = data.user.name;
            });
            
            // Reload page after a short delay
            setTimeout(() => window.location.reload(), 300);
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
        })
        .finally(() => {
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        });
    });
    }
  
  // Handle signup form submission
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
          const bsModal = bootstrap.Modal.getInstance(loginModal);
          if (bsModal) bsModal.hide();
          
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
  
  // Handle Google Sign-In
  window.handleGoogleSignIn = function(response) {
    console.log('Google sign-in response:', response);
    
    // Send token to server for verification
    fetch('/auth/google-login.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ credential: response.credential })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log('Google login successful:', data);
        
        // Save user data to localStorage
        localStorage.setItem('DRFUser', JSON.stringify(data.user));
        
        // Close modal
        const bsModal = bootstrap.Modal.getInstance(loginModal);
        if (bsModal) bsModal.hide();
        
        // Reload page
        window.location.reload();
      } else {
        // Show error
        const errorElement = document.getElementById('loginError');
        if (errorElement) {
          errorElement.textContent = data.error || 'Google login failed. Please try again.';
          errorElement.classList.remove('d-none');
        }
      }
    })
    .catch(error => {
      console.error('Google login error:', error);
      const errorElement = document.getElementById('loginError');
      if (errorElement) {
        errorElement.textContent = 'An error occurred. Please try again.';
        errorElement.classList.remove('d-none');
      }
    });
  };
});