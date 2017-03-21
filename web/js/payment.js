
var menuItems = {};
var orderItems = {};
var comments = {};

// TODO: Make payment OO
function Payment()
{
  var me = this;
}

function init()
{
  data = JSON.parse(sessionStorage.getItem('orderData'));
  menuItems = data['menuItems'];
  orderItems = data['orderItems'];
  comments = data['comments'];
}

function submitOrder()
{
  var requestData = {};
  requestData['restaurant'] = sessionStorage.getItem('restaurantId');
  requestData['comments'] = comments;
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
