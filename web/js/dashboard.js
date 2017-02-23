//Input here everything related to dashboard handling
$(document).ready(function () {
  get_restaurants();
})

var apiURL = 'api/v1/api.php';

function get_restaurants()
{
  var data = {};
  data['request'] = 'get_restaurants';
  data['jwt'] = localStorage.getItem('JWT');
  console.log(JSON.stringify(data));
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
      if(response.status === 1)
      {
        // all is well, generate the html from response
        console.log(response);
      }
      else {
        // whoops, something went wrong
        console.log(response);
      }
    }
  );
  return false;
}
