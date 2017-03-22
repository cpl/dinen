$(window).on('load', function () {
  // The following code is provided by the Imperial theme.

  // Initiate the wowjs
  new WOW().init();

  // Back to top button
  $(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
      $('.back-to-top').fadeIn('slow');
    } else {
      $('.back-to-top').fadeOut('slow');
    }
  });
  $('.back-to-top').click(function(){
    $('html, body').animate({scrollTop : 0}, 750, 'easeInOutExpo');
    return false;
  });

  // End of Imperial code.

  // TODO: look at the URL and load the appropriate page (GET?)
  if (isManager()) {
    loadPage('dashboard');
  } else {
    loadPage('landing');
  }
});

function signUp(event) {
  var ref = $(this).find("[required]");
  $(ref).each(function () {
    if ($(this).val() === '') {
      alert("Required field should not be blank.");
      $(this).focus();
      event.preventDefault();
      return false;
    }
  });
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: 'request=register&data=' + formToJSON('#sign_up_form')
  }).done(function (response) {
    if (response == 'success') {
      alert('Registration email was sent to your email');
      window.location.replace('index.html');
    } else {
      alert(response);
    }
  });
  return false;
}