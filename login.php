<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/auth/db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login | DeeReel Footies</title>
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
              <h3 class="mb-0">Sign In</h3>
            </div>
            <div class="card-body">
              <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                  <?= $_SESSION['error']; ?>
                  <?php unset($_SESSION['error']); ?>
                </div>
              <?php endif; ?>
              
              <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                  <?= $_SESSION['success']; ?>
                  <?php unset($_SESSION['success']); ?>
                </div>
              <?php endif; ?>
              
              <form id="loginPageForm">
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3 form-check">
                  <input type="checkbox" class="form-check-input" id="remember" name="remember_me">
                  <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <div class="d-grid">
                  <button type="submit" class="btn btn-primary">Sign In</button>
                </div>
                <div id="loginError" class="alert alert-danger mt-3 d-none"></div>
              </form>
              
              <div class="mt-3 text-center">
                <p>Don't have an account? <a href="/signup.php">Sign Up</a></p>
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
      const loginForm = document.getElementById('loginPageForm');
      if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          const email = document.getElementById('email').value;
          const password = document.getElementById('password').value;
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
          
          // Get cart data if available
          let cartData = null;
          if (typeof CartHandler !== 'undefined' && window.cartHandler) {
            cartData = JSON.stringify(window.cartHandler.cartItems);
          }
          
          // Send login request
          const formData = new FormData();
          formData.append('email', email);
          formData.append('password', password);
          if (cartData) {
            formData.append('cart', cartData);
          }
          
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
              if (window.cartHandler && typeof window.cartHandler.handleLogin === 'function') {
                window.cartHandler.handleLogin(data.user_id);
              }
              // Save user data to localStorage
              localStorage.setItem('DRFUser', JSON.stringify(data.user));
              
              // Redirect to dashboard or previous page
              const redirectUrl = new URLSearchParams(window.location.search).get('redirect');
              window.location.href = redirectUrl || '/dashboard.php';
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
    });
  </script>
</body>
</html>