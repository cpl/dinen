var apiURL = 'api/v1/api.php';

var themes = {
  "default": "//bootswatch.com/amelia/bootstrap.min.css",
  "amelia": "//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css",
  "cerulean": "//bootswatch.com/cerulean/bootstrap.min.css",
  "cosmo": "//bootswatch.com/cosmo/bootstrap.min.css",
  "cyborg": "//bootswatch.com/cyborg/bootstrap.min.css",
  "flatly": "//bootswatch.com/flatly/bootstrap.min.css",
  "journal": "//bootswatch.com/journal/bootstrap.min.css",
  "simplex": "//bootswatch.com/simplex/bootstrap.min.css",
  "slate": "//bootswatch.com/slate/bootstrap.min.css",
  "spacelab": "//bootswatch.com/spacelab/bootstrap.min.css",
  "united": "//bootswatch.com/united/bootstrap.min.css"
};

$(function () {
  var themesheet = $('<link href="' + themes['default'] + '" rel="stylesheet" />');
  themesheet.appendTo('head');
  $('.theme-link').click(function () {
    var themeurl = themes[$(this).attr('data-theme')];
    themesheet.attr('href', themeurl);
  });
});

function register(e) {
  var ref = $(this).find("[required]");
  $(ref).each(function () {
    if ($(this).val() === '') {
      alert("Required field should not be blank.");
      $(this).focus();
      e.preventDefault();
      return false;
    }
  });
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: 'request=register&data=' + formToJSON('#registerForm')
  }).done(function (response) {
    if (response === 'success') {
      alert("Registration email was sent to your email");
      window.location.replace("index.html");
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
  }).done(function (response) {
    if (response.status == 1) {
      localStorage.setItem('JWT', response.data);
      window.location.replace("dashboard.php");
    } else {
      alert(response.data);
    }
  });
  return false;
}

function create_restaurant() {
  var data = formToDict('#createForm');
  data['request'] = 'create_restaurant';
  data['jwt'] = localStorage.getItem('JWT');
  if(data['jwt'] == null)
  {
    alert("No jwt in local storage, abort creation of restaurant");
    return false;
  }
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: data
  }).done(function (response) {
      alert(response);
    }
  );
  return false;
}

// turns input from form into JSON format
function formToJSON(form) {
  return JSON.stringify(formToDict(form, ':input[name]:enabled'));
}

// Turns form into dictionary
function formToDict(form) {
  var dict = {};
  $(form).find(':input[name]:enabled').each(function () {
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

//this function compare password with c_password:
function validatePassword() {
  var password = document.getElementById("password");
  var confirm_password = document.getElementById("password_confirmation");
  console.log("merge");
  if (password.value !== confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

function showMsgAlert(component, message) {
  $.ajax({
    method: "GET", url: "components/" + component, success: function (data) {
      data = data.toString().replace("#msg#", message);
      $("#msgDiv").replaceWith(data);
      $("#msgDiv").fadeIn(800);
    }
  });
}

// Use this function to find out if user is manager
// uses jwt to check that
function isManager()
{
  var jwt = localStorage.getItem('JWT');
  if(jwt == null)
    return false;
  // TODO : get user category from jwt
  return true;
}
