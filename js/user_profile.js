document.addEventListener('DOMContentLoaded', function() {
  // Check if user is logged in
  const checkAuth = setInterval(() => {
    if (window.app && window.app.auth) {
      clearInterval(checkAuth);
      initUserProfile();
    }
  }, 100);
  
  // Timeout after 5 seconds
  setTimeout(() => clearInterval(checkAuth), 5000);
  
  function initUserProfile() {
    // Only initialize if user is logged in
    if (!window.app.auth.isLoggedIn()) {
      return;
    }
    
    const user = window.app.auth.getCurrentUser();
    console.log('Current user from localStorage:', user);
    
    // Fill user data form
    fillUserDataForm(user);
    
    // Handle personal data form submission
    const personalDataForm = document.getElementById('personal-data-form');
    if (personalDataForm) {
      personalDataForm.addEventListener('submit', function(e) {
        e.preventDefault();
        updateUserData();
      });
    }
  }
  
  // Fill user data form with current user information
  function fillUserDataForm(user) {
    const fullNameInput = document.getElementById('fullName');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const genderSelect = document.getElementById('gender');
    
    if (fullNameInput) fullNameInput.value = user.name || '';
    if (emailInput) emailInput.value = user.email || '';
    if (phoneInput) phoneInput.value = user.phone || '';
    if (genderSelect && user.gender) genderSelect.value = user.gender;
    
    // Get the user's email to find them in the database
    const userEmail = user.email;
    if (!userEmail) {
      console.error('No email found for user');
      return;
    }
    
    // First, try to find the user by email to get the correct user_id
    fetch(`/api/find_user.php?email=${encodeURIComponent(userEmail)}`)
      .then(response => response.json())
      .then(data => {
        if (data.success && data.data) {
          console.log('User found in database:', data.data);
          
          // Update user object with correct user_id from database
          user.user_id = data.data.user_id;
          
          // Update form fields
          if (phoneInput && data.data.phone) phoneInput.value = data.data.phone;
          
          // Update stored user data
          window.app.auth.updateUserData(user);
          
          console.log('Updated user object:', user);
        } else {
          console.error('User not found in database:', data);
        }
      })
      .catch(error => {
        console.error('Error finding user:', error);
      });
  }
  
  // Update user data
  function updateUserData() {
    const fullNameInput = document.getElementById('fullName');
    const phoneInput = document.getElementById('phone');
    const genderSelect = document.getElementById('gender');
    const currentPasswordInput = document.getElementById('currentPassword');
    const newPasswordInput = document.getElementById('newPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    
    const user = window.app.auth.getCurrentUser();
    console.log('Updating user data for:', user);
    
    // Basic validation
    if (!fullNameInput.value) {
      alert('Please enter your name');
      return;
    }
    
    // Check if password is being changed
    if (newPasswordInput.value || confirmPasswordInput.value) {
      if (!currentPasswordInput.value) {
        alert('Please enter your current password');
        return;
      }
      
      if (newPasswordInput.value !== confirmPasswordInput.value) {
        alert('New passwords do not match');
        return;
      }
      
      // Password change would be handled by a separate API call in a real app
      alert('Password change functionality would be implemented in a real app');
    }
    
    // Prepare data for API
    const userData = {
      user_id: user.user_id,
      name: fullNameInput.value
    };
    
    if (phoneInput.value) {
      userData.phone = phoneInput.value;
    }
    
    if (genderSelect.value) {
      userData.gender = genderSelect.value;
    }
    
    console.log('Sending update with data:', userData);
    
    // Send update to API
    fetch('/api/user.php', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(userData)
    })
    .then(response => response.json())
    .then(data => {
      console.log('Update response:', data);
      if (data.success) {
        // Update local user data
        user.name = fullNameInput.value;
        user.phone = phoneInput.value;
        user.gender = genderSelect.value;
        
        // Update stored user data
        window.app.auth.updateUserData(user);
        
        // Show success message
        alert('Personal information updated successfully');
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error updating user data:', error);
      alert('Error updating personal information. Please try again.');
    });
  }
});