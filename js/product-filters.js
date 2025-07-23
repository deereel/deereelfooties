// product-filters.js - Handles product filtering functionality
document.addEventListener('DOMContentLoaded', function() {
  // Check if we're on a product category page
  const productGrid = document.getElementById('product-grid');
  if (!productGrid) return;

  // Get current page path to determine gender
  const path = window.location.pathname;
  let gender = 'all';
  
  if (path.includes('men')) {
    gender = 'men';
  } else if (path.includes('women')) {
    gender = 'women';
  }

  // Add event listeners to mobile type filter buttons
  const typeFilters = document.querySelectorAll('.type-filter');
  typeFilters.forEach(btn => {
    btn.addEventListener('click', function() {
      const type = this.dataset.type;
      filterByType(type, gender);
    });
  });

  // Filter products by type (for mobile buttons)
  window.filterByType = function(type, gender = 'all') {
    console.log('Filtering by type:', type, 'gender:', gender);
    
    // Update URL with the type parameter
    const url = new URL(window.location);
    url.searchParams.set('type', type);
    if (gender !== 'all') {
      url.searchParams.set('gender', gender);
    }
    window.history.pushState({}, '', url);
    
    // If product-grid.js is loaded, let it handle the filtering
    if (typeof initProductGrid === 'function') {
      initProductGrid();
    } else {
      // Fallback to basic type filtering
      applyTypeFilter(type, gender);
    }
    
    // Update active button styling
    typeFilters.forEach(btn => {
      if (btn.dataset.type === type) {
        btn.classList.add('bg-gray-800', 'text-white');
      } else {
        btn.classList.remove('bg-gray-800', 'text-white');
      }
    });
  };

  // Apply just the type filter (basic fallback)
  function applyTypeFilter(typeFilter, genderFilter = 'all') {
    if (!typeFilter || typeFilter === 'all') return;
    
    // Get all product cards
    const productCards = document.querySelectorAll('.product-card');
    let visibleCount = 0;
    
    productCards.forEach(card => {
      const cardType = (card.dataset.type || '').toLowerCase();
      const cardGender = (card.dataset.gender || '').toLowerCase();
      
      const typeMatch = cardType.includes(typeFilter.toLowerCase());
      const genderMatch = genderFilter === 'all' || cardGender === genderFilter;
      
      const show = typeMatch && genderMatch;
      
      // Show or hide the card
      card.style.display = show ? '' : 'none';
      if (show) visibleCount++;
    });
    
    // Show message if no products match filters
    const noResultsMessage = document.getElementById('no-results-message');
    if (noResultsMessage) {
      noResultsMessage.style.display = visibleCount === 0 ? 'block' : 'none';
    }
  }
  
  // Apply filters from URL on page load
  const urlParams = new URLSearchParams(window.location.search);
  const typeParam = urlParams.get('type');
  if (typeParam && typeParam !== 'all') {
    filterByType(typeParam, gender);
  }
});
