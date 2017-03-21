var apiURL = 'api/v1/api.php';

var Status = { ERROR: 0, SUCCESS: 1 };

// Function copied from http://stackoverflow.com/a/1184667
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

function validatePassword() {
  var password = document.getElementById('password');
  var confirm_password = document.getElementById('password_confirmation');
  if (password.value != confirm_password.value) {
    confirm_password.setCustomValidity('Passwords do not match.');
  } else {
    confirm_password.setCustomValidity('');
  }
}

function isManager() {
  const JWT = getJWT();
  if(JWT == null)
    return false;
  // TODO: get user category from JWT
  return true;
}

// Function copied from http://stackoverflow.com/a/1026087
function capitaliseFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function getJWT() {
  return localStorage.getItem('JWT');
}