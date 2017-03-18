var apiURL = 'api/v1/api.php';

var Status = {ERROR: 0, SUCCESS: 1};

//var response = {"status": 1, "data": [{"name": "French Fries", "description": "Best potatoes.", "category": "tavern"}, {"name": "test", "description": "test", "category": "tavern"}]};

var allRestaurants = "";
var oneRestaurant = $('#restaurants').html();
$('#restaurants').html("");
$(function () {
    get_restaurants();
    $('#logout').click(function () {
        var data = {};
        data['request'] = 'logout';
        data['jwt'] = localStorage.getItem('JWT');
        $.ajax({
          url: apiURL,
          type: 'POST',
          data: data
        }).done(function (response) {
          if (response.status === Status.SUCCESS) {
            window.location.replace("index.html");
          } else {
            alert(response.data);
          }
        });
        return false;
    });
    //generate_html_for_restaurants(response);
});

function get_restaurants() {
    const JWT = getJWT();
    if (JWT == null) {
        window.location.replace("login.html");
        return false;
    }
    var requestData = {'request': 'get_restaurants', 'jwt': JWT};
    $.ajax({
        url: apiURL,
        type: 'POST',
        data: requestData
    }).done(generate_html_for_restaurants);
    return false;
}

function generate_html_for_restaurants(response)
{
    console.log('Response from get restaurants: ' + JSON.stringify(response));
    if (response.status === Status.SUCCESS) {
        response.data.forEach(generate_restaurant);
        $("#restaurants").replaceWith(allRestaurants);
    } else {
        $('#welcome').innerHTML = response.data;
    }
}

function generate_restaurant(restaurant)
{
    console.log('Restaurant: ' + JSON.stringify(restaurant));
    var tempRestaurant = oneRestaurant;
    tempRestaurant = tempRestaurant.toString().replace("#name#", restaurant.name);
    tempRestaurant = tempRestaurant.toString().replace("#description#", restaurant.description);
    tempRestaurant = tempRestaurant.toString().replace('#menu#',
                                                       '/menu?restaurant=' + restaurant.id)
    allRestaurants += tempRestaurant;
}

function getJWT() {
    return localStorage.getItem('JWT');
}
