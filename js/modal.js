// /js/modal.js
export function showAddToCartModal(item) {
  const modal = document.getElementById('added-to-cart-modal');
  if (!modal) return;

  const modalImage = document.getElementById('modal-product-image');
  const modalName = document.getElementById('modal-product-name');
  const modalVariant = document.getElementById('modal-product-variant');
  const modalPrice = document.getElementById('modal-product-price');

  if (modalImage) modalImage.src = item.image;
  if (modalName) modalName.textContent = item.name;
  if (modalVariant) modalVariant.textContent = `Size: ${item.size} | Width: ${item.width} | Color: ${item.color}`;
  if (modalPrice) modalPrice.textContent = `₦${item.price.toLocaleString()}`;

  modal.classList.remove('hidden');
  document.body.style.overflow = 'hidden';

  setTimeout(() => {
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
  }, 5000);
}
