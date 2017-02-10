<!DOCTYPE html>
<html>
<head>
  <?php require_once 'common/head.php'; ?>
  <title>Dinen Login</title>
</head>
<body>
<?php require_once 'common/navbar.php'; ?>
<div class = "container">
  <form id='login' action='login.php' method='post'>
    <label><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="email" required>

    <label><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>

    <button type="submit">Login</button>
    <input type="checkbox" checked="checked"> Remember me
  </form>
  <?php require_once 'php_scripts/login.php'; echo login(); ?>
</div>
</body>
</html>