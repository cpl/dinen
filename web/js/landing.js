function Landing() {
  this.init = function () {
    $('#sign_in').click(function () {
      loadPage('login');
    });
    $('#go-to-search').click(function(){
      loadPage('search');
    });

    // The following code is provided by the Imperial theme.

    // Hero rotating texts
    $("#hero .rotating").Morphext({
      animation: "flipInX",
      separator: ",",
      speed: 2360
    });
    
    // Smoth scroll on page hash links
    $('a[href*="#"]:not([href="#"])').on('click', function() {
      if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'')
        && location.hostname == this.hostname) {
        var target = $(this.hash);
        if (target.length) {

          var top_space = 0;

          if( $('#header').length ) {
            top_space = $('#header').outerHeight();
          }

          $('html, body').animate({
            scrollTop: target.offset().top - top_space
          }, 750, 'easeInOutExpo');

          if ( $(this).parents('.nav-menu').length ) {
            $('.nav-menu .menu-active').removeClass('menu-active');
            $(this).closest('li').addClass('menu-active');
          }

          if ( $('body').hasClass('mobile-nav-active') ) {
            $('body').removeClass('mobile-nav-active');
            $('#mobile-nav-toggle i').toggleClass('fa-times fa-bars');
            $('#mobile-body-overly').fadeOut();
          }

          return false;
        }
      }
    });

    // End of Imperial code.
  }
}
