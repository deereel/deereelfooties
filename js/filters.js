export function initFilters() {
  const currentUrl = window.location.pathname.toLowerCase();

  document.querySelectorAll('.cat-filter').forEach(link => {
    const match = link.dataset.cat;
    if (currentUrl.includes(match)) {
      link.classList.add('bg-black', 'text-white', 'border-black');
    }
  });
}
