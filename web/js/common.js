var apiURL = 'api/v1/api.php';

var Status = { ERROR: 0, SUCCESS: 1 };

var themes = {
  'default': '//bootswatch.com/amelia/bootstrap.min.css',
  'amelia': '//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css',
  'cerulean': '//bootswatch.com/cerulean/bootstrap.min.css',
  'cosmo': '//bootswatch.com/cosmo/bootstrap.min.css',
  'cyborg': '//bootswatch.com/cyborg/bootstrap.min.css',
  'flatly': '//bootswatch.com/flatly/bootstrap.min.css',
  'journal': '//bootswatch.com/journal/bootstrap.min.css',
  'simplex': '//bootswatch.com/simplex/bootstrap.min.css',
  'slate': '//bootswatch.com/slate/bootstrap.min.css',
  'spacelab': '//bootswatch.com/spacelab/bootstrap.min.css',
  'united': '//bootswatch.com/united/bootstrap.min.css'
};

$(function () {
  var currentTheme
    = $('<link href="' + themes['default'] + '" rel="stylesheet" />');
  currentTheme.appendTo('head');
  $('.theme-link').click(function () {
    var themeURL = themes[$(this).attr('data-theme')];
    currentTheme.attr('href', themeURL);
  });
  loadPage('landing', true);
});

function loadPage(name, hasJS) {
  showPreloader(function () {
    $('#page_contents').load('html/' + name + '.html', function () {
      $.getScript('imperial/js/custom.js', function () {
        if (hasJS) {
          $.getScript('js/' + name + '.js', function () {
            hidePreloader();
          });
        } else {
          hidePreloader();
        }
      });
    });
  });
}

function showPreloader(callback) {
  $('html, body').css({
    'overflow': 'hidden',
    'height': '100%'
  });
  if ($('#preloader').css('display') == 'none') {
    $('#preloader').slideDown(400, function () {
      callback();
    });
  } else {
    callback();
  }
}

function hidePreloader() {
  $('#preloader').delay(400).slideUp(400, function () {
    $(this).hide();
    $('html, body').css({
      'overflow': 'auto',
      'height': 'auto'
    });
  });
}

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
      window.location.replace("dashboard");
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

function formToJSON(form) {
  return JSON.stringify(formToDict(form, ':input[name]:enabled'));
}

function isManager() {
  const JWT = getJWT();
  if(JWT == null)
    return false;
  // TODO: get user category from JWT
  return true;
}

function getJWT() {
  return localStorage.getItem('JWT');
}

function showMessageAlert(component, message) {
  $.ajax({
    method: "GET",
    url: "components/" + component,
  }).done(function (response) {
    response = response.toString().replace("#msg#", message);
    $("#msgDiv").replaceWith(response);
    $("#msgDiv").fadeIn(800);
  });
}
