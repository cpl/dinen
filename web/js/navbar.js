if($('.navbar').length > 0){
  $(window).on('scroll load resize', function () {
    checkScroll();
  });
}

function checkScroll() {
  // The point where the navbar changes in px.
  var startY = $('.navbar').height() * 2;
  if ($(window).scrollTop() > startY) {
    $('.navbar').addClass("scrolled");
  } else {
    $('.navbar').removeClass("scrolled");
  }
}