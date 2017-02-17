var apiURL = 'api/v1/api.php';

var themes = {
    "default": "//bootswatch.com/amelia/bootstrap.min.css",
    "amelia" : "//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css",
    "cerulean" : "//bootswatch.com/cerulean/bootstrap.min.css",
    "cosmo" : "//bootswatch.com/cosmo/bootstrap.min.css",
    "cyborg" : "//bootswatch.com/cyborg/bootstrap.min.css",
    "flatly" : "//bootswatch.com/flatly/bootstrap.min.css",
    "journal" : "//bootswatch.com/journal/bootstrap.min.css",
    "readable" : "//bootswatch.com/readable/bootstrap.min.css",
    "simplex" : "//bootswatch.com/simplex/bootstrap.min.css",
    "slate" : "//bootswatch.com/slate/bootstrap.min.css",
    "spacelab" : "//bootswatch.com/spacelab/bootstrap.min.css",
    "united" : "//bootswatch.com/united/bootstrap.min.css"
}
$(function(){
   var themesheet = $('<link href="'+themes['default']+'" rel="stylesheet" />');
    themesheet.appendTo('head');
    $('.theme-link').click(function(){
       var themeurl = themes[$(this).attr('data-theme')]; 
        themesheet.attr('href',themeurl);
    });
});

function register() {
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: 'request=register&data=' + formToJSON('#registerForm')
  }).done(function(response) {
    if (response == 'success') {
      window.location.replace("index.php");
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
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: data
  }).done(function(response) {
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
