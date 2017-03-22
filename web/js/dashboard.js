function Dashboard() {
  var me = this;
  this.init = function () {
    me.allRestaurants = "";
    me.oneRestaurant = $('#section').html();
    $('#section').html("");
    me.getRestaurants();

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
          loadPage('landing');
          localStorage.removeItem('JWT');
        } else {
          alert(response.data);
        }
      });
      return false;
    });

    $('#to-index').click(function(event){
      loadPage('landing');
      event.preventDefault();
    });

    $('#create-restaurant').click(function(event){
      loadPage('register_restaurant');
      event.preventDefault();
    });
  };

  this.getRestaurants = function () {
    const JWT = getJWT();
    if (JWT == null) {
      loadPage('landing');
      return false;
    }

    var requestData = {'request': 'get_restaurants', 'jwt': JWT};
    $.ajax({
      url: apiURL,
      type: 'POST',
      data: requestData
    }).done(me.listRestaurants);
    return false;
  };

  this.listRestaurants = function (response) {
    console.log('Response from get restaurants: ' + JSON.stringify(response));
    if (response.status === Status.SUCCESS) {
      response.data.forEach(me.listRestaurant);
      $("#section").replaceWith(me.allRestaurants);
    } else {
      $('#welcome').innerHTML = response.data;
    }
  };

  this.listRestaurant = function (restaurant) {
    var tempRestaurant = me.oneRestaurant;
    tempRestaurant = tempRestaurant.toString().replace("#name#",
      restaurant.name);
    tempRestaurant = tempRestaurant.toString().replace("#description#",
      restaurant.description);
    tempRestaurant = tempRestaurant.toString().replace('#menu#',
      'return editRestaurantMenu(' + restaurant.id + ')');
    tempRestaurant = tempRestaurant.toString().replace('#cookModule#',
      'return gotoCookModule(' + restaurant.id + ')');
    me.allRestaurants += tempRestaurant;
  };
}

function editRestaurantMenu(id) {
  sessionStorage.setItem('restaurantID', id);
  loadPage('menu');
  return false;
}

function gotoCookModule(id) {
  sessionStorage.setItem('restaurantID', id);
  loadPage('cook');
  return false;
}
