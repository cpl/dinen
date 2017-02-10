<!DOCTYPE html>
<html>
<head>
  <?php require_once 'common/head.php'; ?>
  <title>Dinen Login</title>
</head>
<body>
  <?php require_once 'common/navbar.php'; ?>
  <div class = "container">
    <?php
      session_start();
      $name = $_SESSION['name'];
      echo "Hello, $name. Restaurant list:";
      require_once 'php_scripts/get_restaurants.php'; echo getRestaurants();
    ?>
  </div>
</body>
</html>