var apiURL = 'api/v1/api.php';

var Status = {ERROR: 0, SUCCESS: 1};
var restaurants = {};
var position = null;
var allRestaurants = "";
var oneRestaurant = "";

$(function(){
  $('#search-button').click(search);
  oneRestaurant = $('#result-box').html();
  console.log(oneRestaurant);
  $('#result-box').html("");
  getPosition(getRestaurants);
});

// Get all restaurants in near vicinity of user
function getRestaurants(latitude, longitude)
{
  var requestData = {'request': 'get_restaurants_near_user',
                     'lat' : latitude,
                     'lng' : longitude};
  $.ajax({
    url: apiURL,
    type: 'POST',
    data: requestData
  }).done(generate_html_for_restaurants);
}

function getPosition()
{
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(wrapGpsPosition, wrapIpPosition,{timeout:10000});
  } else {
    wrapIpPosition("no error");
  }
}


function wrapIpPosition(error)
{
  // TODO: Add geoplugin to website
  position = {lat: parseFloat(geoplugin_latitude()),
              lng: parseFloat(geoplugin_longitude())};
  getRestaurants(position.lat, position.lng);
}

function wrapGpsPosition(pos) {
  var crd = pos.coords;
  position = {lat: parseFloat(crd.latitude), lng: parseFloat(crd.longitude)};
  getRestaurants(position.lat, position.lng);
};

function get_url_vars()
{
  var vars = {};
  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
  function(m,key,value) {
    vars[key] = value;
  });
  return vars;
}

function generate_html_for_restaurants(response)
{
  console.log(1);
  restaurants = response.data;
  allRestaurants = "";
  $('#result-box').html('');
  if (response.status === Status.SUCCESS) {
    response.data.forEach(generate_restaurant);
    console.log(allRestaurants);
    $("#result-box").html(allRestaurants);
  } else {
    $('#welcome').innerHTML = response.data;
  }
}

function generate_restaurant(restaurant)
{
  var tempRestaurant = oneRestaurant;
  tempRestaurant = tempRestaurant.toString().replace("#restaurantName#", restaurant.name);
  tempRestaurant = tempRestaurant.toString().replace("#restaurantDescription#", restaurant.description);
  allRestaurants += tempRestaurant;
  console.log(oneRestaurant);
}

function search()
{
  var searched = $('#search').val().toLowerCase();
  allRestaurants = "";
  $('#result-box').val('');
  restaurants.forEach(function(restaurant){
    var string = restaurant.name.toLowerCase();
    var result = string.indexOf(searched) >= 0;
    if(result === true)
      generate_restaurant(restaurant);
  });
  $("#result-box").html(allRestaurants);
}
