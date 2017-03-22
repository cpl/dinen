if($('.navbar').length > 0 && $('.navbar').hasClass('navbar-fixed-top')) {
  $(window).on('scroll load resize', function () {
    checkScroll();
  });
}

$('.navbar-toggle').click(function() {
  if ($('.navbar-toggle').hasClass('collapsed')) {
    arguments.callee.opaqueNavbar = $('.navbar-toggle').hasClass('opaque');
    turnNavbarOpaque();
  } else if (!arguments.callee.opaqueNavbar) {
    turnNavbarTransparent();
  }
});

function checkScroll() {
  // The point where the navbar changes in px.
  var startY = $('.navbar').height() * 2;
  if ($(window).scrollTop() > startY) {
    turnNavbarOpaque();
  } else {
    turnNavbarTransparent();
  }
}

function turnNavbarOpaque() {
  $('.navbar').addClass("opaque");
}

function turnNavbarTransparent() {
  $('.navbar').removeClass("opaque");
}
