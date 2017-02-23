<!DOCTYPE html>
<html>
<head>
  <?php use globals\UserType;

  require_once 'common/head.inc.php'; ?>
  <link rel="stylesheet" type="text/css" href="css/restaurants.css">
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
<?php
  require_once 'php/restrict_access.php';
  # Redirect non-managers to the login page.
  restrict_access(UserType::MANAGER);
?>
