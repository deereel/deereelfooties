<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Swiper JS -->
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

<!-- Mobile Navigation -->
<script src="/js/mobile-nav.js"></script>

<!-- Filters JS -->
<script src="/js/filters.js"></script>

<!-- Main JS Module - Load this first -->
<script type="module" src="/js/main.js"></script>

<!-- Modal JS - Load this after main.js -->
<script defer src="/js/modal.js"></script>

<!-- Custom JavaScript - Only basic functionality that doesn't conflict with main.js -->
<script>
// Initialize AOS
AOS.init({ duration: 800, easing: 'ease-in-out', once: true });

// Set current year in footer
document.addEventListener('DOMContentLoaded', () => {
  const year = document.getElementById('current-year');
  if (year) year.textContent = new Date().getFullYear();
});

// Scroll to Top (Universal handler for all scroll buttons)
document.addEventListener('DOMContentLoaded', () => {
  const scrollBtns = document.querySelectorAll('.scroll-to-top, #scrollBtn, #scrollToTop, [data-scroll-top]');
  
  const handleScrollVisibility = () => {
    const shouldShow = window.scrollY > 300;
    scrollBtns.forEach(btn => {
      if (btn) {
        btn.style.display = shouldShow ? "flex" : "none";
      }
    });
  };

  window.addEventListener("scroll", handleScrollVisibility);
  
  scrollBtns.forEach(btn => {
    if (btn) {
      btn.addEventListener("click", (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: "smooth" });
      });
    }
  });
});

// Dropdown and submenu hover
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.dropdown').forEach(dropdown => {
    dropdown.addEventListener('mouseenter', () => {
      const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
      if (toggle && typeof bootstrap !== 'undefined') bootstrap.Dropdown.getOrCreateInstance(toggle).show();
    });
    dropdown.addEventListener('mouseleave', () => {
      const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
      if (toggle && typeof bootstrap !== 'undefined') bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
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

  // Make dropdown submenu items clickable
  document.querySelectorAll('.dropdown-submenu > a').forEach(link => {
    link.addEventListener('click', function(e) {
      // Allow the link to work normally (navigate to the href)
      // Don't prevent default here
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

// Type filter highlight based on ?type= query param
document.addEventListener('DOMContentLoaded', () => {
  const urlParams = new URLSearchParams(window.location.search);
  const type = (urlParams.get('type') || '').toLowerCase().replace(/s$/, '').trim();

  document.querySelectorAll('.type-filter').forEach(btn => {
    const btnType = btn.dataset.type.toLowerCase().replace(/s$/, '').trim();
    if (btnType === type) {
      btn.classList.add('selected');
    }
  });
});

// Category filter highlight
document.addEventListener('DOMContentLoaded', () => {
  const currentUrl = window.location.pathname.toLowerCase();
  document.querySelectorAll('.cat-filter').forEach(link => {
    const match = link.dataset.cat;
    if (match && currentUrl.includes(match)) {
      link.classList.add('selected');
    }
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

// Global function for type filtering (called from HTML)
window.filterByType = function(type) {
  window.location.href = window.location.pathname + '?type=' + type;
};
</script>