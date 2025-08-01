<!-- Search Modal -->
<div id="searchModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
  <div class="bg-white rounded-lg max-w-lg w-full mx-4 p-6">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-xl font-medium">Search Products</h3>
      <button id="closeSearchModal" class="text-gray-500 hover:text-black">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <form id="searchModalForm" action="/search-results.php" method="GET">
      <div class="flex mb-4">
        <input type="text" id="searchModalInput" name="query" placeholder="Search for products..." 
               class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <button type="submit" class="px-6 py-2 bg-black text-white rounded-r-lg hover:bg-gray-800 transition">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </form>
    <div>
      <h6 class="font-medium mb-2">Popular Searches:</h6>
      <div class="flex flex-wrap gap-2">
        <a href="/search-results.php?query=oxford" class="px-3 py-1 bg-gray-100 rounded-full text-sm hover:bg-gray-200 transition">Oxford</a>
        <a href="/search-results.php?query=loafers" class="px-3 py-1 bg-gray-100 rounded-full text-sm hover:bg-gray-200 transition">Loafers</a>
        <a href="/search-results.php?query=boots" class="px-3 py-1 bg-gray-100 rounded-full text-sm hover:bg-gray-200 transition">Boots</a>
        <a href="/search-results.php?query=derby" class="px-3 py-1 bg-gray-100 rounded-full text-sm hover:bg-gray-200 transition">Derby</a>
        <a href="/search-results.php?query=sneakers" class="px-3 py-1 bg-gray-100 rounded-full text-sm hover:bg-gray-200 transition">Sneakers</a>
      </div>
    </div>
  </div>
</div>

<!-- Search Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchModal = document.getElementById('searchModal');
  const searchInput = document.getElementById('searchModalInput');
  const closeBtn = document.getElementById('closeSearchModal');
  const searchForm = document.getElementById('searchModalForm');
  
  // Get all search buttons/icons
  const searchButtons = document.querySelectorAll('.search-btn, .fa-search, [data-search]');
  
  // Open modal
  searchButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      searchModal.classList.remove('hidden');
      setTimeout(() => {
        if (searchInput) searchInput.focus();
      }, 100);
    });
  });
  
  // Close modal
  closeBtn.addEventListener('click', () => {
    searchModal.classList.add('hidden');
  });
  
  // Close on outside click
  searchModal.addEventListener('click', (e) => {
    if (e.target === searchModal) {
      searchModal.classList.add('hidden');
    }
  });
  
  // Handle form submission
  searchForm.addEventListener('submit', function(e) {
    const query = searchInput.value.trim();
    if (!query) {
      e.preventDefault();
      alert('Please enter a search term');
    }
  });
  
  // Close modal on escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
      searchModal.classList.add('hidden');
    }
  });
});
</script>