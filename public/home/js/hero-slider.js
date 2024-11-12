var swiper = new Swiper(".heroSider", {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 0,
    speed: 700,
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
    navigation: {
      nextEl: ".hero_slider_next",
      prevEl: ".hero_slider_prev",
    },
  });