document.addEventListener('DOMContentLoaded', function() {
  // Get search form
  const searchForm = document.getElementById('searchForm');
  
  if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
      const searchQuery = document.getElementById('searchQuery').value.trim();
      
      // Don't submit empty searches
      if (!searchQuery) {
        e.preventDefault();
        return;
      }
      
      // Track search for analytics (optional)
      console.log('Search submitted:', searchQuery);
      
      // Form will submit normally to products.php with the query parameter
    });
  }
  
  // Handle popular search terms clicks
  document.querySelectorAll('.popular-search-term').forEach(term => {
    term.addEventListener('click', function(e) {
      e.preventDefault();
      const searchQuery = this.textContent.trim();
      document.getElementById('searchQuery').value = searchQuery;
      searchForm.submit();
    });
  });
});