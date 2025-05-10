document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const productCards = document.querySelectorAll('.product-card');
    const activeFilters = document.getElementById('active-filters');
    const selectedSizes = new Set();
    const selectedColors = new Set();
    const selectedPrices = [];
  
    const priceRanges = [
      { id: 'price1', range: [30000, 50000], label: '₦30k–50k' },
      { id: 'price2', range: [50000, 70000], label: '₦50k–70k' },
      { id: 'price3', range: [70000, 90000], label: '₦70k–90k' },
      { id: 'price4', range: [90000, Infinity], label: '₦90k+' }
    ];
  
    function renderTags() {
      if (!activeFilters) return;
      activeFilters.innerHTML = '';
      [...selectedSizes].forEach(s => addTag(`Size: EU ${s}`));
      [...selectedColors].forEach(c => addTag(`Color: ${c}`));
      selectedPrices.forEach(p => addTag(`Price: ${p.label}`));
    }
  
    function addTag(text) {
      const tag = document.createElement('span');
      tag.className = 'bg-gray-200 text-gray-700 px-3 py-1 rounded';
      tag.textContent = text;
      activeFilters.appendChild(tag);
    }
  
    function filterProducts() {
      renderTags();
      productCards.forEach(card => {
        const price = parseInt(card.dataset.price);
        const sizes = card.dataset.size.split(',');
        const color = card.dataset.color;
  
        const priceMatch = selectedPrices.length === 0 || selectedPrices.some(p => price >= p.range[0] && price < p.range[1]);
        const sizeMatch = selectedSizes.size === 0 || [...selectedSizes].some(s => sizes.includes(s));
        const colorMatch = selectedColors.size === 0 || selectedColors.has(color);
  
        card.style.display = (priceMatch && sizeMatch && colorMatch) ? 'block' : 'none';
      });
      
        paginateProducts();
      
    }
  
    // Price filter listeners
    priceRanges.forEach(({ id, range, label }) => {
      const el = document.getElementById(id);
      if (!el) return;
      el.addEventListener('change', () => {
        if (el.checked) selectedPrices.push({ range, label });
        else {
          const i = selectedPrices.findIndex(p => p.label === label);
          if (i !== -1) selectedPrices.splice(i, 1);
        }
        filterProducts();
      });
    });
  
    // Size filter listeners
    document.querySelectorAll('.size-filter').forEach(btn => {
      const size = btn.dataset.size;
      if (urlParams.get('size') === size) {
        selectedSizes.add(size);
        btn.classList.add('bg-gray-200');
      }
      btn.addEventListener('click', () => {
        btn.classList.toggle('bg-gray-200');
        if (selectedSizes.has(size)) selectedSizes.delete(size);
        else selectedSizes.add(size);
        filterProducts();
      });
    });
  
    // Color filter listeners
    document.querySelectorAll('.color-filter').forEach(dot => {
      const color = dot.dataset.color;
      if (urlParams.get('color') === color) {
        selectedColors.add(color);
        dot.classList.add('ring-2', 'ring-black');
      }
  
      dot.addEventListener('click', () => {
        dot.classList.toggle('ring-2');
        dot.classList.toggle('ring-black');
        if (selectedColors.has(color)) selectedColors.delete(color);
        else selectedColors.add(color);
        filterProducts();
      });
    });
  
    // Sort dropdown
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
      sortSelect.addEventListener('change', function () {
        const val = this.value;
        const cards = Array.from(productCards);
        const container = document.getElementById('product-grid');
  
        cards.sort((a, b) => {
          const pa = parseInt(a.dataset.price);
          const pb = parseInt(b.dataset.price);
          if (val === "low") return pa - pb;
          if (val === "high") return pb - pa;
          return 0;
        });
  
        cards.forEach(card => container.appendChild(card));
      });
    }
  
    // Pagination
    let perPage = 6;
    let currentPage = 1;

    function paginateProducts() {
    const visibleCards = Array.from(document.querySelectorAll('.product-card'))
        .filter(card => card.style.display !== 'none');

    const totalPages = Math.ceil(visibleCards.length / perPage);
    const pagination = document.querySelector('.pagination .flex');

    // Clear and rebuild pagination
    pagination.innerHTML = '';
    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('a');
        btn.href = "#";
        btn.textContent = i;
        btn.className = `px-4 py-2 border ${i === currentPage ? 'bg-black text-white' : 'hover:bg-gray-100'}`;
        btn.addEventListener('click', e => {
        e.preventDefault();
        currentPage = i;
        showPage(currentPage, visibleCards);
        });
        pagination.appendChild(btn);
    }

    showPage(currentPage, visibleCards);
    }

    function showPage(page, cards) {
    cards.forEach((card, i) => {
        card.style.display = (i >= (page - 1) * perPage && i < page * perPage) ? 'block' : 'none';
    });
    }

  
    // Initial Filter
    filterProducts();
  });
  