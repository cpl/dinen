<!DOCTYPE html>
<html>
 <head>
   <!-- imports -->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
   <title>Dinen - Search</title>
   <meta name="description" content="">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
   <link rel="shortcut icon" href="favicon.ico">

   <!-- Bootstrap for original CSS -->
   <link rel="stylesheet" href="css/bootstrap.css">
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <link rel="stylesheet" href="css/main.css">
   <link rel="stylesheet" href="css/bootstrap-theme.css">

   <!-- My css file for this page -->
   <link rel="stylesheet" type="text/css" href="css/search.css">

 </head>

 <body>

   <!-- navigation bar -->
   <?php require_once 'common/navbar.inc.php'; ?>

   <!-- All items go in this div -->
   <div class="container-fluid">

     <!-- Search bar and search button-->
     <div class="row">
       <div class="col-md-6 col-md-offset-3">
         <div class="input-group">
            <input type="text" class="form-control searchBox" placeholder="Search">
               <span class="input-group-btn">
                  <button class="searchButton" type="button">Go!</button>
               </span>
         </div>
       </div>
     </div>


   <!-- Another row for search results and filters -->
   <div class="row resultsAndFilters">

     <!-- List of search filters -->
  <nav>
     <div class="col-md-2 filtersBox">
       <dl>
         <dt><h4>Filters:</h4></dt>
         <dt>Restaurant category:</dt>
           <dd> <input type="checkbox" name="Tavern"/> Tavern </dd>
           <dd> <input type="checkbox" name="Pub"/> Pub </dd>
           <dd> <input type="checkbox" name="Restaurant"/> Restaurant </dd>
           <dd> <input type="checkbox" name="FastFood"/> Fast food </dd>
           <dd> <input type="checkbox" name="Takeaway"/> Takeaway </dd>
       </dl>
    </div>
  </nav>

  <!-- template for search results -->
  <div class="col-md-6 col-md-offset-1 resultsBox">
       <div><h4>#restaurantName#</h4></div>
       <div><p>#restaurantDescription#</p></div>
     <hr>
  </div>

</div> <!-- row for results and filters -->

   </div> <!-- container div -->

   <!-- Original Jscript -->
   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
   <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
   <script src="js/vendor/bootstrap.js"></script>
   <script src="http://malsup.github.io/jquery.form.js"></script>
   <script src="js/main.js"></script> <!-- Here we will write the code responsible for communicating between frontend and backend -->
 </body>

<!-- footer -->
 <footer class="navbar-fixed-bottom">
   <hr class="line-seperator">
   <span class="footer-text">Dinen is optimized for satisfying people with food. We create an easy interface for managers to manage their restaurants and customers to order food. While using this site, you agree to have read and accepted our terms of use, cookie and privacy policy.</span>
   <p class="footer-company">&copy; Dinen 2017</p>
 </footer>

</html>
