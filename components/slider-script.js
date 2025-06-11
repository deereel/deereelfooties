// Slider/Swiper functionality
document.addEventListener('DOMContentLoaded', function() {
  // Initialize hero swiper if it exists
  if (typeof Swiper !== 'undefined' && document.querySelector('.hero-swiper')) {
    try {
      const swiper = new Swiper('.hero-swiper', {
        loop: true,
        autoplay: {
          delay: 5000,
        },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        }
      });
      console.log('Hero swiper initialized');
    } catch (error) {
      console.error('Error initializing Swiper:', error);
    }
  }
});