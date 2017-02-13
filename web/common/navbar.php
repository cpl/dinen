<!-- TODO: use 'class="active"' to mark the page the user is currently on. -->
<nav class='navbar navbar-default'>
  <div class='container-fluid'>
    <div class='navbar-header'>
      <!-- Optional TODO: Add dinen icon. -->
      <a class='navbar-brand' href='index.php'>Dinen</a>
    </div>
    <ul class='nav navbar-nav'>
      <li><a href='index.php'>Home</a></li>
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
            <li><a href ='php_scripts/logout.php'>Logout</a></li>
          </ul>
        ";
      else
        echo "
          <ul class='nav navbar-nav navbar-right'>
            <li><a href ='login.php'>Login</a></li>
            <li><a href ='register.php'>Sign up</a></li>
          </ul>
        ";
    ?>
  </div>
</nav>
