var swiper = new Swiper(".product-slider", {
    loop: false,
    slidesPerView: 1,
    spaceBetween: 0,
    speed: 700,
    navigation: {
      nextEl: ".pd_slider_next",
      prevEl: ".pd_slider_prev",
    },
    pagination: {
        el: ".swiper-pagination",
    },
    hashNavigation: {
        watchState: true,
    },
});

var currentIndex = swiper.activeIndex;

// You can also listen to slide change events to get the index dynamically
swiper.on('slideChange', function () {
 // Remove active class from all pagination items
 document.querySelectorAll('.pro-duct-slides .left-imgs a').forEach(function (el) {
    el.classList.remove('active');
  });
  // Add active class to the current pagination item
  var activeIndex = swiper.activeIndex;
  document.querySelector('.pro-duct-slides .left-imgs a:nth-child(' + (activeIndex + 1) + ')').classList.add('active');
});