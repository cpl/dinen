var serverData = {
    "myrestaurants":
            [
                {"restaurantName": "French Fries",
                    "description": "test1"
                },
                {"restaurantName": "Pancakes",
                    "description": "test2"
                }
            ]
};

function testJSON() {
    var restaurantsDiv = "<div>";
    //stringify converts the object into text JSON to be displayed
    console.log("serverData: " + JSON.stringify(serverData));
    for (i = 0; i < serverData.myrestaurants.length; i++) {
        restaurantsDiv += serverData.myrestaurants[i].restaurantName + " " + serverData.myrestaurants[i].description + "<br/>";
        //console.log("serverData: " + serverData.myrestaurants[i].restaurantName);
        //console.log("serverData: " + serverData.myrestaurants[i].description);
    }
    restaurantsDiv += "</div>";
    console.log(restaurantsDiv);
    $("#testJSON").replaceWith(restaurantsDiv);
}

$(function () {
    testJSON();
});