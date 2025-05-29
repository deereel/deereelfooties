export class AuthManager {
  constructor() {
    this.userKey = 'DRFUser';
    this.currentUser = null;
  }

  init() {
    console.log('Initializing Auth Manager');
    this.loadCurrentUser();
    this.updateUserDisplay();
    this.setupLogoutHandler();
  }

  loadCurrentUser() {
    const userData = localStorage.getItem(this.userKey);
    if (userData) {
      try {
        this.currentUser = JSON.parse(userData);
        console.log('Loaded user from localStorage:', this.currentUser);
        
        // If we have an email but no user_id, try to fetch it
        if (this.currentUser.email && !this.currentUser.user_id) {
          this.fetchUserIdByEmail(this.currentUser.email);
        }
      } catch (e) {
        console.error('Error parsing user data:', e);
        localStorage.removeItem(this.userKey);
      }
    }
  }

  fetchUserIdByEmail(email) {
    fetch(`/api/find_user.php?email=${encodeURIComponent(email)}`)
      .then(response => response.json())
      .then(data => {
        if (data.success && data.data) {
          console.log('Found user in database:', data.data);
          
          // Update user with database ID
          this.currentUser.user_id = data.data.user_id;
          
          // Save updated user data
          localStorage.setItem(this.userKey, JSON.stringify(this.currentUser));
          
          // Update display
          this.updateUserDisplay();
        } else {
          console.error('Could not find user by email:', email);
        }
      })
      .catch(error => {
        console.error('Error fetching user by email:', error);
      });
  }

  updateUserDisplay() {
    const usernameDisplay = document.getElementById('username-display');
    const loggedOutMenu = document.getElementById('logged-out-menu');
    const loggedInMenu = document.getElementById('logged-in-menu');
    const mobileLoggedOutMenu = document.getElementById('mobile-logged-out-menu');
    const mobileLoggedInMenu = document.getElementById('mobile-logged-in-menu');
    
    if (this.currentUser && this.currentUser.name) {
      // Update username display
      if (usernameDisplay) {
        usernameDisplay.textContent = this.currentUser.name;
      }
      
      // Show logged-in menu, hide logged-out menu
      if (loggedOutMenu) loggedOutMenu.style.display = 'none';
      if (loggedInMenu) loggedInMenu.style.display = 'block';
      
      // Update mobile menus
      if (mobileLoggedOutMenu) mobileLoggedOutMenu.style.display = 'none';
      if (mobileLoggedInMenu) mobileLoggedInMenu.style.display = 'block';
    } else {
      // Reset to default
      if (usernameDisplay) {
        usernameDisplay.textContent = 'Account';
      }
      
      // Show logged-out menu, hide logged-in menu
      if (loggedOutMenu) loggedOutMenu.style.display = 'block';
      if (loggedInMenu) loggedInMenu.style.display = 'none';
      
      // Update mobile menus
      if (mobileLoggedOutMenu) mobileLoggedOutMenu.style.display = 'block';
      if (mobileLoggedInMenu) mobileLoggedInMenu.style.display = 'none';
    }
  }

  setupLogoutHandler() {
    const logoutBtn = document.getElementById('logoutBtn');
    const mobileLogoutBtn = document.getElementById('mobileLogoutBtn');
    
    if (logoutBtn) {
      logoutBtn.addEventListener('click', (e) => {
        e.preventDefault();
        this.logout();
      });
    }
    
    if (mobileLogoutBtn) {
      mobileLogoutBtn.addEventListener('click', (e) => {
        e.preventDefault();
        this.logout();
      });
    }
  }

  login(userData) {
    console.log('Login with user data:', userData);
    
    // If we have an email but no user_id, try to fetch it
    if (userData.email && !userData.user_id) {
      fetch(`/api/find_user.php?email=${encodeURIComponent(userData.email)}`)
        .then(response => response.json())
        .then(data => {
          if (data.success && data.data) {
            console.log('Found user in database during login:', data.data);
            
            // Update user with database ID
            userData.user_id = data.data.user_id;
            
            // Save complete user data
            this.currentUser = userData;
            localStorage.setItem(this.userKey, JSON.stringify(userData));
            this.updateUserDisplay();
          } else {
            // Still save what we have
            this.currentUser = userData;
            localStorage.setItem(this.userKey, JSON.stringify(userData));
            this.updateUserDisplay();
          }
        })
        .catch(error => {
          console.error('Error fetching user by email during login:', error);
          
          // Still save what we have
          this.currentUser = userData;
          localStorage.setItem(this.userKey, JSON.stringify(userData));
          this.updateUserDisplay();
        });
    } else {
      // Save user data as is
      this.currentUser = userData;
      localStorage.setItem(this.userKey, JSON.stringify(userData));
      this.updateUserDisplay();
    }
    
    return true;
  }

  updateUserData(userData) {
    console.log('Updating user data:', userData);
    this.currentUser = userData;
    localStorage.setItem(this.userKey, JSON.stringify(userData));
    this.updateUserDisplay();
    return true;
  }

  logout() {
    localStorage.removeItem(this.userKey);
    this.currentUser = null;
    this.updateUserDisplay();
    
    // Redirect to home page
    window.location.href = '/index.php';
  }

  isLoggedIn() {
    return !!this.currentUser;
  }

  getCurrentUser() {
    return this.currentUser;
  }
}