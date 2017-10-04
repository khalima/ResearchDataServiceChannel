(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.hyMobileMenuv2 = {
    attach: function (context, settings) {
      // var medium = settings.omega.mediaQueries['layout-medium'];
      var mainmenuv2 = $('#block-mainnavigation');
      var mainmenuv2Ul = $('#block-mainnavigation > ul');
      var mainmenuv2Expand = mainmenuv2.find('.main-menu-v2__expand');

/*
      //Media Queries Matching
      if (typeof matchMedia !== 'undefined') {
        var mq = window.matchMedia(medium);
        mq.addListener(removeMenuJs);
      }

      function removeMenuJs(mq) {
        if (mq.matches === true) {
          mainmenuv2.find('li.is-open').removeClass('is-open');
          mainmenuv2.find('ul.menu').removeAttr('style');
        }
      }
*/
      // Overlay toggle
      var overlayToggle = $('.overlay-toggle');

      overlayToggle.click(function (event) {
        overlayToggle.removeClass('is-closing');
        if (overlayToggle.hasClass('is-active')) {
          overlayToggle.addClass('is-closing');
        }
        overlayToggle.toggleClass('is-active');

        // Delay for closing, not needed for now.
        // if ($('.l-overlay').hasClass('is-open')) {
        //   $('.l-overlay').addClass('is-closing');
        //   setTimeout(function() {
        //     $('.l-overlay').removeClass('is-closing').toggleClass('is-open');
        //   }, 250);
        // } else {
        $('.l-overlay').toggleClass('is-open');
        // }
        $('body').toggleClass('is-noscroll').toggleClass('is-overlayed');
      });

      // Check if list element has active-trail class and open it
      mainmenuv2.find('li.is-lvl1.active-trail').toggleClass('is-open').find('> ul').css('display', 'block');

      mainMenuInit();

      mainmenuv2Expand.click(function (event) {
        $(this).parent('li').toggleClass('is-open');
        $(this).siblings('ul.menu').slideToggle(200);
        mainMenuInit();
      });

      function mainMenuInit() {
        if (mainmenuv2Ul.find('li.is-open').length === 0) {
          mainmenuv2Ul.addClass('is-default');
        }
        else {
          mainmenuv2Ul.removeClass('is-default');
        }
      }
    }
  };
})(jQuery, Drupal);
