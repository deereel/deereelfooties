<<<<<<< HEAD
<!-- jQuery (required by Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/4cfdaa33e8.js" crossorigin="anonymous"></script>

<!-- Swiper JS (single version) -->
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Google Client Library for Sign-In -->
<script src="https://accounts.google.com/gsi/client" async defer></script>

<!-- Custom modules - Load these first -->
<script src="/js/modules/auth-global.js"></script>
<script src="/js/modules/cart-global.js"></script>
<script src="/js/modules/product-global.js"></script>
<script src="/js/modules/ui-global.js"></script>

<!-- Main JS - Load after modules -->
<script src="/js/main.js" type="text/javascript"></script>

<!-- Page-specific scripts -->
<?php if (isset($page)): ?>
    <?php if ($page === 'customize'): ?>
        <script src="/js/customize.js" type="text/javascript"></script>
    <?php endif; ?>
    
    <?php if ($page === 'cart' || $page === 'checkout'): ?>
        <script src="/js/cart-page.js" type="text/javascript"></script>
    <?php endif; ?>
    
    <?php if ($page === 'dashboard'): ?>
        <script src="/js/dashboard.js" type="text/javascript"></script>
    <?php endif; ?>
<?php endif; ?>

<!-- Other JS files -->
<script src="/js/mobile-nav.js" type="text/javascript"></script>
<script src="/js/filters.js" type="text/javascript"></script>
<script src="/js/modal.js" type="text/javascript"></script>





<!-- Critical CSS for Slider -->
<style>
/* Hero Swiper Styles */
.hero-swiper {
  width: 100%;
  height: 80vh;
  position: relative;
}

.hero-swiper .swiper-slide {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.hero-swiper .slide-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  z-index: 1;
  transition: opacity 0.3s ease;
}

.hero-swiper .slide-bg.loading {
  background-color: #f3f4f6;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 300'%3E%3Crect width='400' height='300' fill='%23f3f4f6'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%23d1d5db' font-family='Arial, sans-serif' font-size='16'%3ELoading...%3C/text%3E%3C/svg%3E");
}

.hero-swiper .slide-bg::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4);
  z-index: 2;
}

.hero-swiper .slide-content {
  position: relative;
  z-index: 3;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  color: white;
}

.hero-swiper .swiper-pagination {
  bottom: 20px !important;
  z-index: 10;
}

.hero-swiper .swiper-pagination-bullet {
  background: white;
  opacity: 0.5;
  width: 12px;
  height: 12px;
}

.hero-swiper .swiper-pagination-bullet-active {
  opacity: 1;
  background: white;
}

.hero-swiper .swiper-lazy-preloader {
  width: 42px;
  height: 42px;
  position: absolute;
  left: 50%;
  top: 50%;
  margin-left: -21px;
  margin-top: -21px;
  z-index: 10;
  transform-origin: 50%;
  animation: swiper-preloader-spin 1s infinite linear;
  box-sizing: border-box;
  border: 4px solid rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  border-top: 4px solid #fff;
}

@keyframes swiper-preloader-spin {
  100% {
    transform: rotate(360deg);
  }
}

/* Lazy loading styles */
.lazy {
  opacity: 0;
  transition: opacity 0.3s;
}

.lazy.loaded {
  opacity: 1;
}

/* Prevent Tailwind from interfering */
.hero-swiper * {
  box-sizing: border-box;
}
</style>



<!-- Custom JavaScript - Only basic functionality that doesn't conflict with main.js -->
=======
<!-- External Libraries -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- Fixed Bootstrap with correct integrity -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



<!-- Fallback: DOM-ready utilities -->
>>>>>>> parent of f36b17c (checkout page)
<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (typeof AOS !== 'undefined') AOS.init({ duration: 800, easing: 'ease-in-out', once: true });

<<<<<<< HEAD

// Lazy Loading Implementation
class LazyLoader {
  constructor() {
    this.imageObserver = null;
    this.init();
  }

  init() {
    if ('IntersectionObserver' in window) {
      this.imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            this.loadImage(entry.target);
            observer.unobserve(entry.target);
          }
        });
      }, {
        rootMargin: '50px 0px',
        threshold: 0.01
      });

      this.observeImages();
    } else {
      // Fallback for older browsers
      this.loadAllImages();
    }
  }

  observeImages() {
    const lazyImages = document.querySelectorAll('img.lazy');
    lazyImages.forEach(img => this.imageObserver.observe(img));
  }

  loadImage(img) {
    const src = img.getAttribute('data-src');
    if (!src) return;

    img.src = src;
    img.classList.add('loaded');
    img.removeAttribute('data-src');
  }

  loadAllImages() {
    const lazyImages = document.querySelectorAll('img.lazy');
    lazyImages.forEach(img => this.loadImage(img));
  }
}

// Initialize Swiper with delay to ensure DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  // Wait a bit for all resources to load
  setTimeout(() => {
    initializeHeroSwiper();
  }, 100);
});

function initializeHeroSwiper() {
  // Check if Swiper is loaded
  if (typeof Swiper === 'undefined') {
    console.error('Swiper is not loaded!');
    return;
  }

  // Check if hero swiper container exists
  const heroSwiperContainer = document.querySelector('.hero-swiper');
  if (!heroSwiperContainer) {
    console.log('Hero swiper container not found');
    return;
  }

  try {
    const heroSwiper = new Swiper('.hero-swiper', {
      // Basic settings
      loop: true,
      speed: 1000,
      
      // Autoplay
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      
      // Pagination
      pagination: {
        el: '.hero-swiper .swiper-pagination',
        clickable: true,
        dynamicBullets: false,
      },
      
      // Effects
      effect: 'slide',
      
      // Callbacks
      on: {
        init: function () {
          console.log('Hero Swiper initialized successfully');
          // Ensure first slide is visible
          this.update();
        },
        slideChange: function () {
          console.log('Slide changed to:', this.activeIndex);
        },
        transitionStart: function() {
          console.log('Transition started');
        },
        transitionEnd: function() {
          console.log('Transition ended');
        }
      }
    });

    // Force update after initialization
    setTimeout(() => {
      heroSwiper.update();
    }, 50);

  } catch (e) {
    console.error('Error initializing Hero Swiper:', e);
  }
}



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
=======
    const year = document.getElementById('current-year');
    if (year) year.textContent = new Date().getFullYear();
>>>>>>> parent of f36b17c (checkout page)
  });
</script>

<<<<<<< HEAD
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

document.addEventListener('DOMContentLoaded', () => {
  // Initialize Swiper
  const swiper = new Swiper('.swiper', {
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });
});
</script>

</script>

=======
<!-- Main Modular JavaScript -->
<script type="module" src="/js/main.js"></script>
>>>>>>> parent of f36b17c (checkout page)
