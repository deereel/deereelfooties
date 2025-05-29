document.addEventListener('DOMContentLoaded', function() {
  // Check if user is logged in
  const checkAuth = setInterval(() => {
    if (window.app && window.app.auth) {
      clearInterval(checkAuth);
      initAddressesModule();
    }
  }, 100);
  
  // Timeout after 5 seconds
  setTimeout(() => clearInterval(checkAuth), 5000);
  
  function initAddressesModule() {
    // Only initialize if user is logged in
    if (!window.app.auth.isLoggedIn()) {
      return;
    }
    
    const user = window.app.auth.getCurrentUser();
    console.log('Current user for addresses:', user);
    
    // First, make sure we have the correct user_id from the database
    if (user.email) {
      fetch(`/api/find_user.php?email=${encodeURIComponent(user.email)}`)
        .then(response => response.json())
        .then(data => {
          if (data.success && data.data) {
            console.log('User found in database:', data.data);
            
            // Update user object with correct user_id from database
            user.user_id = data.data.user_id;
            
            // Update stored user data
            window.app.auth.updateUserData(user);
            
            // Now load addresses with the correct user_id
            loadUserAddresses(user.user_id);
          } else {
            console.error('User not found in database:', data);
          }
        })
        .catch(error => {
          console.error('Error finding user:', error);
        });
    } else {
      console.error('No email found for user');
    }
    
    // Handle address form submission
    const addressForm = document.getElementById('address-form');
    if (addressForm) {
      addressForm.addEventListener('submit', function(e) {
        e.preventDefault();
        saveAddress();
      });
    }
    
    // Handle save address button click
    const saveAddressBtn = document.getElementById('saveAddressBtn');
    if (saveAddressBtn) {
      saveAddressBtn.addEventListener('click', function() {
        saveAddress();
      });
    }
  }
  
  // Load user addresses from API
  function loadUserAddresses(userId) {
    const addressesSection = document.getElementById('addresses-section');
    if (!addressesSection) return;
    
    console.log('Loading addresses for user_id:', userId);
    
    // Show loading state
    addressesSection.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading your addresses...</p></div>';
    
    // Fetch addresses from API
    fetch(`/api/addresses.php?user_id=${userId}`)
      .then(response => response.json())
      .then(data => {
        console.log('Addresses response:', data);
        if (data.success && data.data.length > 0) {
          // Render addresses
          let addressesHTML = `
            <h3 class="mb-4">Address Book</h3>
            <p class="text-muted mb-4">Manage your shipping addresses.</p>
            <div class="row">
          `;
          
          data.data.forEach(address => {
            addressesHTML += `
              <div class="col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                      <h5 class="card-title">${address.address_name}</h5>
                      ${address.is_default ? '<span class="badge bg-primary">Default</span>' : ''}
                    </div>
                    <address class="mb-3">
                      ${address.full_name}<br>
                      ${address.street_address}<br>
                      ${address.city}, ${address.state}<br>
                      ${address.country}<br>
                      ${address.phone ? `Phone: ${address.phone}` : ''}
                    </address>
                    <div class="d-flex gap-2">
                      <button class="btn-primary btn-sm edit-address" data-address-id="${address.address_id}">Edit</button>
                      <button class="btn-outline-danger btn-sm delete-address" data-address-id="${address.address_id}">Delete</button>
                      ${!address.is_default ? `<button class="btn-outline-secondary btn-sm set-default" data-address-id="${address.address_id}">Set as Default</button>` : ''}
                    </div>
                  </div>
                </div>
              </div>
            `;
          });
          
          addressesHTML += `
            </div>
            <button class="btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addressModal">
              <i class="fas fa-plus me-2"></i> Add New Address
            </button>
          `;
          
          addressesSection.innerHTML = addressesHTML;
          
          // Add event listeners to buttons
          document.querySelectorAll('.edit-address').forEach(btn => {
            btn.addEventListener('click', function() {
              const addressId = this.getAttribute('data-address-id');
              editAddress(addressId);
            });
          });
          
          document.querySelectorAll('.delete-address').forEach(btn => {
            btn.addEventListener('click', function() {
              const addressId = this.getAttribute('data-address-id');
              deleteAddress(addressId);
            });
          });
          
          document.querySelectorAll('.set-default').forEach(btn => {
            btn.addEventListener('click', function() {
              const addressId = this.getAttribute('data-address-id');
              setDefaultAddress(addressId);
            });
          });
        } else {
          // No addresses found
          addressesSection.innerHTML = `
            <h3 class="mb-4">Address Book</h3>
            <p class="text-muted mb-4">Manage your shipping addresses.</p>
            <div class="alert alert-info mb-4">
              <i class="fas fa-info-circle me-2"></i> You have no saved addresses.
            </div>
            <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#addressModal">
              <i class="fas fa-plus me-2"></i> Add New Address
            </button>
          `;
        }
      })
      .catch(error => {
        console.error('Error fetching addresses:', error);
        addressesSection.innerHTML = `
          <h3 class="mb-4">Address Book</h3>
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i> Error loading addresses. Please try again later.
          </div>
          <button class="btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addressModal">
            <i class="fas fa-plus me-2"></i> Add New Address
          </button>
        `;
      });
  }
  
  // Save address (create or update)
  function saveAddress() {
    const addressForm = document.getElementById('address-form');
    const addressId = document.getElementById('address-id');
    const addressName = document.getElementById('addressName');
    const fullName = document.getElementById('fullNameAddress');
    const phone = document.getElementById('phoneAddress');
    const streetAddress = document.getElementById('streetAddress');
    const city = document.getElementById('city');
    const state = document.getElementById('state');
    const country = document.getElementById('country');
    const isDefault = document.getElementById('defaultAddress');
    
    // Validate form
    if (!fullName.value || !streetAddress.value || !city.value || !state.value || !country.value) {
      alert('Please fill in all required fields');
      return;
    }
    
    const user = window.app.auth.getCurrentUser();
    console.log('Saving address for user:', user);
    
    if (!user.user_id) {
      alert('Error: User ID not found. Please refresh the page and try again.');
      return;
    }
    
    const addressData = {
      user_id: user.user_id,
      address_name: addressName.value || 'Home',
      full_name: fullName.value,
      phone: phone.value,
      street_address: streetAddress.value,
      city: city.value,
      state: state.value,
      country: country.value,
      is_default: isDefault.checked
    };
    
    console.log('Saving address with data:', addressData);
    
    // Determine if this is a create or update operation
    const isUpdate = addressId && addressId.value;
    const url = '/api/addresses.php';
    const method = isUpdate ? 'PUT' : 'POST';
    
    if (isUpdate) {
      addressData.address_id = addressId.value;
    }
    
    // Send request to API
    fetch(url, {
      method: method,
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(addressData)
    })
    .then(response => response.json())
    .then(data => {
      console.log('Save address response:', data);
      if (data.success) {
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('addressModal'));
        modal.hide();
        
        // Reset form
        addressForm.reset();
        if (addressId) addressId.value = '';
        
        // Reload addresses
        loadUserAddresses(user.user_id);
        
        // Show success message
        alert(isUpdate ? 'Address updated successfully' : 'Address added successfully');
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error saving address:', error);
      alert('Error saving address. Please try again.');
    });
  }
  
  // Edit address
  function editAddress(addressId) {
    // Fetch address details
    fetch(`/api/addresses.php?address_id=${addressId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const address = data.data;
          
          // Fill form with address data
          document.getElementById('address-id').value = address.address_id;
          document.getElementById('addressName').value = address.address_name;
          document.getElementById('fullNameAddress').value = address.full_name;
          document.getElementById('phoneAddress').value = address.phone || '';
          document.getElementById('streetAddress').value = address.street_address;
          document.getElementById('city').value = address.city;
          document.getElementById('state').value = address.state;
          document.getElementById('country').value = address.country;
          document.getElementById('defaultAddress').checked = address.is_default == 1;
          
          // Update modal title
          document.getElementById('addressModalLabel').textContent = 'Edit Address';
          
          // Show modal
          const modal = new bootstrap.Modal(document.getElementById('addressModal'));
          modal.show();
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error fetching address:', error);
        alert('Error loading address details. Please try again.');
      });
  }
  
  // Delete address
  function deleteAddress(addressId) {
    if (confirm('Are you sure you want to delete this address?')) {
      fetch(`/api/addresses.php?address_id=${addressId}`, {
        method: 'DELETE'
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Reload addresses
          const user = window.app.auth.getCurrentUser();
          loadUserAddresses(user.user_id);
          
          // Show success message
          alert('Address deleted successfully');
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error deleting address:', error);
        alert('Error deleting address. Please try again.');
      });
    }
  }
  
  // Set address as default
  function setDefaultAddress(addressId) {
    const user = window.app.auth.getCurrentUser();
    
    fetch('/api/addresses.php', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        address_id: addressId,
        is_default: true
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Reload addresses
        loadUserAddresses(user.user_id);
        
        // Show success message
        alert('Default address updated');
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error updating default address:', error);
      alert('Error updating default address. Please try again.');
    });
  }
});