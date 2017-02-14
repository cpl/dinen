/*
function validatePassword() {
  if ($('#password').val() === $('#password_confirmation').val()) {
    confirm_password.setCustomValidity('');
  } else {
    confirm_password.setCustomValidity("Passwords Don't Match");
  }
}
*/
function register() {
  // http://stackoverflow.com/questions/11338774/serialize-form-data-to-json
  var data = JSON.stringify($('#registerForm').serializeArray());
  $.ajax({
    url: 'api/v1/api.php',
    type: 'GET',
    data: 'request=register&data=' + data
  }).done(function(response) {
    alert(response);
  });
  return false;
}
function login() {
  var formData = { email : $("#email").val(), password : $("#password").val()};
  $.ajax({
      url: 'php_scripts/login.php',
      type: 'POST',
      data: formData
  }).done(function() {
    window.location.replace("restaurants.php");
  });
  return false;
}