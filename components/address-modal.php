<!-- Address Modal -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addressModalLabel">Add New Address</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="address-form">
          <input type="hidden" id="address-id">
          <div class="mb-3">
            <label for="addressName" class="form-label">Address Name</label>
            <input type="text" class="form-control" id="addressName" placeholder="Home, Work, etc.">
          </div>
          <div class="mb-3">
            <label for="fullNameAddress" class="form-label">Full Name *</label>
            <input type="text" class="form-control" id="fullNameAddress" required>
          </div>
          <div class="mb-3">
            <label for="phoneAddress" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="phoneAddress">
          </div>
          <div class="mb-3">
            <label for="streetAddress" class="form-label">Street Address *</label>
            <input type="text" class="form-control" id="streetAddress" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="city" class="form-label">City *</label>
              <input type="text" class="form-control" id="city" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="state" class="form-label">State *</label>
              <input type="text" class="form-control" id="state" required>
            </div>
          </div>
          <div class="mb-3">
            <label for="country" class="form-label">Country *</label>
            <select class="form-select" id="country" required>
              <option value="">Select Country</option>
              <option value="Nigeria">Nigeria</option>
              <option value="Ghana">Ghana</option>
              <option value="Kenya">Kenya</option>
              <option value="South Africa">South Africa</option>
            </select>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="defaultAddress">
            <label class="form-check-label" for="defaultAddress">
              Set as default address
            </label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="saveAddressBtn">Save Address</button>
      </div>
    </div>
  </div>
</div>

<!-- No inline script here - we'll use the dashboard-address.js file instead -->