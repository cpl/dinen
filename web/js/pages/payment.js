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
    me.menuItems = data['menuItems'];
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
    $('#amount').html(amountHtml);
    var commentsHtml = $('#comments').html();
    commentsHtml = commentsHtml.toString().replace('#comments#', "<br>Comments : " + me.comments);
    $('#comments').html(commentsHtml);

  }

  me.submitOrder = function()
  {
    var requestData = {};
    requestData['restaurant'] = sessionStorage.getItem('restaurantID');
    requestData['comments'] = me.comments;
    requestData['order_items'] = JSON.stringify(me.orderItems);
    requestData['request'] = 'create_order';
    $.ajax({
      url: apiURL,
      type: 'POST',
      data: requestData
    }).done(function(response){
      if(response.status === 1)
      {
        loadPage('landing');
        alert('Your order is on the way!');
      }
      else
      {
        alert('Sorry, something went wrong');
      }
    });
  }
}
