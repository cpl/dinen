var apiURL = 'api/v1/api.php';
var Status = { ERROR: 0, SUCCESS: 1 };
var items = {};
var orderItems = [];
var sections = [];
var comments = "";



// add required onchange's and submit's to form and select inputs
$(function(){
  //processItems(tmpServerResponse);
  getMenu();
  $("#menus").change(changeItemsInSelect);
  $("#menuItems").change(changeItemDescription);
  $("#orderForm").submit(submitItem);
  $("#createOrderButton").click(submitOrder);
});


// Get menu for restaurant
// Needs to have 'restaurant' (restaurant id)
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

// process the items sent by server
// that is, update the select inputs
// and add items to global array 'items'
function processItems(response)
{
  console.log("ProcessItems: "+JSON.stringify(response));
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

// update the menu item options based on current menu selected
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

// update the description of the order item to be created
// based on select for menu section
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

// submit item - creates and order item out of menu item
// for sending the order later
function submitItem(event)
{
  // for every item
  items.forEach(function(item) {
    // if the name of the item is specified in input select, add it
    // to array of all item ids and and create html for it
    if(item.name == $('#menuItems').val())
    {
      $('#orderItems').append('Menu item: ' + item.name + ' in a ' +
                               item.section + '. Cost: $' + item.price +
                               '. Description: ' + item.description + '.<br>');
      orderItems.push(item.id);
    }
  });
  event.preventDefault();
}

function submitOrder()
{
  var orderData = {};
  orderData['menuItems'] = menuItems;
  orderData['comments'] = comments;
  orderData['orderItems'] = orderItems;
  sessionStorage.setItem('orderData', JSON.stringify(orderData));
  loadPage('payment');
  return false;
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
