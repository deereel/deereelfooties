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
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="rememberMe">
            <label class="form-check-label" for="rememberMe">Remember me</label>
          </div>
          <div class="alert alert-danger d-none" id="loginError"></div>
          <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>
        
        <!-- Sign Up Form (Initially Hidden) -->
        <form id="signupForm" class="needs-validation d-none" novalidate>
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
        
        <div class="text-center my-3">
          <span>or</span>
        </div>
        
        <div class="mt-3 text-center">
          <p id="loginText">Don't have an account? <a href="#" id="showSignupForm">Sign up</a></p>
          <p id="signupText" class="d-none">Already have an account? <a href="#" id="showLoginForm">Sign in</a></p>
        </div>
      </div>
    </div>
  </div>
</div>