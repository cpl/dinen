var apiURL = 'api/v1/api.php';

var Status = { ERROR: 0, SUCCESS: 1 };

$(function () {
  get_menu();
});

// Get menu for restaurant
// Needs to have 'menu' (menu id) and 'restaurant' (restaurant id)
// as parameters
function get_menu()
{
  // if menu GET parameter doesn't exist, abort operation
  if(!('restaurant' in get_url_vars())) {
    console.log("No restaurant parameter in GET");
    return;
  }
  var requestData = {'request': 'get_menu',
                     'restaurant_id' : get_url_vars()['restaurant']};
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: requestData
  }).done(generate_html_for_menu);

  return false;
}

function generate_html_for_menu(response)
{
  if (response.status === Status.SUCCESS) {
    $('#menu').append('Menu items: <br>');
    response.data.forEach(function (item) {
      $('#menu').append('Menu item: ' + item.name + ' in ' +
                                item.section + '. Cost: $' + item.price +
                                '. Description: ' + item.description + '.<br>');
    });
  } else {
    console.log(response);
  }
}

// function to get GET parameters
// Used as getUrlVars()['parameter']
// Copied from stack overflow answer
// Kinda surprised that neither JS nor JQuery has a url parameter getters
function get_url_vars()
{
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
    function(m,key,value) {
      vars[key] = value;
    });
    return vars;
}
