<!-- Modal for Sign In / Sign Up -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="loginModalLabel">Sign In</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <!-- Sign In Form -->
        <form id="modalLoginForm">
          <div class="mb-3">
            <label for="modalLoginEmail" class="form-label">Email address</label>
            <input type="email" class="form-control" id="modalLoginEmail" required>
          </div>
          <div class="mb-3">
            <label for="modalLoginPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="modalLoginPassword" required>
          </div>
          <button type="submit" class="btn-primary w-100 mb-3">Sign In</button>
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
          <button type="submit" class="btn-success w-100 mb-3">Create Account</button>
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
        </div>
        
        <!-- Signup Footer (shown when signup form is active) -->
        <div id="signup-footer-links" class="text-center d-none">
          <p class="mb-0">Already have an account? 
            <a href="#" id="switchToLogin" class="text-decoration-none">Sign in</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>