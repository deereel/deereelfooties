document.addEventListener('DOMContentLoaded', function() {
  // Get URL parameters
  const urlParams = new URLSearchParams(window.location.search);
  const typeParam = urlParams.get('type');
  
  if (typeParam) {
    // Normalize the type parameter (handle singular/plural)
    let normalizedType = typeParam.toLowerCase();
    
    // Handle singular/plural variations
    if (normalizedType.endsWith('s')) {
      normalizedType = normalizedType.slice(0, -1); // Remove trailing 's'
    }
    
    // Special cases
    if (normalizedType === 'loafer') normalizedType = 'loafer';
    if (normalizedType === 'oxford') normalizedType = 'oxford';
    if (normalizedType === 'derby') normalizedType = 'derby';
    if (normalizedType === 'monk') normalizedType = 'monk';
    if (normalizedType === 'zipper') normalizedType = 'zipper';
    if (normalizedType === 'wingtip') normalizedType = 'wingtip';
    if (normalizedType === 'chelsea') normalizedType = 'chelsea';
    if (normalizedType === 'captoe') normalizedType = 'captoe';
    if (normalizedType === 'jodhpur') normalizedType = 'jodhpur';
    if (normalizedType === 'balmoral') normalizedType = 'balmoral';
    if (normalizedType === 'slipper') normalizedType = 'slipper';
    if (normalizedType === 'mule') normalizedType = 'mule';
    
    // Apply filters to product grid
    filterProductsByType(normalizedType);
    
    // Update page title and active filters
    updatePageForFilter(normalizedType);
  }
});

function filterProductsByType(type) {
  const productGrid = document.getElementById('product-grid');
  if (!productGrid) return;
  
  const products = productGrid.querySelectorAll('.product-card');
  let visibleCount = 0;
  
  products.forEach(product => {
    const productTypes = (product.dataset.type || '').toLowerCase().split(',');
    
    // Check if product matches the filter
    const matches = productTypes.some(productType => {
      // Normalize product type (handle singular/plural)
      let normalizedProductType = productType.trim();
      if (normalizedProductType.endsWith('s')) {
        normalizedProductType = normalizedProductType.slice(0, -1);
      }
      
      return normalizedProductType === type;
    });
    
    if (matches) {
      product.style.display = '';
      visibleCount++;
    } else {
      product.style.display = 'none';
    }
  });
  
  // Show message if no products match
  const noProductsMessage = document.getElementById('no-products-message');
  if (visibleCount === 0) {
    if (!noProductsMessage) {
      const message = document.createElement('div');
      message.id = 'no-products-message';
      message.className = 'col-span-full text-center py-8';
      message.innerHTML = `<p>No products found matching "${type}".</p>`;
      productGrid.appendChild(message);
    }
  } else if (noProductsMessage) {
    noProductsMessage.remove();
  }
}

function updatePageForFilter(type) {
  // Update page title
  const titleElement = document.querySelector('h1');
  if (titleElement) {
    const baseTitle = titleElement.textContent.split(' - ')[0];
    titleElement.textContent = `${baseTitle} - ${capitalizeFirstLetter(type)}`;
  }
  
  // Update active filters display
  const activeFiltersContainer = document.getElementById('active-filters');
  if (activeFiltersContainer) {
    activeFiltersContainer.innerHTML = `
      <div class="inline-flex items-center bg-gray-100 px-3 py-1 rounded">
        <span>Type: ${capitalizeFirstLetter(type)}</span>
        <a href="${window.location.pathname}" class="ml-2 text-gray-500 hover:text-black">×</a>
      </div>
    `;
  }
  
  // Highlight the corresponding filter button if it exists
  document.querySelectorAll('.type-filter').forEach(btn => {
    btn.classList.remove('selected');
    
    const btnType = (btn.dataset.type || '').toLowerCase();
    let normalizedBtnType = btnType;
    if (normalizedBtnType.endsWith('s')) {
      normalizedBtnType = normalizedBtnType.slice(0, -1);
    }
    
    if (normalizedBtnType === type) {
      btn.classList.add('selected');
    }
  });
}

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}