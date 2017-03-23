var apiURL = 'api/v1/api.php';

var Status = {
    ERROR: 0,
    SUCCESS: 1
};

function Cook()
{
  var me = this;
  me.menuLoaded = false;
  me.menuItems = {};
  me.orders = {};

  me.init = function() {
    window.setInterval(me.getOrderItems, 10000);
    window.setInterval(me.getOrders, 10000);
    me.getMenu();
  }

  me.getOrders = function()
  {
    // if menu GET parameter doesn't exist, abort operation
    var restaurantId = sessionStorage.getItem('restaurantID');
    if(restaurantId == null)
      console.log("empty restaurantId");
    var requestData = {
        'request': 'get_orders',
        'restaurant_id': restaurantId
    };
    $.ajax({
        url: apiURL,
        type: 'POST',
        data: requestData
    }).done(function(response){
      me.orders = response.data;
    });
  }

  // Get unifinished orders for restaurant
  me.getOrderItems = function()
  {
      // if menu GET parameter doesn't exist, abort operation
      var restaurantId = sessionStorage.getItem('restaurantID');
      if(restaurantId == null)
        console.log("empty restaurantId");
      var requestData = {
          'request': 'get_unfinished_order_items',
          'restaurant_id': restaurantId
      };
      $.ajax({
          url: apiURL,
          type: 'POST',
          data: requestData
      }).done(me.processOrderItems);
  }

  // Get menu for restaurant
  // Needs to have 'restaurant' (restaurant id)
  // as parameters
  me.getMenu = function()
  {
      var restaurantId = sessionStorage.getItem('restaurantID');
      if(restaurantId == null)
        console.log("empty restaurantId");
      var requestData = {
          'request': 'get_menu',
          'restaurant_id': restaurantId
      };
      $.ajax({
          url: apiURL,
          type: 'POST',
          data: requestData
      }).done(me.processMenuItems);

      return false;
  }

  me.processOrderItems = function(response) {
      // TODO: Check for errors
      // don't process items if menu isn't loaded
      if (!me.menuLoaded)
          return;
      $('#order-items').empty();
      var data = response['data'];
      data.forEach(function(orderItem) {
          var string = "<tr>" +
              "<th>" + me.menuItems[orderItem.menu_item_id].name + "</th>" +
              "<th>" + orderItem.id +"</th>" +
              "<th>" + orderItem.time + "</th>" +
              "<th>" + orderItem.comments + "</th>" +
              "<th>" + me.genOrderItemCheckbox(orderItem) +
              "</tr>";
          $('#order-items').append(string);
      });
  }

  me.processMenuItems = function(response) {
      me.menuLoaded = true;
      var data = response['data'];
      data.forEach(function(menuItem) {
          me.menuItems[menuItem.id] = menuItem;
      });
      console.log(me.menuItems);
      me.getOrders();
  }

  me.genOrderItemCheckbox = function(orderItem) {
      return "<input type='checkbox' value='isFinished' " +
          "id='" + orderItem.id + "-completed' " +
          (orderItem.is_finished == 1 ? "checked" : "") +
          " onchange='onCheckboxChanged(this)'>";
  }
}

function onCheckboxChanged(checkbox) {
  if (checkbox.checked) {
    var requestData = {
      'request': 'mark_order_item_finished',
      'item': checkbox.id.split('-')[0]
    }
    $.ajax({
        url: apiURL,
        type: 'POST',
        data: requestData
    });
  }
}
