<nav class='navbar navbar-inverse'>
  <div class='container-fluid'>
    <div class='navbar-header'>
      <a class='navbar-brand' href='index.php'>
        <img class="logo" src="img/Dinen small logo.png"/>
      </a>
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="navbar">
      <ul class='nav navbar-nav'>
        <!-- TODO: use 'class="active"' to mark the page the user is currently on. -->
        <li><a href='#'>Business</a></li>
        <li><a href='#'>Customers</a></li>
      </ul>
      <?php
        if(session_status() == PHP_SESSION_NONE)
          session_start();
        require_once 'php_scripts/restrict_access.php';
        if (logged_in())
          echo "
            <ul class='nav navbar-nav navbar-right'>
              <li><a><span class='glyphicon glyphicon-user'></span>[Insert Username Here]</a></li>
              <li><a href ='php_scripts/logout.php'>Logout</a></li>
            </ul>
          ";
        else
          echo "
            <ul class='nav navbar-nav navbar-right'>
              <li><a href='login.html'><span class='glyphicon glyphicon-log-in'></span>Log in</a></li>
              <li><a href='register.html'><span class='glyphicon glyphicon-user'></span>Sign Up</a></li>
            </ul>
          ";
      ?>
    </div>
  </div>
</nav>
