export class UIManager {
  constructor() {
    this.initialized = false;
  }

  init() {
    console.log('Initializing UI Manager');
    this.initScrollToTop();
    this.initCurrentYear();
    this.initialized = true;
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