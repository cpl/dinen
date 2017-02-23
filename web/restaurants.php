<!DOCTYPE html>
<html>
<head>
  <?php require_once 'common/head.inc.php'; ?>
  <link rel="stylesheet" type="text/css" href="css/restaurants.css">
  <script src="js/dashboard.js"></script>
  <title>My Restaurants</title>
</head>
<body>
  <?php require_once 'common/navbar.inc.php'; ?>
  <div class = 'container'>
    <?php
      echo "Hello, {$_SESSION['user_name']}. Restaurants you own:";
      require_once 'php/get_restaurants.php'; echo getRestaurants();
    ?>
    <div>
      <a href="register_restaurant.html">Add another restaurant</a>
    </div>
  </div>
</body>
</html>
