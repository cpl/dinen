<!DOCTYPE html>
<html>
  <head>
    <?php include_once "common/head.php.inc"; ?>
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <title> Dinen - Log In </title>
  </head>

  <body>

      <!-- <div class="header">
          <a href="index.php">
              <img class = "logo" src="http://gdurl.com/PH8I"/>
          </a>
          <a id = "login" href = "login.php">LOG IN</a>
          <a id = "signup" href = "register.php">SIGN UP</a>

      </div> -->
      <?php include_once "common/navbar.php.inc"; ?>

      <!-- whatever name the backend puts for the action -->
      <div class="container-fluid form-container">
          <form id="loginForm" role="form" onsubmit="return login()">
            <div class="form-group">
              <input id="email" name="email" type="email" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input id="password" name="password" type="password" placeholder="Password" class="form-control">
            </div>
            <button class="btn btn-login btn-block" type="submit">Log In</button>
          </form>
      </div>

      <!-- <form id="login-form" class="navbar-form navbar-right" role="form" onsubmit="return login()">
        <div class="form-group">
          <input id="email" name="email" type="text" placeholder="Email" class="form-control">
        </div>
        <div class="form-group">
          <input id="password" name="password" type="password" placeholder="Password" class="form-control">
        </div>
        <button id="login-button" class="btn btn-success">Sign in</button>
        <a class="btn btn-default" href="register.php" role="button">Register</a>
      </form> -->

  </body>
</html>
