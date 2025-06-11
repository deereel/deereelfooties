<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<link rel="stylesheet" href="/css/slider.css" />

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<!-- Swiper Initialization -->
<script>
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
</script>