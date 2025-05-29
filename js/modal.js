document.addEventListener('DOMContentLoaded', function() {
  // Wait for app object to be available
  const checkAppReady = setInterval(() => {
    if (window.app && window.app.auth) {
      clearInterval(checkAppReady);
      initializeModal();
    }
  }, 100);
  
  // Timeout after 5 seconds to prevent infinite checking
  setTimeout(() => clearInterval(checkAppReady), 5000);
  
  function initializeModal() {
    // Get modal elements
    const loginModal = document.getElementById('loginModal');
    const loginForm = document.getElementById('modalLoginForm');
    const registerForm = document.getElementById('modalRegisterForm');
    const switchToSignUp = document.getElementById('switchToSignUp');
    const switchToLogin = document.getElementById('switchToLogin');
    const loginFooter = document.getElementById('login-footer-links');
    const signupFooter = document.getElementById('signup-footer-links');
    
    // Switch between login and signup forms
    if (switchToSignUp) {
      switchToSignUp.addEventListener('click', function(e) {
        e.preventDefault();
        loginForm.classList.add('d-none');
        registerForm.classList.remove('d-none');
        loginFooter.classList.add('d-none');
        signupFooter.classList.remove('d-none');
      });
    }
    
    if (switchToLogin) {
      switchToLogin.addEventListener('click', function(e) {
        e.preventDefault();
        registerForm.classList.add('d-none');
        loginForm.classList.remove('d-none');
        signupFooter.classList.add('d-none');
        loginFooter.classList.remove('d-none');
      });
    }
    
    // Handle login form submission
    if (loginForm) {
      loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('modalLoginEmail').value;
        const password = document.getElementById('modalLoginPassword').value;
        
        // Simple validation
        if (!email || !password) {
          alert('Please fill in all fields');
          return;
        }
        
        // Fetch user data from database to get the correct name
        fetch(`/api/find_user.php?email=${encodeURIComponent(email)}`)
          .then(response => response.json())
          .then(data => {
            let userData;
            
            if (data.success && data.data) {
              // Use data from database
              userData = {
                name: data.data.name,
                email: email,
                user_id: data.data.user_id
              };
            } else {
              // Fallback if user not found in database
              userData = {
                name: email.split('@')[0], // Use part of email as name for demo
                email: email,
                id: Date.now() // Generate a fake ID
              };
            }
            
            // Use the AuthManager to login
            window.app.auth.login(userData);
            
            // Close the modal
            const modalInstance = bootstrap.Modal.getInstance(loginModal);
            if (modalInstance) {
              modalInstance.hide();
            }
          })
          .catch(error => {
            console.error('Error fetching user data:', error);
            
            // Fallback login with basic data
            const userData = {
              name: email.split('@')[0],
              email: email,
              id: Date.now()
            };
            
            window.app.auth.login(userData);
            
            // Close the modal
            const modalInstance = bootstrap.Modal.getInstance(loginModal);
            if (modalInstance) {
              modalInstance.hide();
            }
          });
      });
    }
    
    // Handle register form submission
    if (registerForm) {
      registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const name = document.getElementById('modalRegisterName').value;
        const email = document.getElementById('modalRegisterEmail').value;
        const password = document.getElementById('modalRegisterPassword').value;
        const confirmPassword = document.getElementById('modalRegisterConfirmPassword').value;
        
        // Simple validation
        if (!name || !email || !password || !confirmPassword) {
          alert('Please fill in all fields');
          return;
        }
        
        if (password !== confirmPassword) {
          alert('Passwords do not match');
          return;
        }
        
        // Show loading state
        const submitBtn = registerForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registering...';
        
        // Send registration request to API
        fetch('/api/register.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            name: name,
            email: email,
            password: password
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Registration successful, log in the user
            const userData = {
              user_id: data.user.user_id,
              name: data.user.name,
              email: data.user.email
            };
            
            // Use the AuthManager to login
            window.app.auth.login(userData);
            
            // Close the modal
            const modalInstance = bootstrap.Modal.getInstance(loginModal);
            if (modalInstance) {
              modalInstance.hide();
            }
            
            // Show success message
            alert('Registration successful! You are now logged in.');
          } else {
            // Registration failed
            alert('Registration failed: ' + data.message);
            
            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
          }
        })
        .catch(error => {
          console.error('Error during registration:', error);
          alert('Error during registration. Please try again.');
          
          // Reset button
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        });
      });
    }
  }
});
