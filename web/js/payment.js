function Payment()
{
  var me = this;
  me.menuItems = {};
  me.orderItems = {};
  me.comments = {};
  me.oneOrder = "";
  me.init = function()
  {
    data = JSON.parse(sessionStorage.getItem('orderData'));
    data['menuItems'].forEach(function(item){
      me.menuItems[item.id] = item;
    });
    me.orderItems = data['orderItems'];
    me.comments = data['comments'];
    me.oneOrder = $('#menu-table').html();
    $('#menu-table').html("");
    me.populateTable();
    $('#payment-button').click(me.submitOrder);
  }

  me.populateTable = function()
  {
    var allItems = "";
    var totalAmount = 0;
    me.orderItems.forEach(function(item){
      var tempItem = me.oneOrder;
      tempItem = tempItem.toString().replace("#foodName#", me.menuItems[item].name);
      tempItem = tempItem.toString().replace("#foodCategory#", me.menuItems[item].section);
      tempItem = tempItem.toString().replace("#foodPrice#", "$" + me.menuItems[item].price);
      totalAmount += me.menuItems[item].price;
      allItems += tempItem;
    });
    $('#menu-table').html(allItems);
    var amountHtml = $('#amount').html();
    amountHtml = amountHtml.toString().replace('#amount#', "$" + totalAmount);
    $('#amount').html(amountHtml);
  }

  me.submitOrder = function()
  {
    var requestData = {};
    requestData['restaurant'] = sessionStorage.getItem('restaurantId');
    requestData['comments'] = me.comments;
    requestData['order_items'] = JSON.stringify(orderItems);
    requestData['request'] = 'create_order';
    $.ajax({
      url: apiURL,
      type: 'POST',
      data: requestData
    }).done(function(response){
      console.log(response);
    });
  }
}
