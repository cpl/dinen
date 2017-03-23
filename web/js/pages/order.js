var apiURL = 'api/v1/api.php';
var Status = { ERROR: 0, SUCCESS: 1 };

function Order()
{
  var me = this;
  me.currentItemId = 0;
  me.menuItems = {};
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
      response.data.forEach(function(item) {
        me.menuItems[item.id] = item;
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
    console.log(me.menuItems);
    $.each(me.menuItems, function(id, item) {
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
    $.each(me.menuItems, function(id, item) {
      if(item.name == $('#menuItems').val())
      {
        $("#itemDescription").text(item.description);
      }
    });
  }

  me.updateItems = function()
  {
    $('#orderItems').html("");
    me.currentItemId = 0;
    me.orderItems.forEach(function(item){
      var tempItem = $("<tr>");
      $("<td>", {text: me.menuItems[item].section}).appendTo(tempItem);
      $("<td>", {text: me.menuItems[item].name}).appendTo(tempItem);
      $("<td>", {text: me.menuItems[item].price}).appendTo(tempItem);
      var button = $("<td>");
      var id = me.currentItemId;
      $("<button>", {type:'button',
                     class:'btn btn-danger delete-button',
                     click: function()
                     {
                       me.orderItems.splice(id, 1);
                       me.updateItems();
                     }
                   }).appendTo(button);
      button.appendTo(tempItem);
      tempItem.appendTo($('#orderItems'));
      me.currentItemId++;
    });
  }

  // submit item - creates and order item out of menu item
  // for sending the order later
  me.submitItem = function(event)
  {
    // for every item
    $.each(me.menuItems, function(id, item) {
      // if the name of the item is specified in input select, add it
      // to array of all item ids and and create html for it
      if(item.name == $('#menuItems').val())
      {
        me.orderItems.push(item.id);
      }
    });
    me.updateItems();
    event.preventDefault();
  }

  me.submitOrder = function()
  {
    var orderData = {};
    orderData['menuItems'] = me.menuItems;
    orderData['comments'] = $('#comments').val();
    orderData['orderItems'] = me.orderItems;
    sessionStorage.setItem('orderData', JSON.stringify(orderData));
    loadPage('payment');
    return false;
  }
}
