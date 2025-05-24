// ui.js
export const $ = (sel, ctx = document) => ctx.querySelector(sel);
export const $$ = (sel, ctx = document) => ctx.querySelectorAll(sel);

export const handleSelection = (groupClass, hiddenInputId) => {
  const options = document.querySelectorAll(groupClass);
  options.forEach(option => {
    option.addEventListener('click', () => {
      options.forEach(o => o.classList.remove('selected', 'ring-4', 'ring-black', 'bg-dark', 'text-white'));
      option.classList.add('selected', 'ring-4', 'ring-black', 'bg-dark', 'text-white');
      const type = groupClass.includes('color') ? 'color' : groupClass.includes('size') ? 'size' : 'width';
      document.getElementById(hiddenInputId).value = option.dataset[type];
    });
  });
};

export const initMobileMenu = () => {
  const toggle = $('#mobileMenuToggle'), close = $('#closeMobileMenu'), overlay = $('.mobile-nav-overlay');
  if (!toggle || !close || !overlay) return;
  toggle.addEventListener('click', () => overlay.classList.replace('hidden', 'visible'));
  close.addEventListener('click', () => overlay.classList.replace('visible', 'hidden'));
  overlay.addEventListener('click', e => {
    if (e.target === overlay) overlay.classList.replace('visible', 'hidden');
  });
};
