var allRestaurants;
var oneRestaurant;

function initPage() {
  allRestaurants = "";
  oneRestaurant = $('#restaurants').html();
  getRestaurants();

  $('#logout').click(function () {
    var data = {};
    data['request'] = 'logout';
    data['jwt'] = getJWT();
    $.ajax({
      url: apiURL,
      type: 'POST',
      data: data
    }).done(function (response) {
      if (response.status === Status.SUCCESS) {
        loadPage('login', true, {});
        localStorage.removeItem('JWT');
      } else {
        alert(response.data);
      }
    });
    return false;
  });

  $('#to-index').click(function(event){
    loadPage('landing', true, {});
    event.preventDefault();
  });

  $('#create-restaurant').click(function(event){
    loadPage('register_restaurant', true, {});
    event.preventDefault();
  });
}

function getRestaurants() {
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
    }).done(listRestaurants);
    return false;
}

function listRestaurants(response) {
    console.log('Response from get restaurants: ' + JSON.stringify(response));
    if (response.status === Status.SUCCESS) {
        response.data.forEach(listRestaurant);
        $("#restaurants").replaceWith(allRestaurants);
    } else {
        $('#welcome').innerHTML = response.data;
    }
}

function editRestuarantMenu(id) {
    loadPage('menu', true, {'id' : id});
    return false;
}

function listRestaurant(restaurant) {
    console.log('Restaurant: ' + JSON.stringify(restaurant));
    var tempRestaurant = oneRestaurant;
    tempRestaurant = tempRestaurant.toString().replace("#name#",
                                                       restaurant.name);
    tempRestaurant = tempRestaurant.toString().replace("#description#",
                                                       restaurant.description);
    tempRestaurant = tempRestaurant.toString().replace('#menu#',
                                                       'return editRestuarantMenu(' + restaurant.id + ')');
    allRestaurants += tempRestaurant;
}