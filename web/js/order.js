var apiURL = 'api/v1/api.php';
var Status = { ERROR: 0, SUCCESS: 1 };
var items = {};
var sections = [];

$(function () {
  getMenu();
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
  }).done(processItems);

  return false;
}

function processItems(response)
{
  if (response.status === Status.SUCCESS) {
    items = response.data;
    items.forEach(function(item) {
      sections.push(item.section);
    });
    sections = $.unique(sections);
    console.log(items);
    console.log(sections);
    for(var index in sections){
       $('#menus')
          .append($("<option></option>")
          .attr("value",sections[index])
          .text(sections[index]));
    }
    changeItemsInSelect(null);
  } else {
    console.log(response);
  }
}

$("#menus").change(changeItemsInSelect);
$("#menuItems").change(changeItemDescription);
$("#orderForm").submit(submitItem);

function changeItemsInSelect(sel)
{
  $("#menuItems option").remove();
  items.forEach(function(item) {
    if(item.section == $('#menus').val())
    {
      $('#menuItems')
         .append($("<option></option>")
         .attr("value",item.name)
         .text(item.name));
    }
  });
  changeItemDescription(null);
}

function changeItemDescription(sel)
{
  $("#itemDescription").text("");
  items.forEach(function(item) {
    if(item.name == $('#menuItems').val())
    {
      $("#itemDescription").text(item.description);
    }
  });
}

function submitItem(event)
{
  items.forEach(function(item) {
    if(item.name == $('#menuItems').val())
    {
      $('#orderItems').append('Menu item: ' + item.name + ' in a ' +
                               item.section + '. Cost: $' + item.price +
                               '. Description: ' + item.description + '.<br>');
    }
  });
  event.preventDefault();
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
