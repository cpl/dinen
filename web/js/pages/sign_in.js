function signIn() {
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: 'request=login&data=' + formToJSON('#sign_in_form')
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