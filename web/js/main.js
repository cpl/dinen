var apiURL = 'api/v1/api.php';
function register() {
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: 'request=register&data=' + formToJSON('#registerForm')
  }).done(function(response) {
    if (response == 'success') {
      window.location.replace("restaurants.php");
    } else {
      alert(response);
    }
  });
  return false;
}
function login() {
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: 'request=login&data=' + formToJSON('#loginForm')
  }).done(function(response) {
    if (response == 'success') {
      window.location.replace("restaurants.php");
    } else {
      alert(response);
    }
  });
  return false;
}
function formToJSON(form) {
  var json = {};
  $(form).find(':input[name]:enabled').each( function() {
    var self = $(this);
    var name = self.attr('name');
    if (json[name]) {
      json[name] = json[name] + ',' + self.val();
    } else {
      json[name] = self.val();
    }
  });
  return JSON.stringify(json);
}
/*
function validatePassword() {
  if ($('#password').val() === $('#password_confirmation').val()) {
      confirm_password.setCustomValidity('');
    } else {
      confirm_password.setCustomValidity("Passwords Don't Match");
    }
}
function showMsgAlert(component, message) {
  $.ajax({method: "GET", url: "components/" + component, success: function (data) {
    data = data.toString().replace("#msg#", message);
    $("#msgDiv").replaceWith(data);
    $("#msgDiv").fadeIn(800);
  }});
}
*/