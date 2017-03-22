var apiURL = 'api/v1/api.php';
var Status = { ERROR: 0, SUCCESS: 1 };

function Order()
{
  var me = this;
  me.items = {};
  me.orderItems = [];
  me.sections = [];
  me.comments = "";
  me.init = function()
  {
    // add required onchange's and submit's to form and select input
    me.getMenu();
    $("#menus").change(me.changeItemsInSelect);
    $("#menuItems").change(me.changeItemDescription);
    $("#orderForm").submit(me.submitItem);
    $("#createOrderButton").click(me.submitOrder);
  }
  // Get menu for restaurant
  me.getMenu = function()
  {
    var restaurantId = sessionStorage.getItem('restaurantID');
    if(restaurantId == null)
      console.log("empty restaurantId");
    var requestData = {'request': 'get_menu',
                       'restaurant_id' : restaurantId};
    $.ajax({
      url: apiURL,
      type: 'POST',
      data: requestData
    }).done(me.processItems);

    return false;
  }

  // process the items sent by server
  // that is, update the select inputs
  // and add items to global array 'items'
  me.processItems = function(response)
  {
    console.log("ProcessItems: "+JSON.stringify(response));
    if (response.status === Status.SUCCESS) {
      me.items = response.data;
      me.items.forEach(function(item) {
        me.sections.push(item.section);
      });
      me.sections = $.unique(me.sections);
      for(var index in me.sections){
         $('#menus')
            .append($("<option></option>")
            .attr("value", me.sections[index])
            .text(me.sections[index]));
      }
      me.changeItemsInSelect(null);
    } else {
      console.log(response);
    }
  }

  // update the menu item options based on current menu selected
  me.changeItemsInSelect = function(sel)
  {
    $("#menuItems option").remove();
    me.items.forEach(function(item) {
      if(item.section == $('#menus').val())
      {
        $('#menuItems')
           .append($("<option></option>")
           .attr("value",item.name)
           .text(item.name));
      }
    });
    me.changeItemDescription(null);
  }

  // update the description of the order item to be created
  // based on select for menu section
  me.changeItemDescription = function(sel)
  {
    $("#itemDescription").text("");
    me.items.forEach(function(item) {
      if(item.name == $('#menuItems').val())
      {
        $("#itemDescription").text(item.description);
      }
    });
  }

  // submit item - creates and order item out of menu item
  // for sending the order later
  me.submitItem = function(event)
  {
    // for every item
    me.items.forEach(function(item) {
      // if the name of the item is specified in input select, add it
      // to array of all item ids and and create html for it
      if(item.name == $('#menuItems').val())
      {
        // $('#orderItems').append('Menu item: ' + item.name + ' in a ' +
        //                          item.section + '. Cost: $' + item.price +
        //                          '. Description: ' + item.description + '.<br>');
        //
        var tempItem = "<td>" + item.section + "</td>";
        tempItem += "<td>" + item.name + "</td>";
        tempItem += "<td>£" + item.price + "</td>";
        tempItem += "<td>" + "<button type='button' class='btn btn-danger delete-button' aria-label='delete button'>\n<span class='glyphicon glyphicon-minus' aria-hidden='true'></span>\n</button>" + "</td>";
        $('#orderItems').append("<tr>" + tempItem + "</tr>");
        me.orderItems.push(item.id);
      }
    });
    event.preventDefault();
  }

  me.submitOrder = function()
  {
    var orderData = {};
    orderData['menuItems'] = me.items;
    orderData['comments'] = $('#comments').val();
    orderData['orderItems'] = me.orderItems;
    sessionStorage.setItem('orderData', JSON.stringify(orderData));
    loadPage('payment');
    return false;
  }
}
