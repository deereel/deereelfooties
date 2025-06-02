export function initScroll() {
  const btns = document.querySelectorAll('.scroll-to-top, #scrollToTop');
  window.addEventListener('scroll', () => {
    btns.forEach(btn => btn.style.display = window.scrollY > 300 ? 'flex' : 'none');
  });
  btns.forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  });
}

export function initTooltips() {
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
  document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => new bootstrap.Popover(el));
}

export function initFooterYear() {
  const year = document.getElementById('current-year');
  if (year) year.textContent = new Date().getFullYear();
}
