function Menu() {
  var me = this;
  this.init = function () {
    me.restaurantID = sessionStorage.getItem('restaurantID');
    me.items = {};
    me.allMenuItems = '';
    me.menuItems = $('#menu-table').html();
    me.getMenu();
    $("#done-button-for-item").click(me.addMenuItem);
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
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57))
        && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
      }
    });
  };

  // Get menu for restaurant
  // Needs to have 'menu' (menu id) and 'restaurant' (restaurant id)
  // as parameters
  this.getMenu = function () {
    var requestData = {'request': 'get_menu',
                       'restaurant_id' : me.restaurantID};
    $.ajax({
      url: apiURL,
      type: 'POST',
      data: requestData
    }).done(me.displayMenu);
    return false;
  };

  this.addMenuItem = function () {
    const JWT = localStorage.getItem('JWT');
    if(JWT == null) {
      console.log("User not logged in while creating items for restaurant.");
      return;
    }
    var requestData = {};
    requestData['request'] = 'add_menu_item';
    requestData['restaurant_id'] = me.restaurantID;
    requestData['jwt'] = JWT;
    // TODO: Add section from form
    requestData['section'] = $("#category").val();
    requestData['name'] = $("#food-name").val();
    requestData['price'] = $('#price').val();
    requestData['description'] = $('#food-description').val();
    $.ajax({
      url: apiURL,
      type: 'POST',
      data: requestData
    }).done(me.getMenu);
    // TODO: create new items on done
  };

  this.displayMenu = function (response) {
    $('#menu-table').html("");
    if (response.status === Status.SUCCESS) {
      var nr = 0;
      response.data.forEach(function (item) {
        nr++;
        var tempItem = $("<tr>");
        $("<td>", {text: nr}).appendTo(tempItem);
        $("<td>", {text: item.section}).appendTo(tempItem);
        $("<td>", {text: item.name}).appendTo(tempItem);
        $("<td>", {text: "$" + item.price}).appendTo(tempItem);
        var button = $("<td>");
        var id = item.id;
        $("<button>", {type:'button',
                       class:'btn btn-danger delete-button',
                       click: function()
                       {
                         me.removeItem(id);
                       }
                     }).appendTo(button);
        button.appendTo(tempItem)
        tempItem.appendTo($('#menu-table'));
      });
    } else {
      console.log(response);
    }
  }

  me.removeItem = function(id)
  {
    const JWT = localStorage.getItem('JWT');
    if(JWT == null) {
      console.log("User not logged in while creating items for restaurant.");
      return;
    }
    var requestData = {};
    requestData['restaurant_id'] = sessionStorage.getItem('restaurantID');
    requestData['jwt'] = JWT;
    requestData['menu_item_id'] = id;
    requestData['request'] = 'remove_menu_item';
    $.ajax({
      url: apiURL,
      type: 'POST',
      data: requestData
    }).done(function(response){
      me.getMenu();
    });
    return false;
  }
}
