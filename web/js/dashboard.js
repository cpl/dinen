function Dashboard() {
  var me = this;
  this.init = function () {
    alert('test a');
    me.allRestaurants = "";
    me.oneRestaurant = $('#restaurants').html();
    $('#restaurants').html("");
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
      $("#restaurants").replaceWith(me.allRestaurants);
    } else {
      $('#welcome').innerHTML = response.data;
    }
  };

  this.listRestaurant = function (restaurant) {
    console.log('Restaurant: ' + JSON.stringify(restaurant));
    var tempRestaurant = me.oneRestaurant;
    tempRestaurant = tempRestaurant.toString().replace("#name#",
      restaurant.name);
    tempRestaurant = tempRestaurant.toString().replace("#description#",
      restaurant.description);
    tempRestaurant = tempRestaurant.toString().replace('#menu#',
      'return editRestaurantMenu(' + restaurant.id + ')');
    me.allRestaurants += tempRestaurant;
  };
}

function editRestaurantMenu(id) {
  loadPage('menu');
  return false;
}