<?php
// Check if the user is already logged in
$isLoggedIn = isset($_SESSION['user']);
?>

<!-- Account Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Sign In</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Login Form -->
        <form id="loginForm" class="needs-validation" novalidate>
          <div class="mb-3">
            <label for="loginEmail" class="form-label">Email address</label>
            <input type="email" class="form-control" id="loginEmail" required>
            <div class="invalid-feedback">
              Please enter a valid email address.
            </div>
          </div>
          <div class="mb-3">
            <label for="loginPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="loginPassword" required>
            <div class="invalid-feedback">
              Please enter your password.
            </div>
          </div>
<<<<<<< HEAD
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="rememberMe">
            <label class="form-check-label" for="rememberMe">Remember me</label>
          </div>
          <div class="alert alert-danger d-none" id="loginError"></div>
          <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>
        
        <div class="text-center my-3">
          <span>or</span>
=======
          <button type="submit" class="btn btn-primary w-100 mb-3">Sign In</button>
        </form>

        <!-- Sign Up Form (Initially Hidden) -->
        <form id="modalRegisterForm" class="d-none">
          <div class="mb-3">
            <label for="modalRegisterName" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="modalRegisterName" required>
          </div>
          <div class="mb-3">
            <label for="modalRegisterEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="modalRegisterEmail" required>
          </div>
          <div class="mb-3">
            <label for="modalRegisterPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="modalRegisterPassword" required minlength="6">
          </div>
          <div class="mb-3">
            <label for="modalRegisterConfirmPassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="modalRegisterConfirmPassword" required>
          </div>
          <button type="submit" class="btn btn-success w-100 mb-3">Create Account</button>
        </form>

        <!-- Social Login Buttons -->
        <div class="text-center">
          <button type="button" id="googleLoginBtn" class="btn btn-outline-dark w-100 mb-2">
            <i class="fab fa-google me-2"></i> Continue with Google
          </button>
          <button type="button" id="facebookLoginBtn" class="btn btn-outline-primary w-100">
            <i class="fab fa-facebook me-2"></i> Continue with Facebook
          </button>
        </div>

      </div>
      
      <!-- Modal Footer with switching links -->
      <div class="modal-footer border-0 justify-content-center">
        <!-- Login Footer (shown when login form is active) -->
        <div id="login-footer-links" class="text-center">
          <p class="mb-0">Don't have an account? 
            <a href="#" id="switchToSignUp" class="text-decoration-none">Sign up</a>
          </p>
>>>>>>> parent of f36b17c (checkout page)
        </div>
        
        <!-- Google Sign-In Button -->
        <div class="d-grid gap-2">
          <div id="g_id_onload"
               data-client_id="YOUR_GOOGLE_CLIENT_ID"
               data-context="signin"
               data-ux_mode="popup"
               data-callback="handleGoogleSignIn"
               data-auto_prompt="false">
          </div>

          <div class="g_id_signin"
               data-type="standard"
               data-shape="rectangular"
               data-theme="outline"
               data-text="signin_with"
               data-size="large"
               data-logo_alignment="left">
          </div>
        </div>
        
        <div class="mt-3 text-center">
          <p>Don't have an account? <a href="#" id="showSignupForm">Sign up</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<<<<<<< HEAD

<!-- Signup Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="signupModalLabel">Create Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Signup Form -->
        <form id="signupForm" class="needs-validation" novalidate>
          <div class="mb-3">
            <label for="signupName" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="signupName" required>
            <div class="invalid-feedback">
              Please enter your name.
            </div>
          </div>
          <div class="mb-3">
            <label for="signupEmail" class="form-label">Email address</label>
            <input type="email" class="form-control" id="signupEmail" required>
            <div class="invalid-feedback">
              Please enter a valid email address.
            </div>
          </div>
          <div class="mb-3">
            <label for="signupPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="signupPassword" required minlength="8">
            <div class="invalid-feedback">
              Password must be at least 8 characters.
            </div>
          </div>
          <div class="mb-3">
            <label for="signupConfirmPassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="signupConfirmPassword" required>
            <div class="invalid-feedback">
              Passwords do not match.
            </div>
          </div>
          <div class="alert alert-danger d-none" id="signupError"></div>
          <button type="submit" class="btn btn-primary w-100">Create Account</button>
        </form>
        
        <div class="mt-3 text-center">
          <p>Already have an account? <a href="#" id="showLoginForm">Sign in</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Get modal elements
  const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
  const signupModal = new bootstrap.Modal(document.getElementById('signupModal'));
  
  // Switch between login and signup forms
  document.getElementById('showSignupForm').addEventListener('click', function(e) {
    e.preventDefault();
    loginModal.hide();
    setTimeout(() => {
      signupModal.show();
    }, 500);
  });
  
  document.getElementById('showLoginForm').addEventListener('click', function(e) {
    e.preventDefault();
    signupModal.hide();
    setTimeout(() => {
      loginModal.show();
    }, 500);
  });
  
  // Handle login form submission
  document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const errorElement = document.getElementById('loginError');
    
    // Reset previous error
    errorElement.classList.add('d-none');
    
    // Validate form
    if (!form.checkValidity()) {
      form.classList.add('was-validated');
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
      // Check if response is valid JSON
      const contentType = response.headers.get('content-type');
      if (contentType && contentType.includes('application/json')) {
        return response.json();
      } else {
        // Handle non-JSON response
        return response.text().then(text => {
          console.error('Non-JSON response:', text);
          throw new Error('Server returned non-JSON response');
        });
      }
    })
    .then(data => {
      if (data.success) {
        console.log('Login successful:', data);
        
        // Ensure user data has user_id
        if (data.user && data.user.id && !data.user.user_id) {
          data.user.user_id = data.user.id;
        }
        
        // Save user data to app
        if (window.app && window.app.auth) {
          window.app.auth.login(data.user);
        }
        
        // Close modal
        loginModal.hide();
        
        // Redirect if specified
        if (data.redirect) {
          window.location.href = data.redirect;
        } else {
          // Reload page
          window.location.reload();
        }
      } else {
        // Show error
        errorElement.textContent = data.error || 'Login failed. Please try again.';
        errorElement.classList.remove('d-none');
      }
    })
    .catch(error => {
      console.error('Login error:', error);
      errorElement.textContent = 'An error occurred. Please try again.';
      errorElement.classList.remove('d-none');
    });
  });
  
  // Handle signup form submission
  document.getElementById('signupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const name = document.getElementById('signupName').value;
    const email = document.getElementById('signupEmail').value;
    const password = document.getElementById('signupPassword').value;
    const confirmPassword = document.getElementById('signupConfirmPassword').value;
    const errorElement = document.getElementById('signupError');
    
    // Reset previous error
    errorElement.classList.add('d-none');
    
    // Check if passwords match
    if (password !== confirmPassword) {
      document.getElementById('signupConfirmPassword').setCustomValidity('Passwords do not match');
    } else {
      document.getElementById('signupConfirmPassword').setCustomValidity('');
    }
    
    // Validate form
    if (!form.checkValidity()) {
      form.classList.add('was-validated');
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
      // Check if response is valid JSON
      const contentType = response.headers.get('content-type');
      if (contentType && contentType.includes('application/json')) {
        return response.json();
      } else {
        // Handle non-JSON response
        return response.text().then(text => {
          console.error('Non-JSON response:', text);
          throw new Error('Server returned non-JSON response');
        });
      }
    })
    .then(data => {
      if (data.success) {
        console.log('Signup successful:', data);
        
        // Ensure user data has user_id
        if (data.user && data.user.id && !data.user.user_id) {
          data.user.user_id = data.user.id;
        }
        
        // Save user data to app
        if (window.app && window.app.auth) {
          window.app.auth.login(data.user);
        }
        
        // Close modal
        signupModal.hide();
        
        // Redirect if specified
        if (data.redirect) {
          window.location.href = data.redirect;
        } else {
          // Reload page
          window.location.reload();
        }
      } else {
        // Show error
        errorElement.textContent = data.error || 'Signup failed. Please try again.';
        errorElement.classList.remove('d-none');
      }
    })
    .catch(error => {
      console.error('Signup error:', error);
      errorElement.textContent = 'An error occurred. Please try again.';
      errorElement.classList.remove('d-none');
    });
  });
  
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
    .then(response => {
      // Check if response is valid JSON
      const contentType = response.headers.get('content-type');
      if (contentType && contentType.includes('application/json')) {
        return response.json();
      } else {
        // Handle non-JSON response
        return response.text().then(text => {
          console.error('Non-JSON response:', text);
          throw new Error('Server returned non-JSON response');
        });
      }
    })
    .then(data => {
      if (data.success) {
        console.log('Google login successful:', data);
        
        // Ensure user data has user_id
        if (data.user && data.user.id && !data.user.user_id) {
          data.user.user_id = data.user.id;
        }
        
        // Save user data to app
        if (window.app && window.app.auth) {
          window.app.auth.login(data.user);
        }
        
        // Close modal
        loginModal.hide();
        
        // Redirect if specified
        if (data.redirect) {
          window.location.href = data.redirect;
        } else {
          // Reload page
          window.location.reload();
        }
      } else {
        // Show error
        const errorElement = document.getElementById('loginError');
        errorElement.textContent = data.error || 'Google login failed. Please try again.';
        errorElement.classList.remove('d-none');
      }
    })
    .catch(error => {
      console.error('Google login error:', error);
      const errorElement = document.getElementById('loginError');
      errorElement.textContent = 'An error occurred. Please try again.';
      errorElement.classList.remove('d-none');
    });
  };
});
</script>
=======
>>>>>>> parent of f36b17c (checkout page)
