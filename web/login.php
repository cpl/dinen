<!DOCTYPE html>
<html>
<head>
  <?php require_once 'common/head.php'; ?>
  <title>Dinen Login</title>
</head>
<body>
<?php require_once 'common/navbar.php'; ?>
<div class = 'container'>
  <?php
    session_start();
    require_once 'php_scripts/login.php'; //echo login();
    require_once 'php_scripts/validators.php';
    echo "
      <form action='login.php' method='post'>
        <label for='email'><b>Email</b></label>
        <input type='email' class='form-control' id='email'
               placeholder='Enter Email' name='email' required>
        <br>
        <label for='password'><b>Password</b></label>
        <input type='password' class='form-control' id='password'
               placeholder='Enter Password' name='password'
               pattern='$password_regex' required>
        <br>
        <button type='submit' class='btn btn-primary'>Login</button>
      </form>
    ";
    if($_SESSION === NULL)
      echo 'Session is null';
    var_dump($_SESSION);
    ?>
</div>
</body>
</html>
