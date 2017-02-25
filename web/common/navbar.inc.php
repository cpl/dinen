<nav class='navbar navbar-inverse'>
  <div class='container-fluid'>
    <div class='navbar-header'>
      <a class='navbar-brand' href='index.html'>
        <img class="logo" src="img/Dinen small logo.png"/>
      </a>
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="navbar">
      <ul class='nav navbar-left navbar-nav'>
        <!-- TODO: use 'class="active"' to mark the page the user is currently on. -->
        <li><a href='#'>Business</a></li>
        <li><a href='#'>Customers</a></li>
      </ul>

      <ul class='nav navbar-nav navbar-right' id="user_info">
      </ul>
    </div>
  </div>
</nav>

<script>
  $(document).ready(function () {
    if (getJWT() == null) {
      $('#user_info').html(
        "<li><a href='login.html'><span class='glyphicon glyphicon-log-in'></span>Log in</a></li>"
        + "<li><a href='register.html'><span class='glyphicon glyphicon-user'></span>Sign Up</a></li>");
    } else {
      $('#user_info').html("<li><a href='#' id='logoutLink'>Logout</a></li>");
      var data = {};
      data['jwt'] = getJWT();
      data['request'] = 'logout';
      $('#logoutLink').click(function () {
        alert('Logging out.');
        $.ajax({
          url: 'api/v1/api.php',
          type: 'POST',
          data: data
        }).done(function (response) {
          // Make sure logout works.
          window.location.replace("dashboard.php");
        });
      });
    }
  });
  function getJWT() {
    return localStorage.getItem('JWT');
  }
</script>
