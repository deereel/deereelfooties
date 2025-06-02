export class UIManager {
  constructor() {
    this.initialized = false;
  }

  init() {
    console.log('Initializing UI Manager');
    this.initAOS();
    this.initScrollToTop();
    this.initBootstrapComponents();
    this.initCurrentYear();
    this.initialized = true;
  }

  initAOS() {
    if (typeof AOS !== 'undefined') {
      AOS.init({ 
        duration: 800, 
        easing: 'ease-in-out', 
        once: true 
      });
    }
  }

  initCurrentYear() {
    const year = document.getElementById('current-year');
    if (year) year.textContent = new Date().getFullYear();
  }

  initScrollToTop() {
    const scrollBtns = document.querySelectorAll('.scroll-to-top, #scrollBtn, #scrollToTop, [data-scroll-top]');
    
    window.addEventListener('scroll', () => {
      const shouldShow = window.scrollY > 300;
      scrollBtns.forEach(btn => {
        if (btn) btn.style.display = shouldShow ? "flex" : "none";
      });
    });
    
    scrollBtns.forEach(btn => {
      if (btn) {
        btn.addEventListener('click', (e) => {
          e.preventDefault();
          window.scrollTo({ top: 0, behavior: "smooth" });
        });
      }
    });
  }

  initBootstrapComponents() {
    // Dropdowns
    document.querySelectorAll('.dropdown').forEach(dropdown => {
      dropdown.addEventListener('mouseenter', () => {
        const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
        if (toggle && typeof bootstrap !== 'undefined') {
          bootstrap.Dropdown.getOrCreateInstance(toggle).show();
        }
      });
      dropdown.addEventListener('mouseleave', () => {
        const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
        if (toggle && typeof bootstrap !== 'undefined') {
          bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
        }
      });
    });

    // Tooltips and Popovers
    if (typeof bootstrap !== 'undefined') {
      document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
      document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => new bootstrap.Popover(el));
    }
  }

  showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }
  }

  hideModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    }
  }
}