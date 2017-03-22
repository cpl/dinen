function login() {
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: 'request=login&data=' + formToJSON('#login-form')
  }).done(function (response) {
    if (response.status === Status.SUCCESS) {
      localStorage.setItem('JWT', response.data);
      loadPage('dashboard');
      initHeader();
    } else {
      alert(response.data);
    }
  });
  updateHeader();
  return false;
}