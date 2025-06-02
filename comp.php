<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Swiper JS -->
<link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

<!-- Main JS Module -->
<script type="module" src="/js/main.js"></script>

<!-- Custom JavaScript -->
<script>
// Initialize AOS
AOS.init({ duration: 800, easing: 'ease-in-out', once: true });

// Initialize Swiper
document.addEventListener('DOMContentLoaded', () => {
  // Check if Swiper container exists
  const swiperContainer = document.querySelector('.swiper');
  if (swiperContainer) {
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
      effect: 'fade',
      fadeEffect: {
        crossFade: true
      },
      on: {
        init: function () {
          console.log('Swiper initialized');
        },
        slideChange: function () {
          console.log('Slide changed');
        }
      }
    });
  }
});

// Set current year in footer
document.addEventListener('DOMContentLoaded', () => {
  const year = document.getElementById('current-year');
  if (year) year.textContent = new Date().getFullYear();
});

// Rest of your existing code...
// (Keep all the other functions as they were)
</script>

<!-- Add this CSS to fix background image issues -->
<style>
.swiper-slide {
    background-size: cover !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    min-height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.swiper-slide::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3); /* Optional overlay */
    z-index: 1;
}

.swiper-slide .slide-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
}
</style>
