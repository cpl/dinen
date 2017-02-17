<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>d
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Dinen - Home</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
        <link rel="shortcut icon" href="favicon.ico">

        <!-- Bootstrap for original CSS -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/index.css">

        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
              <a class="navbar-brand" href="index.html"><b>Dinen</b></a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
              <!-- <li><a class="btn btn-success" href="login.html">Log in</a></li> -->
              <!-- <li><a class="btn btn-default" href="register.html">Sign Up</a></li> -->
              <li><a href="login.html"><span class="glyphicon glyphicon-log-in"></span> Log in</a></li>
              <li><a href="register.html"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
            </ul>
          </div><!--/.navbar-collapse -->
        </div>
      </nav>
      <!-- the slider -->
      <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div  class="carousel-inner" role="listbox">
          <div class="item active">
            <img src="img/satisfied.jpg">
            <div class="carousel-caption">
              <h3>Never be afraid</h3>
              <p>You won't go another day without food.</p>
            </div>
          </div>

          <div class="item">
            <img src="img/food.jpg">
            <div class="carousel-caption">
              <h3>Fear No More</h3>
              <p>Get DINEN Get Food.</p>
            </div>
          </div>

          <div class="item">
             <img src="img/frustrated.jpg">
            <div class="carousel-caption">
              <h3>HUNGRY?</h3>
              <p>Don't know where to find food??!</p>
            </div>
          </div>
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
      <!-- end of slider, start of the next part -->
      <hr class="line-seperator">

      <div class="jumbotron">
          <div class="container">
            <h1>Hello, world!</h1>
            <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
            <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
          </div>
      </div>

    <hr class="line-seperator">
    <div class="container">
       Example row of columns

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
          <p>boy bodyDonec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
      </div>
    </div>
    <footer>
      <hr class="line-seperator">
      <span class="footer-text">Dinen is optimized for satisfying people with food. We create an easy interface for managers to manage their restaurants and customers to order food. While using this site, you agree to have read and accepted our terms of use, cookie and privacy policy.</span>
      <p class="footer-company">&copy; Dinen 2017</p>
    </footer>
      <!-- Original Jscript -->
      <script src="http://malsup.github.io/jquery.form.js"></script>
      <script src="js/main.js"></script> <!-- Here we will write the code responsible for communicating between frontend and backend -->
      <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
      <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
      <script src="js/vendor/bootstrap.js"></script>
  </body>
    
  
</html>
