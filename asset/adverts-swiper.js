(function ($, Drupal) {
  Drupal.behaviors.initAdvertsSwiper = {
    attach: function (context, settings) {
      var swiper = new Swiper('.swiper-container', {
        autoHeight: true, //enable auto height
        spaceBetween: 20,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      });
      console.log(swiper);
    }
  };
})(jQuery, Drupal);
