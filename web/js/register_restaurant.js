function create_restaurant() {
  const JWT = getJWT();
  if(JWT == null) {
    alert("You must be logged in to create restaurants.");
    return false;
  }
  var data = formToDict('#createForm');
  data['request'] = 'create_restaurant';
  data['jwt'] = JWT;
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: data
  }).done(function (response) {
    if(response.status == 'success')
      loadPage('dashboard', true, {});
    }
  );
  return false;
}

function validatePassword() {
  var password = document.getElementById('password');
  var confirm_password = document.getElementById('password_confirmation');
  console.log('merge');
  if (password.value != confirm_password.value) {
    confirm_password.setCustomValidity('Passwords do not match.');
  } else {
    confirm_password.setCustomValidity('');
  }
}

function initPage() {
}