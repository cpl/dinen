<!DOCTYPE html>
<html>
  <head>
    <?php require_once 'common/head.php'; ?>
    <title>Dinen Registration</title>
  </head>
  <body>
  <?php require_once 'common/navbar.php'; ?>
  <div class = 'container'>
    <?php
      require_once 'php_scripts/register.php'; echo register();
      require_once 'php_scripts/validators.php';
      echo "
        <form action='register.php' method='post'>
          <label for='name'><b>Name</b></label>
          <input type='text' class='form-control' id='name'
                 placeholder='Enter Name' name='name' pattern='$full_name_regex'
                 required>
          <br>
          <label for='email'><b>Email</b></label>
          <input type='email' class='form-control' id='email'
                 placeholder='Enter Email' name='email' required>
          <br>
          <label for='password'><b>Password</b></label>
          <input type='password' class='form-control' id='password'
                 placeholder='Enter Password' name='password'
                 pattern='$password_regex' required>
          <br>
          <label for='c_password'><b>Confirm Password</b></label>
          <input type='password' class='form-control' id='c_password'
                 placeholder='Enter Password' name='c_password'
                 pattern='$password_regex' required>
          <br>
          <button type='submit' class='btn btn-primary'>Sign up</button>
        </form>
      ";
    ?>
  </div>
</body>
</html>