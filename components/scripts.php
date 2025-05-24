<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Swiper JS -->
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-..." crossorigin="anonymous"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

<!-- Main JS Module -->
<script type="module" src="/js/main.js"></script>

<!-- Custom JavaScript -->
<script>
// Initialize AOS
AOS.init({ duration: 800, easing: 'ease-in-out', once: true });

// Set current year in footer
document.addEventListener('DOMContentLoaded', () => {
  const year = document.getElementById('current-year');
  if (year) year.textContent = new Date().getFullYear();
});

// Scroll to Top
document.addEventListener('DOMContentLoaded', () => {
  const scrollBtn = document.querySelector('.scroll-to-top, #scrollBtn, [data-scroll-top]');
  if (scrollBtn) {
    window.addEventListener("scroll", () => {
      scrollBtn.style.display = window.scrollY > 300 ? "flex" : "none";
    });
    scrollBtn.addEventListener("click", (e) => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }
});

// Dropdown and submenu hover
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.dropdown').forEach(dropdown => {
    dropdown.addEventListener('mouseenter', () => {
      const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
      if (toggle) bootstrap.Dropdown.getOrCreateInstance(toggle).show();
    });
    dropdown.addEventListener('mouseleave', () => {
      const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
      if (toggle) bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
    });
  });

  document.querySelectorAll('.dropdown-submenu').forEach(submenu => {
    submenu.addEventListener('mouseenter', () => {
      const submenuList = submenu.querySelector('.dropdown-menu');
      if (submenuList) submenuList.classList.add('show');
    });
    submenu.addEventListener('mouseleave', () => {
      const submenuList = submenu.querySelector('.dropdown-menu');
      if (submenuList) submenuList.classList.remove('show');
    });
  });

  // Submenu click behavior
  document.querySelectorAll('.dropdown-submenu > a').forEach(link => {
    link.addEventListener('click', function (e) {
      const submenu = this.nextElementSibling;
      if (submenu && submenu.classList.contains('dropdown-menu')) {
        const isSubmenuOpen = submenu.classList.contains('show');
        if (!isSubmenuOpen) window.location.href = this.getAttribute('href');
        e.preventDefault();
      }
    });
  });
});

// Tooltips and popovers
document.addEventListener('DOMContentLoaded', () => {
  [...document.querySelectorAll('[data-bs-toggle="tooltip"]')].forEach(el => new bootstrap.Tooltip(el));
  [...document.querySelectorAll('[data-bs-toggle="popover"]')].forEach(el => new bootstrap.Popover(el));
});

// Form validation
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.needs-validation').forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });
});

// Quantity input for cart/product
document.addEventListener('DOMContentLoaded', () => {
  const quantityBtns = document.querySelectorAll('[data-action="increase"], [data-action="decrease"]');
  quantityBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const input = document.getElementById('quantity');
      if (!input) return;
      let value = parseInt(input.value) || 1;
      if (btn.dataset.action === 'increase') input.value = value + 1;
      else if (btn.dataset.action === 'decrease' && value > 1) input.value = value - 1;
      const hiddenInput = document.getElementById('selected-quantity');
      if (hiddenInput) hiddenInput.value = input.value;
    });
  });
});

// Add to cart animation
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.add-to-cart, [data-action="add-to-cart"]').forEach(btn => {
    btn.addEventListener('click', function () {
      const originalText = this.innerHTML;
      this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding...';
      this.disabled = true;
      setTimeout(() => {
        this.innerHTML = originalText;
        this.disabled = false;
      }, 2000);
    });
  });
});

// Image lazy loading fallback
document.addEventListener('DOMContentLoaded', () => {
  if ('loading' in HTMLImageElement.prototype) {
    document.querySelectorAll('img[loading="lazy"]').forEach(img => {
      img.src = img.dataset.src || img.src;
    });
  } else {
    const script = document.createElement('script');
    script.src = 'https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver';
    document.head.appendChild(script);
  }
});

// Smooth scrolling
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        e.preventDefault();
        targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });
});

// Category filter highlight
document.addEventListener('DOMContentLoaded', () => {
  const currentUrl = window.location.pathname.toLowerCase();
  document.querySelectorAll('.cat-filter').forEach(link => {
    const match = link.dataset.cat;
    if (match && currentUrl.includes(match)) {
      link.classList.add('bg-black', 'text-white', 'border-black');
    }
  });
});

// Type filter highlight based on ?type= query param
document.addEventListener('DOMContentLoaded', () => {
  const urlParams = new URLSearchParams(window.location.search);
  const type = (urlParams.get('type') || '').toLowerCase().replace(/s$/, '').trim();

  document.querySelectorAll('.type-filter').forEach(btn => {
    const btnType = btn.dataset.type.toLowerCase().replace(/s$/, '').trim();
    if (btnType === type) {
      btn.classList.add('bg-black', 'text-white', 'border-black');
    }
  });
});


// Product grid sorting
document.addEventListener('DOMContentLoaded', function () {
  const productGrid = document.getElementById('product-grid');
  const sortSelect = document.getElementById('sortSelect');

  if (productGrid && sortSelect) {
    sortSelect.addEventListener('change', function () {
      const selectedValue = this.value;
      const products = Array.from(productGrid.children);

      if (selectedValue === 'low') {
        products.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
      } else if (selectedValue === 'high') {
        products.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
      } else {
        products.sort((a, b) => new Date(b.dataset.date) - new Date(a.dataset.date));
      }

      productGrid.innerHTML = '';
      products.forEach(product => productGrid.appendChild(product));
    });
  }
});

// Cart logic: subtotal, total, remove
document.addEventListener('DOMContentLoaded', () => {
  const updateCartTotal = () => {
    let total = 0;
    document.querySelectorAll('.cart-item').forEach(row => {
      const price = parseFloat(row.dataset.price);
      const qty = parseInt(row.querySelector('.quantity-input').value);
      const subtotal = price * qty;
      row.querySelector('.item-subtotal').textContent = subtotal.toFixed(2);
      total += subtotal;
    });
    const cartTotal = document.getElementById('cart-total');
    if (cartTotal) cartTotal.textContent = total.toFixed(2);
  };

  document.querySelectorAll('.cart-item').forEach(row => {
    const input = row.querySelector('.quantity-input');
    const btns = row.querySelectorAll('[data-action="increase"], [data-action="decrease"]');

    btns.forEach(btn => {
      btn.addEventListener('click', () => {
        let val = parseInt(input.value);
        if (btn.dataset.action === 'increase') input.value = val + 1;
        if (btn.dataset.action === 'decrease' && val > 1) input.value = val - 1;
        updateCartTotal();
      });
    });

    input.addEventListener('input', () => {
      if (parseInt(input.value) < 1) input.value = 1;
      updateCartTotal();
    });
  });

    document.querySelectorAll('.remove-item').forEach(btn => {
      btn.addEventListener('click', function () {
        const row = this.closest('.cart-item');
        if (row) {
          row.remove();
          updateCartTotal();
        }
      });
    });
  });