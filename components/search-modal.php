<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="searchModalLabel">Search Products</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="searchForm" action="/products.php" method="get">
          <div class="input-group mb-3">
            <input type="text" class="form-control" id="searchQuery" name="q" placeholder="Search for products..." required>
            <button class="btn-primary" type="submit">
              <i class="fas fa-search"></i> Search
            </button>
          </div>
          <div class="mt-3">
            <p class="text-muted small">Popular searches: Oxford, Loafers, Boots, Chelsea</p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>