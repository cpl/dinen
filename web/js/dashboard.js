var apiURL = 'api/v1/api.php';

var Status = { ERROR: 0, SUCCESS: 1 };

$(function () {
  get_restaurants();
});

function get_restaurants() {
  const JWT = getJWT();
  if (JWT == null) {
    window.location.replace("login.html");
    return false;
  }
  var requestData = {'request': 'get_restaurants', 'jwt': JWT};
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: requestData
  }).done(function (response) {
    if (response.status === Status.SUCCESS) {
      response.data.forEach(function (restaurant) {
        $('#restaurants').append('You have a restuarant called '
          + restaurant.name + ' that is a ' + restaurant.category + '.<br>');
      });
    } else {
      console.log(response);
      $('#welcome').innerHTML = response.data;
    }
  });
  return false;
}

function getJWT() {
  return localStorage.getItem('JWT');
}
