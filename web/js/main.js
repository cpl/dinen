// Here we will write the code responsible for communicating between frontend and backend

var serverURL = ""; //  or: "/api/" put the server url here

//this function compare password with c_password:
function validatePassword() {
    var password = document.getElementById("password");
    var confirm_password = document.getElementById("c_password");
    if (password.value !== confirm_password.value) {
        confirm_password.setCustomValidity("Passwords Don't Match");
    } else {
        confirm_password.setCustomValidity('');
    }
}

//this function modifies the registrationForm submit in order to send JSON data to server:
/*$(function () {
    $('#registerForm').on('submit', function (e) {
        e.preventDefault();
        var dataJson = formToJSON("#registerForm");
        console.log("my data JSON: " + JSON.stringify(dataJson));
        requestWsPost(serverURL + "register.php", dataJson, "processRegisterResponse");
    });
});*/

function processRegisterResponse(response) {
    console.log("processRegisterResponse: " + JSON.stringify(response));
    // sessionStorage.setItem("lastResponseMessage",  response.message); // save message in browser session for other pages ...
    if (response.result === 'OK') {
        showMsgAlert("msg-info.html", response.message);
    } else {
        showMsgAlert("msg-error.html", response.message);
    }
}

function showMsgAlert(component, message) {
    $.ajax({method: "GET", url: "components/" + component, success: function (data) {
            data = data.toString().replace("#msg#", message);
            $("#msgDiv").replaceWith(data);
            $("#msgDiv").fadeIn(800);
        }});
}

function formToJSON(selectedForm) {
    var json = {};
    $(selectedForm).find(':input[name]:enabled').each(function () {
        var self = $(this);
        var name = self.attr('name');
        if (json[name]) {
            json[name] = json[name] + ',' + self.val();
        } else {
            json[name] = self.val();
        }
    });
    return json;
}

/**
 * _requestWsPost - private method for RESTFul API POST call
 * @param {string} urlCmd = serverURL + wsRoot + wsCmd
 * @param {string} requestData
 * @param {string} callBackFN = the name of the function will be called after we get the response
 */
function requestWsPost(urlCmd, requestData, callBackFN) {
    $.post(urlCmd, requestData)
            .done(function (data) {
                //console.log("_requestWsPost resp: " + JSON.stringify(data) + " - " + callBackFN);
                _processAllWSResponse(data, callBackFN);
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status && jqXHR.status === 400) {
                    console.log('Communication error: ' + jqXHR.responseText);
                } else {
                    console.log('Something went wrong: communication error!');
                }
            });
}

/**
 * _processAllWSResponse - private method only for requestWsPost
 * @param {string} responseData
 * @param {string} callBackFN
 * @returns {undefined}
 */
function _processAllWSResponse(responseData, callBackFN) {
    //console.log('_processAllWSResponse - callBackFN: ' + callBackFN + " - responseData: " + JSON.stringify(responseData));
    this[callBackFN](responseData);
}

function register()
{
  var formData = formToJSON("#registerForm");
  $.ajax(
    {
      url: 'php_scripts/register.php',
      type: 'POST',
      data: formData
   }).done(function(msg)
   {
       // If user created, redirect to restaurants.php, else print error to user
       if(msg == "User created")
           goToRestaurants();
       else
       {
           $("#msgDiv").text = msg;
       }
   });
   return false;
}

function login() {
  var formData = { email : $("#email").val(), password : $("#password").val()};
  $.ajax(
    {
      url: 'php_scripts/login.php',
      type: 'POST',
      data: formData
   }).done(goToRestaurants);
   return false;
}

function goToRestaurants()
{
  window.location.replace("restaurants.php");
}
