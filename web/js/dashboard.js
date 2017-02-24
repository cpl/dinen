//Input here everything related to dashboard handling
$(document).ready(function () {
  get_restaurants();
});

var apiURL = 'api/v1/api.php';

function get_restaurants()
{
  var data = {};
  data['request'] = 'get_restaurants';
  data['jwt'] = localStorage.getItem('JWT');
  console.log(JSON.stringify(data));
  if(data['jwt'] == null)
  {
    window.location.replace("login.html");
    return false;
  }
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: data
  }).done(function (response) {
      if(response.status === 1)
      {
        // all is well, generate the html from response
        console.log(response);
        response.data.forEach(function (restaurant) {
          $('#restaurants').append('You have a restuarant called '
            + restaurant.name + ' that is a ' + restaurant.category + '.<br>');
        });
      }
      else {
        // whoops, something went wrong
        console.log(response);
        $('#welcome').innerHTML = 'Dirty hacker!';
      }
    }
  );
  return false;
}
