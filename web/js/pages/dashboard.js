function Dashboard() {
    var me = this;
    this.init = function () {
        me.allRestaurants = "";
        me.oneRestaurant = $('#section').html();
        $('#section').html("");
        me.getRestaurants();

        $('#to-index').click(function (event) {
            loadPage('landing');
            event.preventDefault();
        });

        $('#create-restaurant').click(function (event) {
            loadPage('register_restaurant');
            event.preventDefault();
        });

        $('#delete_restaurant').click(function (event) {
            console.log("merge");
            event.preventDefault();
            me.deleteRestaurant();
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
        if (response.status === Status.SUCCESS) {
            console.log(JSON.stringify(response));
            response.data.forEach(me.listRestaurant);
            //$("#section").replaceWith(me.allRestaurants);
        } else {
          if(response.data === "expired")
            signOut();
        }
    };

    this.listRestaurant = function (restaurant) {
        var tempRestaurant = me.oneRestaurant;
        tempRestaurant = tempRestaurant.toString().replace("#name#",
                restaurant.name);
        tempRestaurant = replaceAll(tempRestaurant.toString(), "#id#", restaurant.id);
        tempRestaurant = tempRestaurant.toString().replace("#description#",
                restaurant.description);
        tempRestaurant = tempRestaurant.toString().replace('#menu#',
                'return editRestaurantMenu(' + restaurant.id + ')');
        tempRestaurant = tempRestaurant.toString().replace('#cookModule#',
                'return gotoCookModule(' + restaurant.id + ')');
        $('#section').append(tempRestaurant);
    };

}

function replaceAll(str, find, replace) {
  return str.replace(new RegExp(find, 'g'), replace);
}

function deleteRestaurant(id) {
    console.log("deleted restaurant: " + id);
    const JWT = getJWT();
        if (JWT == null) {
            loadPage('landing');
            return false;
        }
    var requestData = {'request': 'remove_restaurant',
                       'restaurant_id': id,
                       'jwt': JWT,
                       'password': $('#password' + id).val(),
                       'email': $('#email' + id).val()};
    $.ajax({
        url: apiURL,
        type: 'POST',
        data: requestData
    }).done(processResponseDeleteRestaurant);
}

function processResponseDeleteRestaurant(response) {
    if (response.status === Status.SUCCESS) {
        loadPage('dashboard');
    }
    else{
        alert("Error deleting the restaurant.");
    }
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
