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
          <form id="loginForm">
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign In</button>
          </form>
          <hr>
          <p class="text-center">Don't have an account? <a href="#" id="switchToSignUp">Sign Up</a></p>

          <!-- Sign Up Form (hidden by default) -->
          <form id="signUpForm" class="d-none" method="POST" action="/auth/signup.php">
            <div class="mb-3">
              <label for="newEmail" class="form-label">Email address</label>
              <input type="email" class="form-control" id="newEmail" name="email" required>
            </div>
            <div class="mb-3">
              <label for="newPassword" class="form-label">Password</label>
              <input type="password" class="form-control" id="newPassword" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
          </form>

        </div>
      </div>
    </div>
  </div>