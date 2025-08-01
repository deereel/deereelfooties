<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="searchModalLabel">Search Products</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="searchModalForm" action="/search-results.php" method="GET">
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="searchModalInput" name="query" placeholder="Search for products..." aria-label="Search" required>
            <button class="btn btn-primary" type="submit">
              <i class="fas fa-search"></i> Search
            </button>
          </div>
        </form>
        <div class="mt-4">
          <h6>Popular Searches:</h6>
          <div class="d-flex flex-wrap gap-2">
            <a href="/search-results.php?query=oxford" class="badge bg-secondary text-decoration-none">Oxford</a>
            <a href="/search-results.php?query=loafers" class="badge bg-secondary text-decoration-none">Loafers</a>
            <a href="/search-results.php?query=boots" class="badge bg-secondary text-decoration-none">Boots</a>
            <a href="/search-results.php?query=derby" class="badge bg-secondary text-decoration-none">Derby</a>
            <a href="/search-results.php?query=monk" class="badge bg-secondary text-decoration-none">Monk Straps</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Search Script -->
<script>
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
        const searchInput = document.getElementById('searchModalInput');
        if (searchInput) searchInput.focus();
      }, 500);
    });
  });
  
  // Handle search form submission
  const searchForm = document.getElementById('searchModalForm');
  if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
      const query = document.getElementById('searchModalInput').value.trim();
      if (!query) {
        e.preventDefault();
        alert('Please enter a search term');
      }
    });
  }
});
</script>