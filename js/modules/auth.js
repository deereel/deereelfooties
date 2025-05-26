export class AuthManager {
  constructor() {
    this.userKey = 'DRFUser';
    this.currentUser = null;
  }

  init() {
    console.log('Initializing Auth Manager');
    this.loadCurrentUser();
    this.updateUserIcon();
    this.initModalSwitching();
    this.initForms();
    this.initUserIconClick();
  }

  loadCurrentUser() {
    const userData = localStorage.getItem(this.userKey);
    if (userData) {
      try {
        this.currentUser = JSON.parse(userData);
      } catch (e) {
        console.error('Error parsing user data:', e);
        localStorage.removeItem(this.userKey);
      }
    }
  }

  updateUserIcon() {
    if (this.currentUser && this.currentUser.name) {
      const userIcon = document.getElementById('userIcon');
      if (userIcon) {
        userIcon.setAttribute('title', `Welcome, ${this.currentUser.name}`);
      }
    }
  }

  initUserIconClick() {
    const userIcon = document.getElementById('userIcon');
    if (userIcon) {
      userIcon.addEventListener('click', () => {
        const loginModal = document.getElementById('loginModal');
        if (loginModal) {
          const modal = new bootstrap.Modal(loginModal);
          modal.show();
        }
      });
    }
  }

  initModalSwitching() {
    const switchToSignUp = document.getElementById('switchToSignUp');
    const switchToLogin = document.getElementById('switchToLogin');

    if (switchToSignUp) {
      switchToSignUp.addEventListener('click', (e) => {
        e.preventDefault();
        this.showSignupForm();
      });
    }

    if (switchToLogin) {
      switchToLogin.addEventListener('click', (e) => {
        e.preventDefault();
        this.showLoginForm();
      });
    }

    // Reset to login form when modal opens
    const loginModal = document.getElementById('loginModal');
    if (loginModal) {
      loginModal.addEventListener('show.bs.modal', () => {
        this.showLoginForm();
        this.clearForms();
      });
    }
  }

  showLoginForm() {
    const loginForm = document.getElementById('modalLoginForm');
    const signupForm = document.getElementById('modalRegisterForm');
    const loginFooter = document.getElementById('login-footer-links');
    const signupFooter = document.getElementById('signup-footer-links');
    const modalTitle = document.getElementById('loginModalLabel');

    if (loginForm) loginForm.classList.remove('d-none');
    if (signupForm) signupForm.classList.add('d-none');
    if (loginFooter) loginFooter.classList.remove('d-none');
    if (signupFooter) signupFooter.classList.add('d-none');
    if (modalTitle) modalTitle.textContent = 'Sign In';
  }

  showSignupForm() {
    const loginForm = document.getElementById('modalLoginForm');
    const signupForm = document.getElementById('modalRegisterForm');
    const loginFooter = document.getElementById('login-footer-links');
    const signupFooter = document.getElementById('signup-footer-links');
    const modalTitle = document.getElementById('loginModalLabel');

    if (signupForm) signupForm.classList.remove('d-none');
    if (loginForm) loginForm.classList.add('d-none');
    if (signupFooter) signupFooter.classList.remove('d-none');
    if (loginFooter) loginFooter.classList.add('d-none');
    if (modalTitle) modalTitle.textContent = 'Sign Up';
  }

  initForms() {
    this.initLoginForm();
    this.initSignupForm();
    this.initSocialButtons();
  }

  initLoginForm() {
    const loginForm = document.getElementById('modalLoginForm');
    if (!loginForm) return;

    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const email = document.getElementById('modalLoginEmail').value.trim();
      const password = document.getElementById('modalLoginPassword').value;
      const submitBtn = loginForm.querySelector('button[type="submit"]');

      if (!email || !password) {
        alert('Please fill in all fields');
        return;
      }

      // Show loading
      const originalText = submitBtn.textContent;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing in...';
      submitBtn.disabled = true;

      try {
        const response = await fetch('/auth/login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
        });

        const data = await response.json();

        if (data.success) {
          localStorage.setItem(this.userKey, JSON.stringify(data.user));
          this.currentUser = data.user;
          
          // Close modal
          const modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
          if (modal) modal.hide();
          
          alert('Login successful!');
          location.reload();
        } else {
          alert(data.error || 'Login failed');
        }
      } catch (error) {
        console.error('Login error:', error);
        alert('Login failed. Please try again.');
      } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
      }
    });
  }

  initSignupForm() {
    const signupForm = document.getElementById('modalRegisterForm');
    if (!signupForm) return;

    signupForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const name = document.getElementById('modalRegisterName').value.trim();
      const email = document.getElementById('modalRegisterEmail').value.trim();
      const password = document.getElementById('modalRegisterPassword').value;
      const confirmPassword = document.getElementById('modalRegisterConfirmPassword').value;
      const submitBtn = signupForm.querySelector('button[type="submit"]');

      if (!name || !email || !password || !confirmPassword) {
        alert('Please fill in all fields');
        return;
      }

      if (password !== confirmPassword) {
        alert('Passwords do not match');
        return;
      }

      if (password.length < 6) {
        alert('Password must be at least 6 characters');
        return;
      }

      // Show loading
      const originalText = submitBtn.textContent;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating account...';
      submitBtn.disabled = true;

      try {
        const response = await fetch('/auth/signup.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}&confirm_password=${encodeURIComponent(confirmPassword)}`
        });

        const data = await response.json();

        if (data.success) {
          alert('Account created successfully! You can now sign in.');
          this.showLoginForm();
          this.clearForms();
          
          // Pre-fill email
          document.getElementById('modalLoginEmail').value = email;
        } else {
          alert(data.error || 'Signup failed');
        }
      } catch (error) {
        console.error('Signup error:', error);
        alert('Signup failed. Please try again.');
      } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
      }
    });
  }

  initSocialButtons() {
    const googleBtn = document.getElementById('googleLoginBtn');
    const facebookBtn = document.getElementById('facebookLoginBtn');

    if (googleBtn) {
      googleBtn.addEventListener('click', () => {
        window.location.href = '/auth/google-login.php';
      });
    }

    if (facebookBtn) {
      facebookBtn.addEventListener('click', () => {
        window.location.href = '/auth/facebook-login.php';
      });
    }
  }

  clearForms() {
    const loginForm = document.getElementById('modalLoginForm');
    const signupForm = document.getElementById('modalRegisterForm');
    
    if (loginForm) loginForm.reset();
    if (signupForm) signupForm.reset();
  }

  logout() {
    localStorage.removeItem(this.userKey);
    this.currentUser = null;
    alert('Logged out successfully');
    location.reload();
  }

  isLoggedIn() {
    return !!this.currentUser;
  }

  getCurrentUser() {
    return this.currentUser;
  }
}