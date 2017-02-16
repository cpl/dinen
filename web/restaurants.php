<!DOCTYPE html>
<html>
<head>
  <?php require_once 'common/head.php.inc'; ?>
  <link rel="stylesheet" type="text/css" href="css/restaurants.css">
  <title>My Restaurants</title>
</head>
<body>
  <?php require_once 'common/navbar.php.inc'; ?>
  <div class = 'container'>
    <?php
      echo "Hello, {$_SESSION['user_name']}. Restaurants you own:";
      require_once 'php_scripts/get_restaurants.php'; echo getRestaurants();
    ?>
    <div>
      <a href="register_restaurant.html">Add another restaurant</a>
    </div>
  </div>
</body>
</html>
<?php
  require_once 'php_scripts/restrict_access.php';
  # Redirect non-managers to the login page.
  restrict_access(UserType::MANAGER);
?>
