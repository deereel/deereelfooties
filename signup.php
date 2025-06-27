<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Signup | Handcrafted Luxury Shoes for Men and Women</title>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>
</head>

<body class="bg-background">

  <!-- Main Content -->
  <main>
    <div class="container my-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="mb-0">Create Account</h3>
            </div>
            <div class="card-body">
              <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                  <?= $_SESSION['error']; ?>
                  <?php unset($_SESSION['error']); ?>
                </div>
              <?php endif; ?>
              
              <form id="signupPageForm">
                <div class="mb-3">
                  <label for="name" class="form-label">Full Name</label>
                  <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" name="password" required minlength="6">
                  <div class="form-text">Password must be at least 6 characters long</div>
                </div>
                <div class="mb-3">
                  <label for="confirmPassword" class="form-label">Confirm Password</label>
                  <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                </div>
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary">Create Account</button>
                </div>
                <div id="signupError" class="alert alert-danger mt-3 d-none"></div>
              </form>
              
              <div class="mt-3 text-center">
                <p>Already have an account? <a href="/login.php">Sign In</a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const signupForm = document.getElementById('signupPageForm');
      if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const name = document.getElementById('name').value;
          const email = document.getElementById('email').value;
          const password = document.getElementById('password').value;
          const confirmPassword = document.getElementById('confirmPassword').value;
          const errorElement = document.getElementById('signupError');
          
          // Reset previous error
          if (errorElement) {
            errorElement.classList.add('d-none');
          }
          
          // Check if passwords match
          if (password !== confirmPassword) {
            if (errorElement) {
              errorElement.textContent = 'Passwords do not match';
              errorElement.classList.remove('d-none');
            }
            return;
          }
          
          // Validate form
          if (!signupForm.checkValidity()) {
            signupForm.classList.add('was-validated');
            return;
          }
          
          // Get cart data if available
          let cartData = null;
          if (typeof CartHandler !== 'undefined' && window.cartHandler) {
            cartData = JSON.stringify(window.cartHandler.cartItems);
          }
          
          // Send signup request
          const formData = new FormData();
          formData.append('name', name);
          formData.append('email', email);
          formData.append('password', password);
          if (cartData) {
            formData.append('cart', cartData);
          }
          
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
              
              // Redirect to dashboard
              window.location.href = '/dashboard.php';
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
    });
  </script>
</body>
</html>