var apiURL = 'api/v1/api.php';

var Status = { ERROR: 0, SUCCESS: 1 };

$(function () {
  getMenu();
  $("#done-button-for-item").click(addMenuItem);
});

// Get menu for restaurant
// Needs to have 'menu' (menu id) and 'restaurant' (restaurant id)
// as parameters
function getMenu()
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

function addMenuItem(e)
{
  // if menu GET parameter doesn't exist, abort operation
  if(!('restaurant' in get_url_vars())) {
    console.log("No restaurant parameter in GET");
    return;
  }
  const JWT = localStorage.getItem('JWT');
  if(JWT == null) {
    console.log("User not logged in while creating items for restaurant.");
    return;
  }
  var requestData = {};
  requestData['request'] = 'add_menu_item';
  requestData['restaurant_id'] = get_url_vars()['restaurant'];
  requestData['jwt'] = JWT;
  // TODO: Add section from form
  requestData['section'] = 'CHANGEME';
  requestData['name'] = $("#name").val();
  requestData['price'] = $('#price').val();
  requestData['description'] = $('#description').val();
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: requestData
  });//.done(generate_html_for_menu);
  // TODO: create new items on done
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

// forces price to always be numbers or dot or commas only
$(document).ready(function() {
    $("#price").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
});

// makes price 2 decimals only
