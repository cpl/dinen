var apiURL = 'api/v1/api.php';
var Status = {ERROR: 0, SUCCESS: 1};
function Search()
{
  var me = this;
  me.restaurants = {};
  me.position = null;
  me.allRestaurants = "";
  me.oneRestaurant = "";
  me.init = function()
  {
    $('#search').on('input', me.search);
    me.oneRestaurant = $('#result-box').html();
    $('#result-box').html("");
    me.getPosition();
  };
  // Get all restaurants in near vicinity of user
  me.getRestaurants = function(latitude, longitude)
  {
    var requestData = {'request': 'get_restaurants_near_user',
                       'lat' : latitude,
                       'lng' : longitude};
    $.ajax({
      url: apiURL,
      type: 'POST',
      data: requestData
    }).done(me.generate_html_for_restaurants);
  };

  me.getPosition = function()
  {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(me.wrapGpsPosition, me.wrapIpPosition,{timeout:10000});
    } else {
      me.wrapIpPosition("no error");
    }
  };


  me.wrapIpPosition = function(error)
  {
    // TODO: Add geoplugin to website
    me.position = {lat: parseFloat(geoplugin_latitude()),
                lng: parseFloat(geoplugin_longitude())};
    me.getRestaurants(me.position.lat, me.position.lng);
  };

  me.wrapGpsPosition = function(pos)
  {
    var crd = pos.coords;
    me.position = {lat: parseFloat(crd.latitude), lng: parseFloat(crd.longitude)};
    me.getRestaurants(me.position.lat, me.position.lng);
  };

  me.generate_html_for_restaurants = function(response)
  {
    me.restaurants = response.data;
    me.allRestaurants = "";
    $('#result-box').html('');
    if (response.status === Status.SUCCESS) {
      response.data.forEach(me.generate_restaurant);
      $("#result-box").html(me.allRestaurants);
    } else {
      $('#welcome').innerHTML = response.data;
    }
  };

  me.generate_restaurant = function(restaurant)
  {
    var tempRestaurant = me.oneRestaurant;
    tempRestaurant = tempRestaurant.toString().replace("#restaurantName#", restaurant.name);
    tempRestaurant = tempRestaurant.toString().replace("#restaurantDescription#", restaurant.description);
    tempRestaurant = tempRestaurant.toString().replace("#order#", "return goToOrdering(" + restaurant.id + ")");
    me.allRestaurants += tempRestaurant;
  };

  me.search = function()
  {
    var searched = $('#search').val().toLowerCase();
    me.allRestaurants = "";
    $('#result-box').val('');
    me.restaurants.forEach(function(restaurant){
      var string = restaurant.name.toLowerCase();
      var result = string.indexOf(searched) >= 0;
      if(result === true)
        me.generate_restaurant(restaurant);
    });
    $("#result-box").html(me.allRestaurants);
  }
}

function goToOrdering(id)
{
  sessionStorage.setItem('restaurantID', id);
  loadPage('order');
}
