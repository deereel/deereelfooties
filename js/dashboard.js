// Main Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
  console.log('Dashboard main script loaded');
  
  // Initialize all dashboard components
  initPersonalData();
  
  // Handle logout button
  const logoutBtn = document.getElementById('logout-btn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function(e) {
      e.preventDefault();
      window.location.href = '/auth/logout.php';
    });
  }
  
  // Handle delete account form
  const deleteForm = document.getElementById('delete-form');
  if (deleteForm) {
    deleteForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const confirmText = document.getElementById('deleteConfirm').value;
      const password = document.getElementById('password').value;
      
      if (confirmText !== 'DELETE') {
        alert('Please type DELETE to confirm account deletion');
        return;
      }
      
      if (!password) {
        alert('Please enter your password');
        return;
      }
      
      if (confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.')) {
        deleteAccount(password);
      }
    });
  }
});

// Personal Data Management
function initPersonalData() {
  const personalForm = document.getElementById('personal-form');
  if (personalForm) {
    personalForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const userData = {
        name: document.getElementById('fullName').value,
        phone: document.getElementById('phone').value,
        gender: document.getElementById('gender').value
      };
      
      updateUserData(userData);
    });
  }
}

// Update user data in database
async function updateUserData(userData) {
  try {
    const response = await fetch('/api/update_user.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(userData)
    });
    
    const data = await response.json();
    
    if (data.success) {
      // Update local storage
      const storedUser = JSON.parse(localStorage.getItem('DRFUser') || '{}');
      const updatedUser = { ...storedUser, ...userData };
      localStorage.setItem('DRFUser', JSON.stringify(updatedUser));
      
      showToast('Success', 'Your profile has been updated successfully', 'success');
    } else {
      showToast('Error', data.message || 'Failed to update profile', 'error');
    }
  } catch (error) {
    console.error('Error updating user data:', error);
    showToast('Error', 'An unexpected error occurred', 'error');
  }
}

// Delete account
async function deleteAccount(password) {
  try {
    const response = await fetch('/api/delete_account.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ password })
    });
    
    const data = await response.json();
    
    if (data.success) {
      // Clear local storage
      localStorage.removeItem('DRFUser');
      // Cart functionality has been removed
      localStorage.removeItem('DRFWishlist');
      
      showToast('Account Deleted', 'Your account has been successfully deleted', 'success');
      
      // Redirect to home page after a short delay
      setTimeout(() => {
        window.location.href = '/index.php';
      }, 2000);
    } else {
      showToast('Error', data.message || 'Failed to delete account', 'error');
    }
  } catch (error) {
    console.error('Error deleting account:', error);
    showToast('Error', 'An unexpected error occurred', 'error');
  }
}

// Helper function to show toast notifications
function showToast(title, message, type = 'info') {
  // Check if Bootstrap toast is available
  if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
    // Create toast element
    const toastEl = document.createElement('div');
    toastEl.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0`;
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    
    toastEl.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">
          <strong>${title}</strong>: ${message}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    `;
    
    // Add to container or body
    const toastContainer = document.querySelector('.toast-container') || document.body;
    toastContainer.appendChild(toastEl);
    
    // Initialize and show toast
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
    
    // Remove after hidden
    toastEl.addEventListener('hidden.bs.toast', function() {
      toastEl.remove();
    });
  } else {
    // Fallback to alert
    alert(`${title}: ${message}`);
  }
}