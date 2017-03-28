function register(event) {
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
    data: 'request=register&data=' + formToJSON('#register-form')
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