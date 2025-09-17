(function () {
    'use strict';

    // deault swiper
    var swiper = new Swiper(".page-header-swiper", {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 50,
      speed: 3000, 
      autoplay: {
        delay: 200,
        disableOnInteraction: false,
      },
      breakpoints: {
        320: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        500: {
          slidesPerView: 4,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 4,
          spaceBetween: 20,
        },
        992: {
          slidesPerView: 4,
          spaceBetween: 20,
        },
        1200: {
          slidesPerView: 5,
          spaceBetween: 20,
        },
        1400: {
          slidesPerView: 6,
          spaceBetween: 20,
        },
        1600: {
          slidesPerView: 6,
          spaceBetween: 20,
        },
        1800: {
          slidesPerView: 8,
          spaceBetween: 20,
        },
      },
    });
})();

