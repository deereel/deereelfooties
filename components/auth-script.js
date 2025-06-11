// Auth script - Handle login and signup
document.addEventListener('DOMContentLoaded', function() {
  // Handle login form submission
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const email = document.getElementById('loginEmail').value;
      const password = document.getElementById('loginPassword').value;
      
      fetch('/auth/login.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email, password })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Store user data in localStorage
          localStorage.setItem('DRFUser', JSON.stringify(data.user));
          
          // Close modal
          const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
          if (loginModal) loginModal.hide();
          
          // Reload page
          window.location.reload();
        } else {
          // Show error message
          const errorMsg = document.getElementById('loginErrorMsg');
          if (errorMsg) {
            errorMsg.textContent = data.message || 'Login failed';
            errorMsg.classList.remove('d-none');
          }
        }
      })
      .catch(error => {
        console.error('Login error:', error);
        const errorMsg = document.getElementById('loginErrorMsg');
        if (errorMsg) {
          errorMsg.textContent = 'An error occurred. Please try again.';
          errorMsg.classList.remove('d-none');
        }
      });
    });
  }
  
  // Handle signup form submission
  const signupForm = document.getElementById('signupForm');
  if (signupForm) {
    signupForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const name = document.getElementById('signupName').value;
      const email = document.getElementById('signupEmail').value;
      const password = document.getElementById('signupPassword').value;
      const confirmPassword = document.getElementById('signupConfirmPassword').value;
      
      if (password !== confirmPassword) {
        const errorMsg = document.getElementById('signupErrorMsg');
        if (errorMsg) {
          errorMsg.textContent = 'Passwords do not match';
          errorMsg.classList.remove('d-none');
        }
        return;
      }
      
      fetch('/auth/signup.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ name, email, password })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Store user data in localStorage
          localStorage.setItem('DRFUser', JSON.stringify(data.user));
          
          // Close modal
          const loginModal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
          if (loginModal) loginModal.hide();
          
          // Reload page
          window.location.reload();
        } else {
          // Show error message
          const errorMsg = document.getElementById('signupErrorMsg');
          if (errorMsg) {
            errorMsg.textContent = data.message || 'Signup failed';
            errorMsg.classList.remove('d-none');
          }
        }
      })
      .catch(error => {
        console.error('Signup error:', error);
        const errorMsg = document.getElementById('signupErrorMsg');
        if (errorMsg) {
          errorMsg.textContent = 'An error occurred. Please try again.';
          errorMsg.classList.remove('d-none');
        }
      });
    });
  }
  
  // Handle tab switching in login modal
  const loginTab = document.getElementById('login-tab');
  const signupTab = document.getElementById('signup-tab');
  const loginPane = document.getElementById('login-pane');
  const signupPane = document.getElementById('signup-pane');
  
  if (loginTab && signupTab && loginPane && signupPane) {
    loginTab.addEventListener('click', function(e) {
      e.preventDefault();
      loginTab.classList.add('active');
      signupTab.classList.remove('active');
      loginPane.classList.add('show', 'active');
      signupPane.classList.remove('show', 'active');
    });
    
    signupTab.addEventListener('click', function(e) {
      e.preventDefault();
      signupTab.classList.add('active');
      loginTab.classList.remove('active');
      signupPane.classList.add('show', 'active');
      loginPane.classList.remove('show', 'active');
    });
  }
});