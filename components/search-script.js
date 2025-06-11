// Search functionality
document.addEventListener('DOMContentLoaded', function() {
  // Get all search buttons/icons
  const searchButtons = document.querySelectorAll('.search-btn, .fa-search');
  
  // Add click event to all search buttons/icons
  searchButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const searchModal = new bootstrap.Modal(document.getElementById('searchModal'));
      searchModal.show();
      setTimeout(() => {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) searchInput.focus();
      }, 500);
    });
  });
  
  // Handle search form submission
  const searchForm = document.getElementById('searchForm');
  if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
      const query = document.getElementById('searchInput').value.trim();
      if (!query) {
        e.preventDefault();
        alert('Please enter a search term');
      }
    });
  }
});