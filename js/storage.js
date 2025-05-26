export const getCart = () => JSON.parse(localStorage.getItem('DRFCart')) || [];
export const setCart = (cart) => localStorage.setItem('DRFCart', JSON.stringify(cart));
