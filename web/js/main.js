// Here we will write the code responsible for communicating between frontend and backend
//this function modifies the registrationForm submit in order to send JSON data to server:
$(function () {
    $('#registerForm').on('submit', function (e) {
        e.preventDefault();
        //var dataParam = $(this).serialize();
        //console.log("my data param: " + dataParam);
        var dataJson = formToJSON("#registerForm");
        console.log("my data JSON: " + JSON.stringify(dataJson));
    });
});

$("#login-form").ajaxForm({url: 'php_scripts/login.php', type: 'post'})




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
