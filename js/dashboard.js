document.addEventListener('DOMContentLoaded', function() {
  // Check if user is logged in
  const checkAuth = setInterval(() => {
    if (window.app && window.app.auth) {
      clearInterval(checkAuth);
      initDashboard();
    }
  }, 100);
  
  // Timeout after 5 seconds
  setTimeout(() => clearInterval(checkAuth), 5000);
  
  function initDashboard() {
    // Redirect if not logged in
    if (!window.app.auth.isLoggedIn()) {
      window.location.href = '/index.php';
      return;
    }
    
    // Get user data
    const user = window.app.auth.getCurrentUser();
    
    // Update username in dashboard
    const dashboardUsername = document.getElementById('dashboard-username');
    if (dashboardUsername && user) {
      dashboardUsername.textContent = user.name + "'s Account";
    }
    
    // Fill in user data form
    const fullNameInput = document.getElementById('fullName');
    const emailInput = document.getElementById('email');
    
    if (fullNameInput && user) {
      fullNameInput.value = user.name || '';
    }
    
    if (emailInput && user) {
      emailInput.value = user.email || '';
    }
    
    // Handle navigation
    const navLinks = document.querySelectorAll('.dashboard-nav .nav-link');
    const contentSections = document.querySelectorAll('.dashboard-content');
    
    navLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        
        const section = this.getAttribute('data-section');
        if (!section) return;
        
        // Update active link
        navLinks.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        
        // Show selected section
        contentSections.forEach(s => s.classList.remove('active'));
        document.getElementById(section + '-section').classList.add('active');
        
        // Update URL hash
        window.location.hash = section;
      });
    });
    
    // Check for hash in URL
    const hash = window.location.hash.substring(1);
    if (hash) {
      const link = document.querySelector(`.dashboard-nav .nav-link[data-section="${hash}"]`);
      if (link) {
        link.click();
      }
    }
    
    // Handle dashboard logout
    const logoutBtn = document.getElementById('dashboard-logout');
    if (logoutBtn) {
      logoutBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.app.auth.logout();
      });
    }
    
    // Handle personal data form submission
    const personalDataForm = document.getElementById('personal-data-form');
    if (personalDataForm) {
      personalDataForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const name = document.getElementById('fullName').value;
        const phone = document.getElementById('phone').value;
        const birthdate = document.getElementById('birthdate').value;
        
        // Update user data
        user.name = name;
        user.phone = phone;
        user.birthdate = birthdate;
        
        // Save to localStorage
        window.app.auth.login(user);
        
        // Show success message
        alert('Personal information updated successfully!');
      });
    }
    
    // Handle delete account form
    const deleteAccountForm = document.getElementById('delete-account-form');
    if (deleteAccountForm) {
      deleteAccountForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const confirmation = document.getElementById('deleteConfirmation').value;
        
        if (confirmation !== 'DELETE') {
          alert('Please type DELETE to confirm account deletion');
          return;
        }
        
        if (confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.')) {
          window.app.auth.logout();
          alert('Your account has been deleted.');
          window.location.href = '/index.php';
        }
      });
    }
    
    // Handle address form
    const saveAddressBtn = document.getElementById('saveAddressBtn');
    if (saveAddressBtn) {
      saveAddressBtn.addEventListener('click', function() {
        alert('Address saved successfully!');
        const modal = bootstrap.Modal.getInstance(document.getElementById('addressModal'));
        if (modal) {
          modal.hide();
        }
      });
    }
  }

  // In your dashboard.js file
  function switchSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.dashboard-content').forEach(section => {
      section.classList.remove('active');
    });
    
    // Show selected section
    const targetSection = document.getElementById(sectionId + '-section');
    if (targetSection) {
      targetSection.classList.add('active');
      
      // If switching to orders section, load orders
      if (sectionId === 'orders') {
        // Trigger a custom event that dashboard-orders.js can listen for
        const event = new CustomEvent('loadOrders');
        document.dispatchEvent(event);
      }
    }
  }

});