<!-- Modal for Sign In / Sign Up -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Sign In / Sign Up</h5>
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
          <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>

        <hr>
        <p class="text-center">Don't have an account? <a href="#" id="switchToSignUp">Sign Up</a></p>

        <!-- Sign Up Form -->
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
            <input type="password" class="form-control" id="modalRegisterPassword" required>
          </div>
          <div class="mb-3">
            <label for="modalRegisterConfirmPassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="modalRegisterConfirmPassword" required>
          </div>
          <button type="submit" class="btn btn-success w-100">Create Account</button>
        </form>

        <div class="text-center mt-3">
          <button class="btn btn-outline-dark w-100" onclick="googleLogin()">
            <i class="fab fa-google me-2"></i> Continue with Google
          </button>
        </div>
        <div class="text-center mt-2">
          <button class="btn btn-outline-dark w-100" onclick="facebookLogin()">
            <i class="fab fa-facebook me-2"></i> Continue with Facebook
          </button>

      </div>
    </div>
  </div>
</div>
