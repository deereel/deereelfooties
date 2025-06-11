// Dashboard Address Management
class DashboardAddressManager {
  constructor() {
    this.init();
  }

  init() {
    console.log('Initializing Dashboard Address Manager');
    this.bindEvents();
    this.loadAddresses();
  }

  bindEvents() {
    // Add new address button
    const addAddressBtn = document.getElementById('add-address-btn');
    if (addAddressBtn) {
      addAddressBtn.addEventListener('click', () => {
        this.openAddressModal();
      });
    }

    // Listen for address modal events
    const addressModal = document.getElementById('addressModal');
    if (addressModal) {
      addressModal.addEventListener('hidden.bs.modal', () => {
        this.resetAddressForm();
      });
    }

    // Save address button
    const saveAddressBtn = document.getElementById('saveAddressBtn');
    if (saveAddressBtn) {
      saveAddressBtn.addEventListener('click', (e) => {
        e.preventDefault();
        this.saveAddress();
      });
    }
  }

  openAddressModal(addressData = null) {
    const modal = new bootstrap.Modal(document.getElementById('addressModal'));
    const modalTitle = document.getElementById('addressModalLabel');
    const saveBtn = document.getElementById('saveAddressBtn');

    if (addressData) {
      // Edit mode
      modalTitle.textContent = 'Edit Address';
      saveBtn.textContent = 'Update Address';
      this.populateAddressForm(addressData);
    } else {
      // Add mode
      modalTitle.textContent = 'Add New Address';
      saveBtn.textContent = 'Save Address';
      this.resetAddressForm();
    }

    modal.show();
  }

  populateAddressForm(addressData) {
    console.log('Populating form with address data:', addressData);
    
    // Clear form first
    this.resetAddressForm();
    
    // Populate fields with proper null checks
    const addressId = document.getElementById('address-id');
    const addressName = document.getElementById('addressName');
    const fullNameAddress = document.getElementById('fullNameAddress');
    const phoneAddress = document.getElementById('phoneAddress');
    const streetAddress = document.getElementById('streetAddress');
    const city = document.getElementById('city');
    const state = document.getElementById('state');
    const country = document.getElementById('country');
    const defaultAddress = document.getElementById('defaultAddress');

    if (addressId) addressId.value = addressData.address_id || '';
    if (addressName) addressName.value = addressData.name || '';
    if (fullNameAddress) fullNameAddress.value = addressData.full_name || '';
    if (phoneAddress) phoneAddress.value = addressData.phone || '';
    if (streetAddress) streetAddress.value = addressData.line1 || '';
    if (city) city.value = addressData.city || '';
    if (state) state.value = addressData.state || '';
    if (country) country.value = addressData.country || '';
    if (defaultAddress) defaultAddress.checked = parseInt(addressData.is_default) === 1;

    console.log('Form populated with full_name:', addressData.full_name);
  }

  resetAddressForm() {
    const form = document.getElementById('address-form');
    if (form) {
      form.reset();
    }
    const addressId = document.getElementById('address-id');
    if (addressId) {
      addressId.value = '';
    }
  }

  async loadAddresses() {
    const container = document.getElementById('addresses-container');
    if (!container) return;

    try {
      // Show loading
      container.innerHTML = `
        <div class="text-center py-4">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2">Loading your addresses...</p>
        </div>
      `;

      // Get user data - use user_id field from your database structure
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        container.innerHTML = '<p class="text-center py-4">Please log in to view addresses.</p>';
        return;
      }

      const user = JSON.parse(userData);
      const userId = user.user_id || user.id;

      console.log('Loading addresses for user ID:', userId);

      // Fetch addresses
      const response = await fetch(`/api/get-addresses.php?user_id=${userId}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      console.log('Loaded addresses response:', data);

      if (data.success) {
        this.renderAddresses(data.addresses || []);
      } else {
        container.innerHTML = `<p class="text-center py-4 text-danger">Error: ${data.message}</p>`;
      }
    } catch (error) {
      console.error('Error loading addresses:', error);
      container.innerHTML = '<p class="text-center py-4 text-danger">Failed to load addresses. Please try again.</p>';
    }
  }

  renderAddresses(addresses) {
    const container = document.getElementById('addresses-container');
    
    console.log('Rendering addresses:', addresses);
    
    if (!addresses || addresses.length === 0) {
      container.innerHTML = `
        <div class="text-center py-5">
          <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
          <h5>No addresses found</h5>
          <p class="text-muted">Add your first address to get started.</p>
        </div>
      `;
      return;
    }

    const addressesHtml = addresses.map(address => {
      console.log('Rendering individual address:', address);
      
      // Ensure we have proper fallbacks for all fields
      const displayName = address.name || address.address_type || 'Address';
      const fullName = address.full_name || 'No name provided';
      const line1 = address.line1 || '';
      const line2 = address.line2 || '';
      const city = address.city || '';
      const state = address.state || '';
      const country = address.country || '';
      const phone = address.phone || '';
      const isDefault = parseInt(address.is_default) === 1;
      
      return `
        <div class="card mb-3">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <div class="flex-grow-1">
                <h6 class="card-title d-flex align-items-center mb-2">
                  <span class="me-2">${displayName}</span>
                  ${isDefault ? '<span class="badge bg-primary">Default</span>' : ''}
                </h6>
                <div class="address-details">
                  <p class="card-text mb-1">
                    <strong><i class="fas fa-user me-2 text-muted"></i>${fullName}</strong>
                  </p>
                  <p class="card-text mb-1">
                    <i class="fas fa-map-marker-alt me-2 text-muted"></i>${line1}
                  </p>
                  ${line2 ? `<p class="card-text mb-1"><i class="fas fa-map me-2 text-muted"></i>${line2}</p>` : ''}
                  <p class="card-text mb-1">
                    <i class="fas fa-city me-2 text-muted"></i>${city}, ${state}
                  </p>
                  <p class="card-text mb-1">
                    <i class="fas fa-flag me-2 text-muted"></i>${country}
                  </p>
                  ${phone ? `<p class="card-text mb-0"><i class="fas fa-phone me-2 text-muted"></i>${phone}</p>` : ''}
                </div>
              </div>
              <div class="dropdown ms-3">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); dashboardAddressManager.editAddress(${address.address_id})">
                      <i class="fas fa-edit me-2"></i>Edit
                    </a>
                  </li>
                  ${!isDefault ? `
                  <li>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); dashboardAddressManager.setDefaultAddress(${address.address_id})">
                      <i class="fas fa-star me-2"></i>Set as Default
                    </a>
                  </li>
                  ` : ''}
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); dashboardAddressManager.deleteAddress(${address.address_id})">
                      <i class="fas fa-trash me-2"></i>Delete
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      `;
    }).join('');

    container.innerHTML = addressesHtml;
  }

  async editAddress(addressId) {
    try {
      console.log('Edit address called for ID:', addressId);
      
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        alert('Please log in to edit addresses');
        return;
      }

      const user = JSON.parse(userData);
      const userId = user.user_id || user.id;

      console.log('Fetching address for editing - ID:', addressId, 'User:', userId);

      const response = await fetch(`/api/get-address.php?address_id=${addressId}&user_id=${userId}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      
      if (data.success) {
        this.openAddressModal(data.address);
      } else {
        alert(`Error: ${data.message}`);
      }
    } catch (error) {
      console.error('Error fetching address:', error);
      alert('Failed to load address details. Please try again.');
    }
  }

  async saveAddress() {
    try {
      const form = document.getElementById('address-form');
      if (!form) return;
      
      // Basic form validation
      const requiredFields = ['fullNameAddress', 'streetAddress', 'city', 'state', 'country'];
      let isValid = true;
      
      requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input || !input.value.trim()) {
          isValid = false;
          input.classList.add('is-invalid');
        } else {
          input.classList.remove('is-invalid');
        }
      });
      
      if (!isValid) {
        alert('Please fill in all required fields');
        return;
      }
      
      // Get user data
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        alert('Please log in to save addresses');
        return;
      }

      const user = JSON.parse(userData);
      const userId = user.user_id || user.id;
      
      // Get form data
      const addressId = document.getElementById('address-id').value;
      const addressData = {
        user_id: userId,
        name: document.getElementById('addressName').value,
        full_name: document.getElementById('fullNameAddress').value,
        phone: document.getElementById('phoneAddress').value,
        line1: document.getElementById('streetAddress').value,
        city: document.getElementById('city').value,
        state: document.getElementById('state').value,
        country: document.getElementById('country').value,
        is_default: document.getElementById('defaultAddress').checked ? 1 : 0
      };
      
      if (addressId) {
        addressData.id = addressId;
      }
      
      console.log('Saving address data:', addressData);
      
      // Save address
      const response = await fetch('/api/save-address.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(addressData)
      });
      
      const data = await response.json();
      
      if (data.success) {
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('addressModal'));
        if (modal) {
          modal.hide();
        }
        
        // Show success message
        alert(addressId ? 'Address updated successfully' : 'Address added successfully');
        
        // Reload addresses
        this.loadAddresses();
      } else {
        alert(`Error: ${data.message}`);
      }
    } catch (error) {
      console.error('Error saving address:', error);
      alert('Failed to save address. Please try again.');
    }
  }

  async setDefaultAddress(addressId) {
    try {
      // Get user data
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        alert('Please log in to manage addresses');
        return;
      }

      const user = JSON.parse(userData);
      const userId = user.user_id || user.id;
      
      // Set as default
      const response = await fetch('/api/set-default-address.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          user_id: userId,
          address_id: addressId
        })
      });
      
      const data = await response.json();
      
      if (data.success) {
        // Show success message
        alert('Default address updated successfully');
        
        // Reload addresses
        this.loadAddresses();
      } else {
        alert(`Error: ${data.message}`);
      }
    } catch (error) {
      console.error('Error setting default address:', error);
      alert('Failed to update default address. Please try again.');
    }
  }

  async deleteAddress(addressId) {
    if (!confirm('Are you sure you want to delete this address?')) {
      return;
    }
    
    try {
      // Get user data
      const userData = localStorage.getItem('DRFUser');
      if (!userData) {
        alert('Please log in to manage addresses');
        return;
      }

      const user = JSON.parse(userData);
      const userId = user.user_id || user.id;
      
      console.log('Deleting address:', addressId, 'for user:', userId);
      
      // Delete address
      const response = await fetch('/api/delete-address.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          user_id: userId,
          address_id: addressId
        })
      });
      
      const data = await response.json();
      console.log('Delete response:', data);
      
      if (data.success) {
        // Show success message
        alert('Address deleted successfully');
        
        // Reload addresses
        this.loadAddresses();
      } else {
        alert(`Error: ${data.message}`);
      }
    } catch (error) {
      console.error('Error deleting address:', error);
      alert('Failed to delete address. Please try again.');
    }
  }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
  // Check if we're on the dashboard page
  if (document.body.getAttribute('data-page') === 'dashboard') {
    window.dashboardAddressManager = new DashboardAddressManager();
  }
});