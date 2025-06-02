<?php include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/auth/db.php'); ?><body class="bg-background" data-page="product-admin">
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/navbar.php'); ?>

  <!-- Main Content -->
  <div class="container py-5">
    <h1 class="mb-4">Product Management</h1>
    
    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="add-tab" data-bs-toggle="tab" data-bs-target="#add-content" type="button" role="tab" aria-controls="add-content" aria-selected="true">Add New Product</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit-content" type="button" role="tab" aria-controls="edit-content" aria-selected="false">Edit Product</button>
      </li>
    </ul>
    
    <!-- Tab Content -->
    <div class="tab-content" id="productTabsContent">
      <!-- Add New Product Tab -->
      <div class="tab-pane fade show active" id="add-content" role="tabpanel" aria-labelledby="add-tab">
        <div class="card shadow-sm">
          <div class="card-body">
            <form id="add-product-form">
              <!-- Basic Information -->
              <h3 class="mb-3">Basic Information</h3>
              <div class="row mb-3">
                <div class="col-md-6 mb-3">
                  <label for="name" class="form-label">Product Name*</label>
                  <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="slug" class="form-label">Slug* (URL-friendly name)</label>
                  <input type="text" class="form-control" id="slug" name="slug" required>
                  <small class="text-muted">Example: oxford-cap-toe-600</small>
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-md-4 mb-3">
                  <label for="price" class="form-label">Price (₦)*</label>
                  <input type="number" class="form-control" id="price" name="price" required>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="gender" class="form-label">Gender*</label>
                  <select class="form-control" id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="men">Men</option>
                    <option value="women">Women</option>
                    <option value="unisex">Unisex</option>
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="category" class="form-label">Category*</label>
                  <select class="form-control" id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="shoes">Shoes</option>
                    <option value="boots">Boots</option>
                    <option value="slippers">Slippers</option>
                    <option value="mules">Mules</option>
                  </select>
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-md-4 mb-3">
                  <label for="type" class="form-label">Type*</label>
                  <input type="text" class="form-control" id="type" name="type" required>
                  <small class="text-muted">Example: oxford, loafer, derby, monk, chelsea</small>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="colors" class="form-label">Available Colors</label>
                  <input type="text" class="form-control" id="colors" name="colors">
                  <small class="text-muted">Comma-separated: black,brown,tan</small>
                </div>
                <div class="col-md-4 mb-3">
                  <label for="sizes" class="form-label">Available Sizes</label>
                  <input type="text" class="form-control" id="sizes" name="sizes">
                  <small class="text-muted">Comma-separated: 39,40,41,42,43,44</small>
                </div>
              </div>
              
              <!-- Description -->
              <h3 class="mb-3 mt-4">Description</h3>
              <div class="mb-3">
                <label for="short_description" class="form-label">Short Description</label>
                <input type="text" class="form-control" id="short_description" name="short_description">
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Full Description</label>
                <textarea class="form-control" id="description" name="description" rows="5"></textarea>
              </div>
              <div class="mb-3">
                <label for="features" class="form-label">Features</label>
                <textarea class="form-control" id="features" name="features" rows="3"></textarea>
                <small class="text-muted">One feature per line</small>
              </div>

                <div class="mb-3">
                    <label for="details_care" class="form-label">Details & Care</label>
                    <textarea class="form-control" id="details_care" name="details_care" rows="5"></textarea>
                    <small class="text-muted">HTML is supported. Use <ul> and <li> tags for bullet points.</small>
                </div>
              
              <!-- Images -->
              <h3 class="mb-3 mt-4">Images</h3>
              <div class="row mb-3">
                <div class="col-md-6 mb-3">
                  <label for="main_image" class="form-label">Main Image Path*</label>
                  <input type="text" class="form-control" id="main_image" name="main_image" required>
                  <small class="text-muted">Example: /images/product-name.webp</small>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="additional_images" class="form-label">Additional Images</label>
                  <input type="text" class="form-control" id="additional_images" name="additional_images">
                  <small class="text-muted">Comma-separated paths</small>
                </div>
              </div>
              
              <!-- Display Options -->
              <h3 class="mb-3 mt-4">Display Options</h3>
              <div class="row mb-3">
                <div class="col-md-6 mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                    <label class="form-check-label" for="is_featured">
                      Feature on homepage
                    </label>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_new_collection" name="is_new_collection">
                    <label class="form-check-label" for="is_new_collection">
                      Add to New Collection
                    </label>
                  </div>
                </div>
              </div>
              
              <div class="mt-4">
                <button type="submit" class="btn btn-primary">Add Product</button>
                <button type="reset" class="btn btn-secondary ms-2">Reset Form</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      
      <!-- Edit Product Tab -->
      <div class="tab-pane fade" id="edit-content" role="tabpanel" aria-labelledby="edit-tab">
        <div class="card shadow-sm">
          <div class="card-body">
            <!-- Product Selection -->
            <div class="mb-4">
              <label for="product-select" class="form-label">Select Product to Edit</label>
              <select class="form-control" id="product-select">
                <option value="">Select a product...</option>
                <?php
                  // Get all products from database
                  $stmt = $pdo->query("SELECT product_id, name, slug FROM products ORDER BY name");
                  while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value=\"{$product['product_id']}\">{$product['name']} ({$product['slug']})</option>";
                  }
                ?>
              </select>
            </div>
            
            <!-- Edit Form (initially hidden) -->
            <div id="edit-form-container" style="display: none;">
              <form id="edit-product-form">
                <input type="hidden" id="edit-product-id" name="product_id">
                
                <!-- Basic Information -->
                <h3 class="mb-3">Basic Information</h3>
                <div class="row mb-3">
                  <div class="col-md-6 mb-3">
                    <label for="edit-name" class="form-label">Product Name*</label>
                    <input type="text" class="form-control" id="edit-name" name="name" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="edit-slug" class="form-label">Slug* (URL-friendly name)</label>
                    <input type="text" class="form-control" id="edit-slug" name="slug" required>
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-md-4 mb-3">
                    <label for="edit-price" class="form-label">Price (₦)*</label>
                    <input type="number" class="form-control" id="edit-price" name="price" required>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="edit-gender" class="form-label">Gender*</label>
                    <select class="form-control" id="edit-gender" name="gender" required>
                      <option value="men">Men</option>
                      <option value="women">Women</option>
                      <option value="unisex">Unisex</option>
                    </select>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="edit-category" class="form-label">Category*</label>
                    <select class="form-control" id="edit-category" name="category" required>
                      <option value="shoes">Shoes</option>
                      <option value="boots">Boots</option>
                      <option value="slippers">Slippers</option>
                      <option value="mules">Mules</option>
                    </select>
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-md-4 mb-3">
                    <label for="edit-type" class="form-label">Type*</label>
                    <input type="text" class="form-control" id="edit-type" name="type" required>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="edit-colors" class="form-label">Available Colors</label>
                    <input type="text" class="form-control" id="edit-colors" name="colors">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="edit-sizes" class="form-label">Available Sizes</label>
                    <input type="text" class="form-control" id="edit-sizes" name="sizes">
                  </div>
                </div>
                
                <!-- Description -->
                <h3 class="mb-3 mt-4">Description</h3>
                <div class="mb-3">
                  <label for="edit-short-description" class="form-label">Short Description</label>
                  <input type="text" class="form-control" id="edit-short-description" name="short_description">
                </div>
                <div class="mb-3">
                  <label for="edit-description" class="form-label">Full Description</label>
                  <textarea class="form-control" id="edit-description" name="description" rows="5"></textarea>
                </div>
                <div class="mb-3">
                  <label for="edit-features" class="form-label">Features</label>
                  <textarea class="form-control" id="edit-features" name="features" rows="3"></textarea>
                </div>


                <div class="mb-3">
                    <label for="edit-details-care" class="form-label">Details & Care</label>
                    <textarea class="form-control" id="edit-details-care" name="details_care" rows="5"></textarea>
                    <small class="text-muted">HTML is supported. Use <ul> and <li> tags for bullet points.</small>
                </div>
                
                <!-- Images -->
                <h3 class="mb-3 mt-4">Images</h3>
                <div class="row mb-3">
                  <div class="col-md-6 mb-3">
                    <label for="edit-main-image" class="form-label">Main Image Path*</label>
                    <input type="text" class="form-control" id="edit-main-image" name="main_image" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="edit-additional-images" class="form-label">Additional Images</label>
                    <input type="text" class="form-control" id="edit-additional-images" name="additional_images">
                  </div>
                </div>
                
                <!-- Display Options -->
                <h3 class="mb-3 mt-4">Display Options</h3>
                <div class="row mb-3">
                  <div class="col-md-6 mb-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="edit-is-featured" name="is_featured">
                      <label class="form-check-label" for="edit-is-featured">
                        Feature on homepage
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="edit-is-new-collection" name="is_new_collection">
                      <label class="form-check-label" for="edit-is-new-collection">
                        Add to New Collection
                      </label>
                    </div>
                  </div>
                </div>
                
                <div class="mt-4">
                  <button type="submit" class="btn btn-primary">Update Product</button>
                  <button type="button" id="delete-product-btn" class="btn btn-danger ms-2">Delete Product</button>
                </div>                
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/footer.php'); ?>
  <?php include($_SERVER['DOCUMENT_ROOT'] . '/components/scripts.php'); ?>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Add Product Form
      const addForm = document.getElementById('add-product-form');
      
      // Auto-generate slug from name
      document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-');
        document.getElementById('slug').value = slug;
      });
      
      // Handle add form submission
      addForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Collect form data
        const formData = {
          name: document.getElementById('name').value,
          slug: document.getElementById('slug').value,
          price: document.getElementById('price').value,
          gender: document.getElementById('gender').value,
          category: document.getElementById('category').value,
          type: document.getElementById('type').value,
          colors: document.getElementById('colors').value,
          sizes: document.getElementById('sizes').value,
          short_description: document.getElementById('short_description').value,
          description: document.getElementById('description').value,
          details_care: document.getElementById('details_care').value,
          main_image: document.getElementById('main_image').value,
          additional_images: document.getElementById('additional_images').value,
          is_featured: document.getElementById('is_featured').checked ? 1 : 0,
          is_new_collection: document.getElementById('is_new_collection').checked ? 1 : 0
        };
        
        // Convert features to JSON array
        const featuresText = document.getElementById('features').value;
        if (featuresText) {
          formData.features = JSON.stringify(
            featuresText.split('\n').filter(line => line.trim() !== '')
          );
        }
        
        // Send data to API
        fetch('/api/products.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Product added successfully!');
            addForm.reset();
            // Refresh product dropdown
            location.reload();
          } else {
            alert('Error: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred. Please try again.');
        });
      });
      
      // Edit Product Form
      const productSelect = document.getElementById('product-select');
      const editFormContainer = document.getElementById('edit-form-container');
      const editForm = document.getElementById('edit-product-form');
      
      // Load product data when selected
      productSelect.addEventListener('change', function() {
        const productId = this.value;
        
        if (!productId) {
          editFormContainer.style.display = 'none';
          return;
        }
        
        // Fetch product data
        fetch(`/api/products.php?product_id=${productId}`)
          .then(response => response.json())
          .then(data => {
            if (data.success && data.data) {
              const product = data.data;
              
              // Fill form with product data
              document.getElementById('edit-product-id').value = product.product_id;
              document.getElementById('edit-name').value = product.name;
              document.getElementById('edit-slug').value = product.slug;
              document.getElementById('edit-price').value = product.price;
              document.getElementById('edit-gender').value = product.gender;
              document.getElementById('edit-category').value = product.category;
              document.getElementById('edit-type').value = product.type;
              document.getElementById('edit-colors').value = product.colors || '';
              document.getElementById('edit-sizes').value = product.sizes || '';
              document.getElementById('edit-short-description').value = product.short_description || '';
              document.getElementById('edit-description').value = product.description || '';
              document.getElementById('edit-details-care').value = product.details_care || '';
              
              // Parse features from JSON
              const features = JSON.parse(product.features || '[]');
              document.getElementById('edit-features').value = features.join('\n');
              
              document.getElementById('edit-main-image').value = product.main_image;
              document.getElementById('edit-additional-images').value = product.additional_images || '';
              document.getElementById('edit-is-featured').checked = product.is_featured == 1;
              document.getElementById('edit-is-new-collection').checked = product.is_new_collection == 1;
              
              // Show edit form
              editFormContainer.style.display = 'block';
            } else {
              alert('Error loading product data');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading product data');
          });
      });
      
      // Handle edit form submission
      editForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Collect form data
        const formData = {
          product_id: document.getElementById('edit-product-id').value,
          name: document.getElementById('edit-name').value,
          slug: document.getElementById('edit-slug').value,
          price: document.getElementById('edit-price').value,
          gender: document.getElementById('edit-gender').value,
          category: document.getElementById('edit-category').value,
          type: document.getElementById('edit-type').value,
          colors: document.getElementById('edit-colors').value,
          sizes: document.getElementById('edit-sizes').value,
          short_description: document.getElementById('edit-short-description').value,
          description: document.getElementById('edit-description').value,
          details_care: document.getElementById('edit-details-care').value,
          main_image: document.getElementById('edit-main-image').value,
          additional_images: document.getElementById('edit-additional-images').value,
          is_featured: document.getElementById('edit-is-featured').checked ? 1 : 0,
          is_new_collection: document.getElementById('edit-is-new-collection').checked ? 1 : 0
        };
        
        // Convert features to JSON array
        const featuresText = document.getElementById('edit-features').value;
        if (featuresText) {
          formData.features = JSON.stringify(
            featuresText.split('\n').filter(line => line.trim() !== '')
          );
        }
        
        // Send data to API
        fetch('/api/products.php', {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Product updated successfully!');
          } else {
            alert('Error: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred. Please try again.');
        });
      });
    });

    // Add this JavaScript to handle the delete functionality
    document.getElementById('delete-product-btn').addEventListener('click', function() {
    const productId = document.getElementById('edit-product-id').value;
    
    if (!productId) {
        alert('No product selected');
        return;
    }
    
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        fetch('/api/products.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
        if (data.success) {
            alert('Product deleted successfully');
            // Reset form and hide it
            document.getElementById('edit-form-container').style.display = 'none';
            document.getElementById('product-select').value = '';
            // Refresh product dropdown
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
        })
        .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the product');
        });
    }
    });

  </script>
</body>
</html>
