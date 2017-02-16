<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <!--<html class="no-js" lang="">--> <!--<![endif]-->
    <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <!-- bootstrap -->
      <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
      <script src="js/vendor/jquery-1.11.2.min.js"></script>
      <script src="js/vendor/bootstrap.min.js"></script>
      <script src="js/main.js"></script>
      <link rel="shortcut icon" href="favicon.ico">

      <!-- Website CSS style -->
      <link rel="stylesheet" type="text/css" href="css/main.css">

      <!-- Page CSS style -->
      <!-- <link rel="stylesheet" type="text/css" href="css/index.css"> -->

      <!-- Website Font style -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

      <!-- Google Fonts -->
      <link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
      <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>

      <!-- Page title -->
      <title> Dinen </title>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
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

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>Hello, world!</h1>
        <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
        <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; Company 2015</p>
      </footer>
    </div> <!-- /container -->
    </body>
</html>
