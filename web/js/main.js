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