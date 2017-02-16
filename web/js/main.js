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

function create_restaurant() {
  var data = formToDict('#createForm');
  data['request'] = 'register_restaurant';
  console.log(data);
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: data
  }).done(function(response) {
    if (response == 'success') {
      window.location.replace("restaurants.php");
    } else {
      alert(response);
    }
  });
  return false;
}

// turns input from form into JSON format
function formToJSON(form) {
  return JSON.stringify(formToDict(form, ':input[name]:enabled'));
}

// Turns form into dictionary
function formToDict(form) {
  var dict = {};
  $(form).find(':input[name]:enabled').each(function() {
    var self = $(this);
    var name = self.attr('name');
    if (dict[name]) {
      dict[name] = dict[name] + ',' + self.val();
    } else {
      dict[name] = self.val();
    }
  });
  return dict;
}

function validatePassword() {
  if ($('#password').val() !== $('#password_confirmation').val()) {
    confirm_password.setCustomValidity('Passwords don\'t match');
  } else if ($('#password').val.length() < 8) {
    confirm_password.setCustomValidity('Password length is less than 8');
  } else {
    confirm_password.setCustomValidity('');
  }
}

function showMsgAlert(component, message) {
  $.ajax({method: "GET", url: "components/" + component, success: function (data) {
    data = data.toString().replace("#msg#", message);
    $("#msgDiv").replaceWith(data);
    $("#msgDiv").fadeIn(800);
  }});
}
